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
 To use the parser you should create a new instance of the Parser class, then set 
 the BLM contents, or pass in a file path to a BLM.
 To access the data in the BLM, you should call ```parseBlm()```.
 
 ```php
 $parser = new \mattcannon\Rightmove\Parser;
 $parser->setBlmFilePath('/path/to/blm/file');
 $data = $parser->parseBlm();
 
 foreach($data as $property){
    var_dump($property);
 }
 ```

You can access the property attributes like so:

```php
<?php
$property = $collection->first();
echo $property->displayAddress;
echo $property->price;
```

##Public API
To see a full list of all public methods, please look at the interface 
files in src/mattcannon/Rightmove/Interfaces - if a method isn't listed 
in these files, then it may disappear or change it's behavior without notice.