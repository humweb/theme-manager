<?php

namespace Humweb\ThemeManager\Editor;

use Humweb\ThemeManager\Editor\Exceptions\TemplateFieldsNotFound;

/**
 * User: ryun
 * Date: 4/8/18
 * Time: 11:04 PM
 */
class LayoutParser
{

    /**
     * @var string
     */
    protected $attributeName = 'data-section';


    /**
     * @param $templateString
     *
     * @return mixed
     * @example <div section="body">
     */
    public function getSectionNames($templateString)
    {
        $re = '/'.$this->attributeName.'=\"([^"]*)\"/';

        if (preg_match_all($re, $templateString, $matches) > 0) {
            return $matches[1];
        } else {
            throw new TemplateFieldsNotFound();
        }
    }


    /**
     * @param $file
     *
     * @return mixed
     */
    public function parseFile($file)
    {
        return $this->getSectionNames($this->readFile($file));
    }


    /**
     * @param $filename
     *
     * @return mixed
     */
    public function readFile($filename)
    {
        return file_get_contents($filename);
    }


    /**
     * @param string $attributeName
     *
     * @return LayoutParser
     */
    public function setAttributeName(string $attributeName): LayoutParser
    {
        $this->attributeName = $attributeName;

        return $this;
    }
}