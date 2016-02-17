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
class Staempfli_Pdf_Model_Observer_Autoload
{
    /**
     * Include our composer autoloader
     *
     * @param Varien_Event_Observer $observer
     */
    public function listener(Varien_Event_Observer $observer)
    {
        $io = new Varien_Io_File();
        if ($io->fileExists(Mage::getBaseDir() . DS . '../vendor/autoload.php', true)) {
            require_once(Mage::getBaseDir() . DS . '../vendor/autoload.php');
        }
    }
}
