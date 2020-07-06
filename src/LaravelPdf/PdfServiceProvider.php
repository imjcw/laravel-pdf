<?php

namespace niklasravnsborg\LaravelPdf;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class PdfServiceProvider extends BaseServiceProvider
{
    const CONFIG_KEY = 'pdf';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /*
    * Bootstrap the application service
    *
    * @return void
    */
    public function boot()
    {
        // In Lumen application configuration files needs to be loaded implicitly
        if ($this->app instanceof \Laravel\Lumen\Application) {
            $this->app->configure(self::CONFIG_KEY);
        } else {
            $this->publishes([$this->configPath() => config_path('pdf.php')]);
        }
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'pdf');

        $this->app->bind('mpdf.wrapper', function($app) {
            return new PdfWrapper(config(self::CONFIG_KEY));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('mpdf.pdf');
    }

    /**
     * Default config file path
     *
     * @return string
     */
    protected function configPath(): string
    {
        return __DIR__ . '/../config/pdf.php';
    }
}
