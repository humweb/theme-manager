<?php namespace Humweb\ThemeManager;

use Illuminate\View\FileViewFinder as IlluminateFileViewFinder;
use Illuminate\View\ViewFinderInterface;

/**
 * Extends the core FileViewFinder class
 */
class FileViewFinder extends IlluminateFileViewFinder implements ViewFinderInterface
{

    /**
     * Add a namespace hint to the finder.
     *
     * @param  string  $namespace
     * @param  string|array  $hints
     * @param  bool $prepend Prepend/append namespace hints
     * @return void
     */
    public function addNamespace($namespace, $hints, $prepend = false)
    {
        $hints = (array) $hints;

        if (isset($this->hints[$namespace]))
        {
            if ($prepend) {

                foreach ($hints as $hint) {
                    array_unshift($this->hints[$namespace], $hint);
                }

                return;

            } else {
                $hints = array_merge($this->hints[$namespace], $hints);
            }
        }

        $this->hints[$namespace] = $hints;
    }

    public function setHints($hints = [])
    {
        $this->hints = $hints;
    }

}
