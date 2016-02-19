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

    /**
     * @var \mikehaertl\wkhtmlto\Pdf
     */
    protected $pdf;

    protected function _construct()
    {
        Mage::dispatchEvent('composer_autoload', array('web2print' => $this));
        $this->pdf = new \mikehaertl\wkhtmlto\Pdf();
        $this->setTmpDir(Mage::getBaseDir('var') . '/tmp');
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
