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
    protected $content;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $stylesheets = array();

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
        $this->setTitle('Staempfli PDF Generator');
        $this->setContent('No content set!');
        $this->setStylesheets(array(
            'default' => Mage::getBaseDir('base') . '/skin/frontend/base/default/pdf/css/default.css'
        ));
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        if (is_string($content)) {
            $this->content = $content;
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
        if($this->language) {
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
        return $this->stylesheets;
    }

    /**
     * @param array $stylesheets
     * @return $this
     */
    public function setStylesheets($stylesheets)
    {
        if (is_array($stylesheets)) {
            $this->stylesheets = $stylesheets;
        }
        return $this;
    }
}
