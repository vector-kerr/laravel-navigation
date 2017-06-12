<?php

namespace Vector88\Navigation;
use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider {

    public function boot() {
        $rootPath = __DIR__;
        $this->loadViewsFrom( $rootPath . '/views', 'Vector88/navigation' );
        $this->loadRoutesFrom( $rootPath . '/routes.php' );
        $this->publishes( [
            $rootPath . '/views' => resource_path( 'views/vendor/Vector88/navigation' )
        ] );
    }

    public function register() {
        $this->app->bind(
            \Vector88\Navigation\Contracts\Navigation::class,
            \Vector88\Navigation\Services\Navigation::class
        );

    }

}
