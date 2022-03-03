<?php

namespace ThibeDev\DropzoneChunk\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use ThibeDev\DropzoneChunk\Facades\ChunkService as ChunkFacade;
use ThibeDev\DropzoneChunk\Services\ChunkService;

class DropzoneChunkServiceProvider extends ServiceProvider
{
    /**
     * When the service is being booted.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/dropzonechunk.php' => config_path('dropzonechunk.php'),
        ]);
        $this->app->bind('chunk-service', ChunkService::class);
        $loader = AliasLoader::getInstance();
        $loader->alias('ChunkService', ChunkFacade::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/dropzonechunk.php', 'dropzonechunk'
        );
    }
}
