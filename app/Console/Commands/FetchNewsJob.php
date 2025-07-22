<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;

class FetchNewsJob extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch news from all sources';

    public function handle()
    {
        // Resolve the NewsService from the container
        $newsService = app(NewsService::class);

        // Call the service method directly
        $total = $newsService->fetchAllSources();

        // Show feedback in the console
        $this->info("Fetched from all sources. Total articles saved: $total");
    }
}
