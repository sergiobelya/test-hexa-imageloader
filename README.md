# test-hexa-imageloader

## Installing
```
composer require sergiobelya/test-hexa-imageloader:">=0.9"
```

## Examples of use
```php
use sergiobelya\TestHexaImageloader\UrlImportFromFile;
use sergiobelya\TestHexaImageloader\ImageLoader;

$folder = __DIR__ . '/loaded/';
$image_loader = new ImageLoader($folder);

$image_loader->addUrl('https://ssl.gstatic.com/gb/images/v1_76783e20.png');
$image_loader->addUrl('http://easy-code.ru/wordpress/wp-content/uploads/2014/09/wplogo-50x50.png');
$image_loader->loadAllImages();

$import_from_file = new UrlImportFromFile();
$img_urls = $import_from_file->importToArray(__DIR__ . '/imglist.txt');

$image_loader->setUrlsArray($img_urls);
$image_loader->loadAllImages();
```
