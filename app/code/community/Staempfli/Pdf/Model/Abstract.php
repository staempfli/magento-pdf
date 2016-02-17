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
    /**
     * @var \mikehaertl\wkhtmlto\Pdf
     */
    private $pdf;

    protected function _construct()
    {
        Mage::dispatchEvent('composer_autoload', array('web2print' => $this));
        $this->pdf = new \mikehaertl\wkhtmlto\Pdf();
    }
}
