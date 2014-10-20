#MattCannon\Blm
Rightmove BLM parser for PHP - Please see (http://mattcannon.github.io/rightmove/)[http://mattcannon.github.io/rightmove/]

##Current Status
* Master:[![Build Status](https://travis-ci.org/mattcannon/Blm.svg?branch=master)](https://travis-ci.org/mattcannon/Blm)
* Develop:[![Build Status](https://travis-ci.org/mattcannon/Blm.svg?branch=develop)](https://travis-ci.org/mattcannon/Blm)

##Requirements
* PHP 5.4+

##Installation
###Composer
To install this package using composer, run:
```composer require matt-cannon/blm:0.2.*```

##Usage
 To use the parser you should create a new instance of the Parser class, then set 
 the BLM contents, or pass in a file path to a BLM.
 To access the data in the BLM, you should call ```parseBlm()```.
 
 ```php
 $adapter = new \League\Flysystem\Adapter\Local('/data/');
 $loader = new BlmFileLoader(new League\Flysystem\Filesystem($adapter),'BlmFile.blm');
 $parser = new \MattCannon\Blm\Parser(new \Psr\Log\NullLogger(),$loader);
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
files in src/MattCannon/Blm/Interfaces - if a method isn't listed 
in these files, then it may disappear or change it's behavior without notice.

