# lara-pdf-merger

Drop-in replacement for the original package from [deltaaskii/lara-pdf-merger](https://github.com/deltaaskii/lara-pdf-merger) that works under *PHP 7.4*


Original written by http://pdfmerger.codeplex.com/team/view

This Package was tested on **Laravel 7.0**

### Improvements 

* Code source refactoring
* Enabling the Facade use
* Adding duplex merge feature
* Seperate save operation from the merge
  
## Installation

* Require this package in your composer.json by adding those lines

```
composer require daltcore/lara-pdf-merger
```

* Run  this commend in your terminal
```bash
composer update
```
### Laravel <5.5:

After updating composer, add the ServiceProvider to the providers array in config/app.php
```php
    LynX39\LaraPdfMerger\PdfMergerServiceProvider::class,
```
You can optionally use the facade for shorter code. Add this to your facades:
```php
    'PdfMerger' => LynX39\LaraPdfMerger\Facades\PdfMerger::class,
```
## Usage

```php

use LynX39\LaraPdfMerger\Facades\PdfMerger;

//...

$pdfMerger = PDFMerger::init(); //Initialize the merger

$pdfMerger->addPDF('samplepdfs/one.pdf', '1, 3, 4');
$pdfMerger->addPDF('samplepdfs/two.pdf', '1-2');
$pdfMerger->addPDF('samplepdfs/three.pdf', 'all');

//You can optionally specify a different orientation for each PDF
$pdfMerger->addPDF('samplepdfs/one.pdf', '1, 3, 4', 'L');
$pdfMerger->addPDF('samplepdfs/two.pdf', '1-2', 'P');

$pdfMerger->merge(); //For a normal merge (No blank page added)

// OR..
$pdfMerger->duplexMerge(); //Merges your provided PDFs and adds blank pages between documents as needed to allow duplex printing

// optional parameter can be passed to the merge functions for orientation (P for protrait, L for Landscape). 
// This will be used for every PDF that doesn't have an orientation specified

$pdfMerger->save("file_path.pdf");

// OR...
$pdfMerger->save("file_name.pdf", "download");
// REPLACE 'download' WITH 'browser', 'download', 'string', or 'file' for output options

```

## Authors
* [RamonSmit](https://github.com/RamonSmit)
* [MarwenSami](https://github.com/MarwenSami)


## Credits
* **deltaaskii** [deltaaskii/lara-pdf-merger](https://github.com/deltaaskii/lara-pdf-merger)
* **DALTCORE** [DALTCORE/lara-pdf-merger](https://github.com/DALTCORE/lara-pdf-merger)
