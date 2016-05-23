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
abstract class Staempfli_Pdf_Model_Abstract extends Mage_Core_Model_Abstract
{
    const ORIENTATION_PORTRAIT = 'Portrait';
    const ORIENTATION_LANDSCAPE = 'Landscape';

    const SIZE_A4 = 'A4';
    const SIZE_LETTER = 'letter';

    const LOG_FILE = 'pdf.log';

    const XML_PDF_COMMAND_PATH = 'staempfli/pdf/command';

    /**
     * @var \mikehaertl\wkhtmlto\Pdf
     */
    protected $pdf;

    /**
     * Cache is disabled by default
     * @var int
     */
    protected $cache_lifetime = 0;

    protected function _construct()
    {
        Mage::dispatchEvent('composer_autoload', array('web2print' => $this));
        $this->pdf = new \mikehaertl\wkhtmlto\Pdf();
        if ($command = Mage::getStoreConfig(self::XML_PDF_COMMAND_PATH)) {
            $this->setCommandOptions(array('command' => $command));
        }
        $this->setTmpDir(Mage::getBaseDir('var') . '/tmp');
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setCommandOptions($options = array())
    {
        if (is_array($options)) {
            $this->pdf->commandOptions = $options;
        }
        return $this;
    }

    /**
     * @param $dir
     * @throws Exception
     */
    public function setTmpDir($dir)
    {
        $io = new Varien_Io_File();
        if ($io->checkAndCreateFolder($dir)) {
            $this->pdf->tmpDir = $dir;
        }
    }

    /**
     * Set the cache lifetime in seconds
     * setting it to 0 will disable the caching
     *
     * @param int $time
     * @return $this
     */
    public function setCacheLifetime($time)
    {
        if (is_int($time)) {
            $this->cache_lifetime = $time;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getCacheLifetime()
    {
        return $this->cache_lifetime;
    }

    /**
     * @return bool
     */
    public function isCacheEnabled()
    {
        if ($this->cache_lifetime > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param $filename
     * @return bool
     */
    public function getIsFileCached($filename)
    {
        // @codingStandardsIgnoreStart
        if (file_exists($this->pdf->tmpDir . DS . $filename)) {
            return !(filemtime($this->pdf->tmpDir . DS . $filename) + $this->getCacheLifetime()) < time();
        }
        return false;
        // @codingStandardsIgnoreEnd
    }

    /**
     * @param $message
     * @param int $level
     */
    public function log($message, $level = Zend_Log::ERR)
    {
        Mage::log($message, $level, self::LOG_FILE);
    }

    /**
     * @param $block
     * @return mixed
     */
    public function createBlock($block)
    {
        /**
         * @var $layout Mage_Core_Model_Layout
         */
        $layout = Mage::getModel('core/layout');
        return $layout->createBlock($block);
    }
}
