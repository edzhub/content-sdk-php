<?php

namespace Zsl\ContentSdk;

use Illuminate\Support\ServiceProvider;

class ContentSdkServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/zsl-content.php',
            'zsl-content'
        );
        $this->publishes([
            __DIR__.'/config/zsl-content.php' => \config_path('zsl-content.php'),
        ]);
    }
}
