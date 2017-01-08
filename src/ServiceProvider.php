<?php

namespace Humweb\ThemeManager;

use Humweb\Modules\ModuleBaseProvider;
use Illuminate\View\Factory;

class ServiceProvider extends ModuleBaseProvider
{

    protected $moduleMeta = [
        'name' => '',
        'slug' => 'theme-manager',
        'version' => '',
        'author' => '',
        'email' => '',
        'website' => '',
    ];

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        //Bind theme manager
        $oldPaths = $this->app['view.finder']->getPaths();
        $oldHints = $this->app['view.finder']->getHints();

        // Register Settings
        $this->app['settings.schema.manager']->register('themes', ThemeSettingsSchema::class);

        // Override core view finder
        $this->app->bind('view.finder', function ($app) use ($oldPaths, $oldHints) {
            $finder = new FileViewFinder($app['files'], $oldPaths);
            $finder->setHints($oldHints);

            return $finder;
        });

        // Override core view instance
        $this->app->singleton('view', function ($app) {
            // Next we need to grab the engine resolver instance that will be used by the
            // environment. The resolver will be used by an environment to get each of
            // the various engine implementations such as plain PHP or Blade engine.
            $resolver = $app['view.engine.resolver'];

            $finder = $app['view.finder'];

            $env = new Factory($resolver, $finder, $app['events']);

            // We will also set the container instance on this view environment since the
            // view composers may be classes registered in the container, which allows
            // for great testable, flexible composers for the application developer.
            $env->setContainer($app);

            $env->share('app', $app);

            return $env;
        });
        $this->app->singleton('theme', function ($app) {
//            dd($app['view.finder']);
            return new Theme($app['config']['theme-manager'], $app['view.finder']);
        });
        // Wait for modules to be booted before we init themes
        // so we can override the modules views with the themes views if available
        //$this->onEvent('modules.booted', function($moduleProvider){
        $this->app['theme']->init();
        //});

        //SettingsSchema::register('theme', 'Humweb\ThemeManager\ThemeSettingsSchema');
    }


    public function register()
    {
        $this->publishConfig();
    }


    public function getAdminMenu()
    {
        return [
            'Settings' => [
                [
                    'label' => 'Theme',
                    'url'   => route('get.admin.settings.form', ['theme']),
                ]
            ]
        ];
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
