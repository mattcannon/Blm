#mattcannon\rightmove
Rightmove BLM parser for PHP

##Current Status
* Master:[![Build Status](https://travis-ci.org/mattcannon/rightmove.svg?branch=master)](https://travis-ci.org/mattcannon/rightmove)
* Develop:[![Build Status](https://travis-ci.org/mattcannon/rightmove.svg?branch=develop)](https://travis-ci.org/mattcannon/rightmove)

##Requirements
* PHP 5.4+

##Installation
###Composer
To install this package using composer, run:
```composer require mattcannon/Rightmove:0.1.*```

##Usage
To use the rightmove parser, create a new instance of the Parser class, passing in the path of the rightmove file.
then call `parseFile()` on the instance - it will return a Collection of property objects.
```php 
<?php
use mattcannon\Rightmove\Parser;
$blmParser = new Parser('/path/to/rightmove.blm');
$collection = $blmParser->parseFile();
```

You can access the property attributes like so:

```php
<?php
$property = $collection->first();
echo $property->displayAddress;
echo $property->price;
```