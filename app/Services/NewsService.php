<?php

namespace App\Services;

use App\Models\News;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewsService
{
    public function getNews(Request $request)
    {
        $query = News::query();

        // User preferences
        $prefs = UserPreference::where('user_id', $request->query('user_id'))->first();
        $sources = [];
        if ($prefs) {
            if ($prefs->use_newsapi) $sources[] = 'NewsAPI';
            if ($prefs->use_gnews) $sources[] = 'GNews';
            if ($prefs->use_mediastack) $sources[] = 'MediaStack';
        }
        if (!empty($sources)) {
            $query->whereIn('api_source', $sources);
        }

        // Filters
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->keyword}%")
                  ->orWhere('description', 'like', "%{$request->keyword}%");
            });
        }
        foreach (['source', 'category', 'author'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 'like', "%{$request->$filter}%");
            }
        }
        if ($request->filled('dateFrom')) {
            $query->where('published_at', '>=', $request->dateFrom);
        }
        if ($request->filled('dateTo')) {
            $query->where('published_at', '<=', $request->dateTo);
        }

        $limit = $request->input('limit', 9);
        $offset = $request->input('offset', 0);

        return $query->orderByDesc('published_at')
            ->skip($offset)
            ->take($limit)
            ->get();
    }

    public function saveArticle(array $data): int
    {
        $exists = News::where('url', $data['url'])->exists();
        if ($exists) return 0;

        if (isset($data['published_at'])) {
            try {
                $data['published_at'] = Carbon::parse($data['published_at'])->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $data['published_at'] = now();
            }
        }

        News::create($data);
        return 1;
    }

    public function fetchFromNewsAPI()
    {
        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'country' => 'us',
            'pageSize' => 10,
            'apiKey' => config('services.newsapi.key'),
        ]);
        if ($response->failed()) return 0;

        $saved = 0;
        foreach ($response->json('articles') as $item) {
            $saved += $this->saveArticle([
                'title' => $item['title'],
                'description' => $item['description'],
                'content' => $item['content'],
                'source' => $item['source']['name'] ?? 'NewsAPI',
                'author' => $item['author'],
                'published_at' => $item['publishedAt'],
                'url' => $item['url'],
                'urlToImage' => $item['urlToImage'],
                'category' => 'General',
                'api_source' => 'NewsAPI',
            ]);
        }
        return $saved;
    }

    public function fetchFromGNews()
    {
        $response = Http::get('https://gnews.io/api/v4/top-headlines', [
            'token' => config('services.gnews.key'),
            'lang' => 'en',
            'max' => 10
        ]);
        if ($response->failed()) return 0;

        $saved = 0;
        foreach ($response->json('articles') as $item) {
            $saved += $this->saveArticle([
                'title' => $item['title'],
                'description' => $item['description'],
                'content' => $item['content'],
                'source' => $item['source']['name'] ?? 'GNews',
                'author' => $item['source']['name'],
                'published_at' => $item['publishedAt'],
                'url' => $item['url'],
                'urlToImage' => $item['image'],
                'category' => 'General',
                'api_source' => 'GNews',
            ]);
        }
        return $saved;
    }

    public function fetchFromMediaStack()
    {
        $response = Http::get('http://api.mediastack.com/v1/news', [
            'access_key' => config('services.mediastack.key'),
            'languages' => 'en',
        ]);
        if ($response->failed()) return 0;

        $saved = 0;
        foreach ($response->json('data') as $item) {
            $saved += $this->saveArticle([
                'title' => $item['title'],
                'description' => $item['description'],
                'content' => $item['description'],
                'source' => $item['source'],
                'author' => $item['author'] ?? 'Unknown',
                'published_at' => $item['published_at'],
                'url' => $item['url'],
                'urlToImage' => $item['image'] ?? null,
                'category' => $item['category'] ?? 'General',
                'api_source' => 'MediaStack',
            ]);
        }
        return $saved;
    }

    public function fetchAllSources()
    {
        $total = 0;
        $total += $this->fetchFromNewsAPI();
        $total += $this->fetchFromGNews();
        $total += $this->fetchFromMediaStack();
        return $total;
    }
}