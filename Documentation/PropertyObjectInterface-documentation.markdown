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
//or 
$epcs = $property->epcs;
{% endhighlight %}

##Summary

* [__construct()](#__construct)
* [getFeatures()](#getFeatures)
* [getImages()](#getImages)
* [getEpcEntries()](#getEpcEntries)
* [jsonSerialize()](#jsonSerialize)
* [toArray()](#toArray)
* [getStatusId()](#getStatusId)
* [getPriceQualifier()](#getPriceQualifier)
* [getPublishedFlag()](#getPublishedFlag)
* [getLetTypeId()](#getLetTypeId)
* [getLetFurnId()](#getLetFurnId)
* [getLetRentFrequency()](#getLetRentFrequency)
* [getTenureTypeId()](#getTenureTypeId)
* [getTransTypeId()](#getTransTypeId)

__file__ : Interfaces/PropertyObjectInterface.php

__package__ : \mattcannon\Rightmove\Interfaces


##Methods


###__construct()

{% highlight php startinline %} __construct(array $attributes) {% endhighlight %}

Create a new PropertyObject

###Parameters

{% highlight php startinline %} array	$attributes	= array(){% endhighlight%}


###getFeatures()

{% highlight php startinline %} getFeatures() : \Illuminate\Support\Collection {% endhighlight %}

get all of the non-blank features as a collection.

####Returns

{% highlight php startinline%}\Illuminate\Support\Collection {% endhighlight %}

###getImages()

{% highlight php startinline %} getImages() : \Illuminate\Support\Collection {% endhighlight %}

get all of the non-blank images as a collection.

####Returns

{% highlight php startinline%}\Illuminate\Support\Collection {% endhighlight %}

###getEpcEntries()

{% highlight php startinline %} getEpcEntries() : \Illuminate\Support\Collection {% endhighlight %}

Get all non-empty epc properties as a collection

####Returns

{% highlight php startinline%}\Illuminate\Support\Collection {% endhighlight %}

###jsonSerialize()

{% highlight php startinline %} jsonSerialize() : mixed {% endhighlight %}

(PHP 5 >= 5.4.0) Specify data which should be serialized to JSON

####Returns

{% highlight php startinline%}mixed — {% endhighlight %}
data which can be serialized by __json_encode__, which is a value of any type other than a resource.

see also
[http://php.net/manual/en/jsonserializable.jsonserialize.php](http://php.net/manual/en/jsonserializable.jsonserialize.php)

###toArray()

{% highlight php startinline %} toArray() : array {% endhighlight %}

returns array of property details, images, epcs, and features

####Returns

{% highlight php startinline%}array {% endhighlight %}

###getStatusId()

{% highlight php startinline %} getStatusId() : string {% endhighlight %}

returns current property status as a string

####Returns

{% highlight php startinline%}string {% endhighlight %}

###getPriceQualifier()

{% highlight php startinline %} getPriceQualifier() : string {% endhighlight %}

returns current price qualifier

####Returns

{% highlight php startinline%}string {% endhighlight %}


###getPublishedFlag()

{% highlight php startinline %} getPublishedFlag() : string {% endhighlight %}

returns current published status

####Returns

{% highlight php startinline%}string {% endhighlight %}


###getLetTypeId()

{% highlight php startinline %} getLetTypeId() : string {% endhighlight %}

returns let type

####Returns

{% highlight php startinline%}string {% endhighlight %}


###getLetFurnId()

{% highlight php startinline %} getLetFurnId() : string {% endhighlight %}

returns property furnishing type

####Returns

{% highlight php startinline%}string {% endhighlight %}

###getLetRentFrequency()

{% highlight php startinline %} getLetRentFrequency() : string {% endhighlight %}

returns frequency of let.

####Returns

{% highlight php startinline%}string {% endhighlight %}

###getTenureTypeId()

{% highlight php startinline %} getTenureTypeId() : string {% endhighlight %}

returns tenure type

####Returns

{% highlight php startinline%}string {% endhighlight %}

###getTransTypeId()

{% highlight php startinline %} getTransTypeId() : string {% endhighlight %}

returns property type (Resale / Lettings)

####Returns

{% highlight php startinline%}string {% endhighlight %}