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
class Staempfli_Pdf_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param $file
     * @param null $minWidth
     * @return array
     */
    public function getSvgDimensions($file, $minWidth = null)
    {
        $width = 0;
        $height = 0;
        $source = '';

        $xml = simplexml_load_file($file);
        $attributes = $xml->attributes();


        if ($attributes->viewBox) {
            $dimensions = explode(' ', $attributes->viewBox);
            $width = $dimensions[2];
            $height = $dimensions[3];
            $source = 'viewBox';
        } else {
            if ($attributes->width && $attributes->height) {
                $width = (string) $attributes->width;
                $height = (string) $attributes->height;
                $source = 'width/height';
            }
        }

        if (!is_null($minWidth)) {
            $ratio = $width / $minWidth;
            $width = $width / $ratio;
            $height = $height / $ratio;
        }

        return array(
            'width' => round($width),
            'height' => round($height),
            'source' => $source
        );
    }

    /**
     * @param $file
     * @return string
     */
    public function renderSvgImage($file)
    {
        $io = new Varien_Io_File();
        $dimensions = $this->getSvgDimensions($file);
        $width = $dimensions['width'];
        $height = $dimensions['height'];

        if ($dimensions) {
            if (isset($dimensions['source']) && $dimensions['source'] === 'viewBox') {
                $data = $io->read($file);
                $data = str_replace('viewBox', 'width="'.$width.'" height="'.$height.'" viewBox', $data);
                return base64_encode($data);
            }
        }
        return '';
    }
}
