<?php namespace Freez0n\SypexGeo;

use Freez0n\SypexGeo\Commands\UpdateBaseCommand;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;
use Freez0n\SypexGeo\Sypex\SxGeo;
use Freez0n\SypexGeo\Sypex\SxGeoHttp;
use Freez0n\SypexGeo\Geo\SypexGeo;

class SypexGeoServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(){
        $dir = __DIR__ . '/../../publish/';
        $this->publishes([
            $dir . 'config/sypexgeo.php' => config_path('sypexgeo.php'),
            $dir . 'storage/.gitignore'   => storage_path('sypexgeo/.gitignore'),
        ]);
        if($this->app->runningInConsole()){
            $this->commands([
                UpdateBaseCommand::class
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(){
        // Register providers.
        $this->app->singleton('sypexgeo', function($app){
            /** @var Repository $config */
            $config = $app['config'];
            $type = $config->get('sypexgeo.type', 'file');

            $settings = $config->get('sypexgeo.types.' . $type);

            switch(array_get($settings, 'driver', 'file')){
                case 'api':
                    $license_key = array_get($settings, 'license_key', '');
                    $instance = new SxGeoHttp($license_key);
                    break;
                default:
                case 'file':
                    $path = array_get($settings, 'path');
                    $instance = new SxGeo($path);
                    break;
            }

            return new SypexGeo($instance, $config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(){
        return ['sypexgeo'];
    }

}