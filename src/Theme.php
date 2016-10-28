<?php namespace Humweb\ThemeManager;

use App;
use Humweb\ThemeManager\Exceptions\ThemeClassNotFound;
use Humweb\ThemeManager\Exceptions\ThemeDirectoryNotFound;
use Illuminate\View\ViewFinderInterface;

class Theme {

    protected $config;

    protected $finder;

    protected $active;

    protected $theme;

    public function __construct($config = [], ViewFinderInterface $finder)
    {
        $this->config = $config;
        $this->finder = $finder;
    }

    /**
     * Initialize the theme instance
     *
     * @return void
     */
    public function init()
    {
        $this->load($this->config['active']);
        $this->prependThemeLocations();
    }

    public function load($name)
    {
        //Set activ theme
        $this->setActive($name);

        //Validate theme
        $this->validateTheme();

        //Load serviceprovider
        $this->spawnThemeClass();

        //run autoloads
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
        if ( ! is_dir($this->getActiveDir()))
        {
            throw new ThemeDirectoryNotFound;
        }
        if ( ! file_exists($this->getActiveDir('Theme.php')))
        {
            throw new ThemeClassNotFound($this->getActiveDir());
        }
    }

    /**
     * Get the full path to the active theme
     *
     * @return string The path
     */
    public function activeThemePath($path = '')
    {
        return $this->baseThemesPath(rtrim($this->active,'/').'/'.ltrim($path,'/'));
    }

    public function getActiveDir($file = '')
    {
        return rtrim($this->activeThemePath(),'/').'/'.$file;
    }

    public function baseThemesPath($theme = '')
    {
        return base_path(trim($this->config['themes_dir'],'/').'/'.$theme);
    }

    protected function spawnThemeClass()
    {
        include $this->activeThemePath('Theme.php');
        $this->theme = new Theme($this->config, $this->finder);
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
        // add theme hints to existing namespaces
        foreach ($this->finder->getHints() as $namespace => $hints)
        {
            $this->finder->addNamespace($namespace, $this->activeThemePath() . '/views/modules/' . $namespace, true);
        }

        // add theme views path
        $this->finder->prependLocation($this->activeThemePath() . '/views');
    }

}