<?php
namespace Oasis1992\Sociauth;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * User: gabriel_gerardo_rodriguez_diaz (oasis1992)
 * Date: 17/06/2016
 * Time: 11:17
 */
class SociauthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(!$this->app->routesAreCached()){
            include __DIR__ . '/routes.php';
        }
        $this->loadViewsFrom(base_path('resourses/views'), 'sociauth');

        $this->publishes([
            __DIR__.'/views' => base_path('resourses/views')
        ]);

        $this->publishes([
            __DIR__.'/migrations' => base_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__.'/models' => base_path('app')
        ]);

    }
    
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('Laravel\Socialite\SocialiteServiceProvider');
        $this->app->bind('Oasis1992\\Sociauth\\Contracts\\Redirects\\FacebookResponse', 'Oasis1992\\Sociauth\\Controllers\\ViewResponseController');
    }
}