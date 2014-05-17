---
layout: default
title: Parser - Public API documentation
---
#Public API documentation
The public API for \mattcannon\Rightmove\Parser is defined in the ParserInterface

__namespace__: \mattcannon\Rightmove\interfaces
#ParserInterface
To use the parser you should create a new instance of the Parser class, then set the BLM contents, or pass in a file path to a BLM.
To access the data in the BLM, you should call ```parseBlm()```.

{% highlight php startinline %}
$parser = new \mattcannon\Rightmove\Parser;
$parser->setBlmFilePath('/path/to/blm/file');

$data = $parser->parseBlm();

foreach($data as $property){
    var_dump($property);
}
{% endhighlight %}
##Summary

* [parseBlm()](#parseblm)
* [setBlmContents()](#setBlmContents)
* [setBlmFilePath()](#setBlmFilePath)
* [getBlmContents()](#getBlmContents)
* [getBlmFilePath()](#getBlmFilePath)

__file__: interfaces/ParserInterface.php

__package__: \mattcannon\Rightmove

#Methods

##parseBlm()

{% highlight php startinline %}parseBlm() : \Illuminate\Support\Collection{% endhighlight %}

Parses the BLM and returns a collection of PropertyObjects

###Throws
{% highlight php startinline %}\mattcannon\Rightmove\Exceptions\InvalidBLMException{% endhighlight %}

###Returns

{% highlight php startinline %}\Illuminate\Support\Collection{% endhighlight %}

##setBlmContents()

{% highlight php startinline %}setBlmContents(string $blmContentString){% endhighlight %}

Sets the BLM data to parse - if called, will set blmFilePath to null.

###Parameters

{% highlight php startinline %}string	$blmContentString{% endhighlight %}

##setBlmFilePath()

{% highlight php startinline %}setBlmFilePath(string $filePath){% endhighlight %}

Sets the path of the BLM file to parse - if called, will set blmContents to null.

###Parameters

{% highlight php startinline %}string	$filePath{% endhighlight %}

##getBlmContents()

{% highlight php startinline %}getBlmContents() : string|null{% endhighlight %}

returns the BLM data as a string to be parsed.

###Returns
{% highlight php startinline %}string|null{% endhighlight %}

##getBlmFilePath()

{% highlight php startinline %}getBlmFilePath() : string|null{% endhighlight %}

returns the file path to the BLM file as a string.

###Returns
{% highlight php startinline %}string|null{% endhighlight %}

