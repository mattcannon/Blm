---
layout: default
title: PropertyObject - Public API documentation
---
#Public API documentation
The public API for ```\mattcannon\Rightmove\PropertyObject``` is defined in the PropertyObjectInterface.

__namespace__: \mattcannon\Rightmove\Interfaces

#PropertyObjectInterface
This is a convenience object containing all of the standard rightmove fields, plus some additional convenience methods.

You can access details like this:
{% highlight php startinline %}
$agentRef = $property->agentRef;
{% endhighlight %}
To access an array of epcs:
{% highlight php startinline %}
$epcs = $property->getEpcEntries();
{% endhighlight %}

#Summary

* [__construct()](#construct)
* [getFeatures()](#getFeatures)
* [getImages()](#getImages)
* [getEpcEntries()](#getEpcEntries)
* [jsonSerialize()](#jsonSerialize)
* [toArray()](#toArray)

__file__: Interfaces/PropertyObjectInterface.php

__package__: \mattcannon\Rightmove\Interfaces

__author__: Matt Cannon

#Methods

##__construct()

{% highlight php startinline %}__construct(array $attributes){% endhighlight %}

Create a new PropertyObject

###Parameters

{% highlight php startinline %}array	$attributes	= array(){% endhighlight %}

###Tags

api	

##getFeatures()

{% highlight php startinline %}getFeatures() : \Illuminate\Support\Collection{% endhighlight %}

get all of the non-blank features as a collection.

###Returns

{% highlight php startinline %}\Illuminate\Support\Collection{% endhighlight %}

###Tags

api	

##getImages()

{% highlight php startinline %}getImages() : \Illuminate\Support\Collection{% endhighlight %}

get all of the non-blank images as a collection.

###Returns

{% highlight php startinline %}\Illuminate\Support\Collection{% endhighlight %}

###Tags

api	

##getEpcEntries()

{% highlight php startinline %}getEpcEntries() : \Illuminate\Support\Collection{% endhighlight %}

Get all non-empty epc properties as a collection

###Returns

{% highlight php startinline %}\Illuminate\Support\Collection{% endhighlight %}

###Tags

api
	
##jsonSerialize()

{% highlight php startinline %}jsonSerialize() : mixed{% endhighlight %}

(PHP 5 >= 5.4.0) Specify data which should be serialized to JSON

###Returns

{% highlight php startinline %}mixed {% endhighlight %}

data which can be serialized by __json_encode__, which is a value of any type other than a resource.

see also
http://php.net/manual/en/jsonserializable.jsonserialize.php

###Tags

api	

##toArray()

{% highlight php startinline %}toArray() : array{% endhighlight %}

returns array of property details, images, epcs, and features

###Returns

{% highlight php startinline %}array{% endhighlight %}

###Tags

api
