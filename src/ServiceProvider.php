<?php

namespace Humweb\ThemeManager;

use Humweb\Modules\ModuleBaseProvider;

class ServiceProvider extends ModuleBaseProvider
{

    protected $defer = true;

    protected $moduleMeta = [
        'name'    => '',
        'slug'    => 'theme-manager',
        'version' => '',
        'author'  => '',
        'email'   => '',
        'website' => '',
    ];


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
     * Bootstrap the application events.
     */
    public function boot()
    {

        //Bind theme manager
        //        $oldPaths = $this->app['view.finder']->getPaths();
        //        $oldHints = $this->app['view.finder']->getHints();

        // Register Settings
        $this->app['settings.schema.manager']->register('themes', ThemeSettingsSchema::class);
    }


    public function register()
    {

        $this->publishConfig();

        $this->app->singleton('theme', function ($app) {
            return new Theme($app['config']['theme-manager'], $app['view']);
        });
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['theme'];
    }
}
