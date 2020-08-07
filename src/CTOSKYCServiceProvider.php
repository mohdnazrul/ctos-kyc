<?php
/**
 * API Library for CTOS KYC.
 * User: Mohd Nazrul Bin Mustaffa
 */
namespace MohdNazrul\CTOSKYCLaravel;

use Illuminate\Support\ServiceProvider;

class CTOSKYCServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/ctoskyc.php' => config_path('ctoskyc.php'),
        ], 'ctoskyc');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ctoskyc.php','ctoskyc');

        $this->app->singleton('CTOSApi', function ($app){
            $config     =   $app->make('config');
            $username   =   $config->get('ctoskyc.username');
            $password   =   $config->get('ctoskyc.password');
            $serviceURL =   $config->get('ctoskyc.serviceUrl');

            return new CTOSApi($serviceURL, $username, $password);

        });
    }

    public function provides() {
        return ['CTOSKYCApi'];
    }
}
