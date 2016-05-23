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
class Staempfli_Pdf_Model_Pdf extends Staempfli_Pdf_Model_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->setOrientation(parent::ORIENTATION_PORTRAIT);
        $this->setPageSize(parent::SIZE_A4);
    }

    /**
     * @param string $orientation
     * @return $this
     */
    public function setOrientation($orientation = parent::ORIENTATION_PORTRAIT)
    {
        if (is_string($orientation)
            && in_array($orientation, array(parent::ORIENTATION_LANDSCAPE, parent::ORIENTATION_PORTRAIT))
        ) {
            $this->pdf->setOptions(array('orientation' => $orientation));
        }
        return $this;
    }

    /**
     * @param $pageSize
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        if (is_string($pageSize) && in_array($pageSize, array(parent::SIZE_A4, parent::SIZE_LETTER))) {
            $this->pdf->setOptions(array('page-size' => $pageSize));
        }
        return $this;
    }

    /**
     * @param mixed $header
     * @return $this
     */
    public function addHeader($header)
    {
        if (is_string($header)) {
            $this->pdf->setOptions(array('header-html' => $header));
        }
        return $this;
    }

    /**
     * @param mixed $footer
     * @return $this
     */
    public function addFooter($footer)
    {
        if (is_string($footer)) {
            $this->pdf->setOptions(array('footer-html' => $footer));
        }
        return $this;
    }

    /**
     * @param $toc
     * @return $this
     */
    public function addToc($toc)
    {
        $this->pdf->addToc($toc);
        return $this;
    }

    /**
     * @param $cover
     * @return $this
     */
    public function addCover($cover)
    {
        $this->pdf->addCover($cover);
        return $this;
    }

    /**
     * @param mixed $page
     * @return $this
     */
    public function addPage($page)
    {
        $this->pdf->addPage($page);
        return $this;
    }


    /**
     * @param null $filename
     */
    public function savePdf($filename = null)
    {
        $this->_storePdf($filename);
    }

    /**
     * @param null $filename
     * @param bool $inline
     */
    public function downloadPdf($filename = null, $inline = false)
    {
        $this->_storePdf($filename, true, $inline);
    }


    private function _storePdf($filename = '', $download = false, $inline = false)
    {
        $io = new Varien_Io_File();
        $tmpDir = $this->pdf->tmpDir;

        if ($this->isCacheEnabled()
            && $io->fileExists($tmpDir . DS . $filename)
            && $download === true) {
            // As the $io->ls() implementation from Magento Core
            // will list all files it can have a huge performance impact.
            // So we use a custom check and ignore the Magento coding standard
            // for this part.

            // @codingStandardsIgnoreStart
            if ($this->getIsFileCached($filename)) {
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Content-Type: application/pdf');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: '.filesize($tmpDir . DS . $filename));

                if ($filename!==null || $inline) {
                    $disposition = $inline ? 'inline' : 'attachment';
                    header("Content-Disposition: $disposition; filename=\"$filename\"");
                }

                readfile($tmpDir . DS . $filename);
                return true;
            }
            // @codingStandardsIgnoreEnd
        }

        if ($download) {
            $result = $this->pdf->send($filename, $inline);
        } else {
            $result = $this->pdf->saveAs($filename);
        }

        // Save temporary file
        $tmpFile = $this->pdf->getPdfFilename();
        $io->mv($tmpFile, $tmpDir . DS . $filename);

        if (!$result) {
            $this->log($this->pdf->getCommand());
            $this->log($this->pdf->getError());
            return false;
        }
        return true;
    }
}
