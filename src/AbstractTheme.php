<?php namespace Humweb\ThemeManager;

use Illuminate\Support\Facades\App;

/**
 * AbstractTheme.php
 *
 * Date: 8/15/14
 * Time: 11:15 AM
 */
class AbstractTheme {

    /**
     * Theme name
     * @var string
     */
    public $name = '';

    /**
     * Theme Author
     * @var string
     */
    public $author = '';

    /**
     * Theme description
     * @var string
     */
    public $description = '';

    /**
     * Theme version
     * @var string
     */
    public $version = '1.0';

    /**
     * Theme keywords
     * @var array
     */
    public $keywords = [];

    /**
     * Theme license type
     * @var string
     */
    public $licence = 'MIT';

    /**
     * Require files
     * @var array
     */
    public $require = [];

    /**
     * Event listeners
     * @var array
     */
    public $events = [
        'post.install' => '',
		'post.update' => '',
	];

    protected $path;
    protected $assets;

    public function __construct()
    {
//        $this->assets = App::make('theme.assets');
    }
    /**
     * Get theme config
     */
    public function getConfigSchema()
    {

    }

    public function asset()
    {
        return $this->assets;
    }

}