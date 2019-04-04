<?php

namespace Humweb\ThemeManager;

use Humweb\Modules\ModuleBaseProvider;

class ServiceProvider extends ModuleBaseProvider
{

    protected $moduleMeta = [
        'name'    => 'Theme Manager',
        'slug'    => 'theme',
        'version' => '1.0',
        'author'  => '',
        'email'   => '',
        'website' => '',
    ];



    /**
     * Bootstrap the application events.
     */
    public function boot()
    {

        $this->app['modules']->put('theme', $this);
        $this->app['settings.schema.manager']->register('theme', ThemeSettingsSchema::class);
//        $this->app['theme']->load();
    }


    public function register()
    {

        $this->publishConfig();
        $this->app->singleton('theme', function ($app) {
            $app['config']->set('theme-manager.active', $app['settings']->getVal('theme.current'));
            return new Theme($app['config']['theme-manager'], $app['view']);
        });

    }


    public function getAdminMenu()
    {
        return [
            'Settings' => [
                [
                    'label' => 'Theme',
                    'url'   => route('get.admin.settings.module', ['theme']),
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
        return ['theme'];
    }
}
