---
layout: default
title: Public API documentation
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

```parseBlm() : \Illuminate\Support\Collection```

Parses the BLM and returns a collection of PropertyObjects

###Throws
```\mattcannon\Rightmove\Exceptions\InvalidBLMException```

###Returns

```\Illuminate\Support\Collection```

##setBlmContents()

```setBlmContents(string $blmContentString)```
Sets the BLM data to parse - if called, will set blmFilePath to null.

###Parameters

```string	$blmContentString```	

##setBlmFilePath()

```setBlmFilePath(string $filePath)```

Sets the path of the BLM file to parse - if called, will set blmContents to null.

###Parameters

```string	$filePath```	

##getBlmContents()

```getBlmContents() : string|null```

returns the BLM data as a string to be parsed.

###Returns
```string|null```

##getBlmFilePath()

```getBlmFilePath() : string|null```

returns the file path to the BLM file as a string.

###Returns
```string|null```

