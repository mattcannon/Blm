---
layout: default
title: Rightmove BLM parser for PHP
---
#mattcannon\\rightmove
Rightmove BLM parser for PHP

##Current Status
* Master: [![Build Status](https://travis-ci.org/mattcannon/rightmove.svg?branch=master)](https://travis-ci.org/mattcannon/rightmove)
* Develop: [![Build Status](https://travis-ci.org/mattcannon/rightmove.svg?branch=develop)](https://travis-ci.org/mattcannon/rightmove)

##Requirements
* PHP 5.4+

##Installation
###Composer
To install this package using composer, run:
{% highlight bash %}
composer.phar require mattcannon/Rightmove:0.1.*
{% endhighlight %}

__This package is not yet available on [packagist.org](packagist.org), once considered stable, it will be added.__
##Usage
To use the rightmove parser, create a new instance of the Parser class, then either set the path of the rightmove file, or set the blm content to be parsed.
you can then call `parseBlm()` on the instance - it will return a Collection of property objects.
{% highlight php %} 
<?php
use mattcannon\Rightmove\Parser;
$blmParser = new Parser();
$blmParser->setBlmFile('/path/to/blm/file');
// or
$blmParser->setBlmContents('**blm contents here**');
$collection = $blmParser->parseBlm();
{% endhighlight %}

You can access the property attributes like so:

{% highlight php %} 
<?php
$property = $collection->first();
echo $property->displayAddress;
echo $property->price;
{% endhighlight %}
