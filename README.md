Staempfli PDF
=============

Facts
-----
- version: 0.1.0
- extension key: Staempfli_Pdf
- [extension on GitHub](https://github.com/staempfli/magento-pdf)
- [direct download link](https://github.com/staempfli/magento-pdf/archive/master.zip)

Description
-----------
This Module let's you easily create PDF's based on HTML Templates or by using the default Templates and adding the content you want.

**Load the Module**

    $pdf = Mage::getModel('staempfli_pdf/pdf');

**or extend the the PDF Model in your own Model, like:**

    <?php
	class Vendor_Extension_Model_Pdf extends Staempfli_Pdf_Model_Pdf
	{
	}

**Define a Header, Footer and/or Content part.**

	$header   = $pdf->createBlock('staempfli_pdf/pdf_header');
    $footer   = $pdf->createBlock('staempfli_pdf/pdf_footer');
    $content  = $pdf->createBlock('staempfli_pdf/pdf_content');

Now you are ready to add some Content and/or add custom Stylesheets.

**Add a custom Stylesheet:**

    $content  = $pdf->addStylesheet('/skin/frontend/your_theme/default/css/pdf.css');

**Set a Title:**

    $content->setTitle('My Page Title');

**Add a Section:**

    $content->addSection('header');
    // here you can define your Content by ->addContent
    // or adding an Image with ->addImage()
    $content->endSection();

**Add Content:**

    // Plain Text
    // Output:
    // Hello World

    $content->addContent('Hello World');

    // Wrap content between an element
    // Output:
    // <h1>Hello World</h1>

    $content->addContent('Hello World', 'h1');

    // Add some attributes to an element
    // Output:
    // <h1 class="content">Hello World</h1>

    $content->addContent('Hello World', 'h1', array('class' => 'custom'));

**Add an Image:**

    $content->addImage('/skin/frontend/your_theme/default/images/my-logo.svg');

    // With image title
    $content->addImage('/skin/frontend/your_theme/default/images/my-logo.svg', 'Title');

**Now, create a Page based on your Content**

    $pdf->addPage($content->toHtml);

**Download the PDF:**

    $pdf->downloadPdf('filename.pdf');

**Save the PDF:**

    $pdf->savePdf('filename.pdf');

Requirements
------------
- PHP >= 5.4.0
- [wkhtmltopdf](http://wkhtmltopdf.org/downloads.html)

Compatibility
-------------
- Magento >= 1.7

Installation Instructions
-------------------------
1. Install the extension via Composer or copy all the files into your document root.
2. Clear the cache, logout from the admin panel and then login again.

Uninstallation
--------------
1. Remove all extension files from your Magento installation.

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/staempfli/magento-pdf/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Staempfli Webteam and all other [contributors](https://github.com/staempfli/magento-pdf/contributors)

License
-------
[GNU General Public License, version 3 (GPLv3)](http://opensource.org/licenses/gpl-3.0)

Copyright
---------
(c) 2016, Stämpfli AG