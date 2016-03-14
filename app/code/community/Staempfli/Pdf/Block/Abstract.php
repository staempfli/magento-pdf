<?php
/**
 * This file is part of the Staempfli project.
 *
 * Staempfli_Pdf is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  Staempfli
 * @package   Staempfli_Pdf
 * @author    Staempfli Webteam <webteam@staempfli.com>
 * @copyright 2016 Staempfli AG (http://www.staempfli.com/)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
abstract class Staempfli_Pdf_Block_Abstract extends Mage_Core_Block_Template
{
    /**
     * @var string
     */
    protected $content = array();

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $stylesheets = array();

    /**
     * @var bool
     */
    protected $path;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $template;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate($this->template);
        $this->setTitle('Stämpfli AG - Magento PDF Generator');
        $this->addStylesheet('/skin/frontend/base/default/pdf/css/default.css');
        $this->setBasePath(Mage::getBaseDir('base'));
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param null $content
     * @param null $element
     * @param null $attribute
     * @return $this
     */
    public function addContent($content = null, $element = null, $attribute = null)
    {
        $prefix = '';
        $suffix = '';

        if ($element) {
            $prefix = '<' . $element . $this->_getAttributes($attribute) .'>';
            $suffix = '</' . $element . '>';
        }

        $this->content[] = $prefix . $content . $suffix;
        return $this;
    }

    /**
     * @param null $id
     * @return $this
     */
    public function addSection($id = null)
    {
        $this->addContent('<section id="' . $id . '">');
        return $this;
    }

    /**
     * @return $this
     */
    public function endSection()
    {
        $this->addContent('</section>');
        return $this;
    }

    /**
     * @param null $id
     * @return $this
     */
    public function addDiv($id = null)
    {
        $this->addContent('<div id="' . $id . '">');
        return $this;
    }

    /**
     * @return $this
     */
    public function endDiv()
    {
        $this->addContent('</div>');
        return $this;
    }

    /**
     * @param null|string $src
     * @param null|string $alt
     * @param null|array $attribute
     * @return $this
     */
    public function addImage($src = null, $alt = null, $attribute = null)
    {
        if (stripos($src, 'base64,') !== false) {
            $this->addContent('<img src="'. $src . '"  alt="' . $alt . '"' . $this->_getAttributes($attribute) . '/>');
        } else {
            $this->addContent('<img src="'. $this->path . str_replace($this->path, '', $src) . '"  alt="' . $alt . '"' . $this->_getAttributes($attribute) . '/>');
        }
        return $this;
    }

    /**
     * @param null|string $data
     * @param null|array $attribute
     * @return $this
     */
    public function addObject($data = null, $attribute = null)
    {
        if (stripos($data, 'base64,') !== false) {
            $this->addContent('<object data="'. $data . '" ' . $this->_getAttributes($attribute) . '/></object>');
        } else {
            $this->addContent('<object data="'. $this->path . str_replace($this->path, '', $data) . '" ' . $this->_getAttributes($attribute) . '></object>');
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        if (is_string($title)) {
            $this->title = $title;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        if ($this->language) {
            return $this->language;
        }
        return substr(Mage::app()->getLocale()->getLocaleCode(), 0, 2);
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage($language)
    {
        if (is_string($language)) {
            $this->language = $language;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getStylesheets()
    {
        if ($this->path) {
            $data = array();
            foreach ($this->stylesheets as $key => $file) {
                $data[$key] =  $this->path . $file;
            }
            return $data;
        }
        return $this->stylesheets;
    }

    /**
     * @param $stylesheet
     * @return $this
     */
    public function addStylesheet($stylesheet)
    {
        if (is_string($stylesheet)) {
            $this->stylesheets[pathinfo($stylesheet, PATHINFO_FILENAME)] = $stylesheet;
        }
        return $this;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setBasePath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBasePath()
    {
        return $this->path;
    }

    /**
     * @param $attribute
     * @return string
     */
    private function _getAttributes($attribute)
    {
        $attr = '';
        if ($attribute) {
            if (is_string($attribute)) {
                $attr = ' ' . $attribute;
            } elseif (is_array($attribute)) {
                foreach ($attribute as $name => $value) {
                    $attr .= ' ' . $name . '="' . $value . '"';
                }
            }
        }

        return $attr;
    }
}
