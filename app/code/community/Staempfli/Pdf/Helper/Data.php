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
    protected $files = array();

    /**
     * @var Varien_Io_File
     */
    protected $io;

    protected $media;

    public function __construct()
    {
        $this->io = new Varien_Io_File();
    }

    public function __destruct()
    {
        foreach ($this->files as $file) {
            $this->io->rm($file);
        }
    }

    /**
     * @param $file
     */
    public function addFile($file)
    {
        $this->files[] = $file;
    }

    /**
     * @param $file
     * @param null|int $minWidth
     * @param bool $fixed
     * @return array
     */
    public function getSvgDimensions($file, $minWidth = null, $fixed = true)
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
            if ($fixed) {
                $ratio = $width / $minWidth;
                $width = $width / $ratio;
                $height = $height / $ratio;
            } else {
                $width = $width + ($width * $minWidth);
                $height = $height + ($height * $minWidth);
            }
        }

        return array(
            'width' => round($width),
            'height' => round($height),
            'source' => $source
        );
    }

    /**
     * @param $file
     * @param bool $save
     * @return string
     * @throws Exception
     */
    public function renderSvgImage($file, $save = false)
    {
        $dimensions = $this->getSvgDimensions($file);
        $width = $dimensions['width'];
        $height = $dimensions['height'];

        if ($dimensions) {
            if (isset($dimensions['source']) && $dimensions['source'] === 'viewBox') {
                $data = $this->io->read($file);
                $data = str_replace('viewBox', 'width="'.$width.'" height="'.$height.'" viewBox', $data);

                // check for bae64 images
                $pattern = "~data:image/[a-zA-Z]*;base64,[a-zA-Z0-9+/=\s]*~";
                preg_match($pattern, $data, $matches);

                if (count($matches) > 0) {
                    foreach ($matches as $match) {
                        $parts = explode(',', $match);
                        $type = str_replace(array('data:image/', ';base64'), '', $parts[0]);
                        $imageData = $parts[1];

                        $file = $this->createImageFromBase64($imageData, $type);
                        $data = str_replace($match, $file, $data);
                        $save = true;
                    }
                }
                if ($save) {
                    $tmp = Mage::getBaseDir('var') . DS . 'tmp';
                    $this->io->checkAndCreateFolder($tmp);
                    $file = Mage::getBaseDir('var') . DS . 'tmp' . DS .sha1($data) . '.svg';
                    $this->io->write($file, $data);
                    $this->addFile($file);
                    return $file;
                }
                return 'data:image/svg+xml;base64,' . base64_encode($data);
            }
        }
        return '';
    }


    /**
     * @param $data
     * @param string $type
     * @return string
     * @throws Exception
     */
    public function createImageFromBase64($data, $type = 'jpeg')
    {
        $tmp = Mage::getBaseDir('var') . DS . 'tmp';
        $this->io->checkAndCreateFolder($tmp);
        $file = $tmp . DS . sha1($data). '.' . $type;

        $res = imagecreatefromstring(base64_decode($data));

        switch ($type) {
            case 'jpeg':
                imagejpeg($res, $file);
                break;
            case 'png':
                imagepng($res, $file);
                break;
            case 'gif':
                imagegif($res, $file);
                break;
            default:
                throw new Exception('Image type [' . $type . '] not supported!');
                break;
        }

        imagedestroy($res);
        $this->addFile($file);
        return $file;
    }
}
