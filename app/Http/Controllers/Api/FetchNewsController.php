<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NewsService;
use App\Http\Resources\FetchNewsResource;

class FetchNewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function fetch()
    {
        $total = $this->newsService->fetchAllSources();

        return new FetchNewsResource([
            'message' => 'Fetched from all sources',
            'total_articles_saved' => $total
        ]);
    }
}