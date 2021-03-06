<?php namespace Humweb\ThemeManager;

use Humweb\ThemeManager\Exceptions\ThemeClassNotFound;
use Humweb\ThemeManager\Exceptions\ThemeDirectoryNotFound;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\File;

class Theme
{

    protected $config;

    protected $view;

    protected $active;

    protected $theme;


    public function __construct($config = [], Factory $view)
    {
        $this->config = $config;
        $this->view   = $view;
        $this->load($this->config['active']);

        $this->prependThemeLocations();
    }


    public function load($name)
    {
        //Set activ theme
        $this->setActive($name);

        if ($this->active) {
            //Validate theme
            $this->validateTheme();

            //Load serviceprovider
            $this->createThemeClass();
        }
    }


    /**
     * Set the active theme
     *
     * @param string $theme The theme name
     */
    public function setActive($theme)
    {
        $this->active = $theme;
    }


    public function validateTheme()
    {
        if ( ! is_dir($this->getActiveDir())) {
            throw new ThemeDirectoryNotFound;
        }
        if ( ! file_exists($this->getActiveDir('Theme.php'))) {
            throw new ThemeClassNotFound($this->getActiveDir());
        }
    }


    public function getActiveDir($file = '')
    {
        return rtrim($this->activeThemePath(), '/').'/'.$file;
    }


    /**
     * Get the full path to the active theme
     *
     * @return string The path
     */
    public function activeThemePath($path = '')
    {
        return $this->baseThemesPath(rtrim($this->active, '/').'/'.trim($path, '/'));
    }


    public function baseThemesPath($theme = '')
    {
        return $this->basePath().'/'.$theme;
    }


    public function basePath($theme = '')
    {
        return base_path(trim($this->config['themes_dir'], '/'));
    }


    protected function createThemeClass()
    {
        require_once $this->activeThemePath('Theme.php');

        $this->theme = new \Theme($this->config, $this->view);

        return $this->theme;
    }


    /**
     * Override views with theme views
     * it will fallback to normal view location if none are found in the theme
     *
     * @return void
     */
    protected function prependThemeLocations()
    {
        $this->view->getFinder()->prependLocation($this->activeThemePath('views'));
    }


    public function getUrlPath($path = '')
    {
        return str_replace(public_path(), '', $this->activeThemePath($path));
    }


    public function getAvailable()
    {

        return collect(File::directories($this->basePath()))->mapWithKeys(function ($path) {
            return [
                basename($path) => title_case(basename($path))
            ];
        });
    }
}