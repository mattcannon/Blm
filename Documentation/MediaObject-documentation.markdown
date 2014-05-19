---
layout: default
title: MediaObject - Public API documentation
---
#Public API documentation

__namespace__: \mattcannon\Rightmove

#MediaObject

##Summary
This class is used to group images, EPCs, HIPs and other media/documents with their captions.

* [__construct()](#__construct)
* [$value](#value)
* [$caption](#caption)
* [$type](#type)

__file__ : MediaObject.php

__package__ : \mattcannon\Rightmove

##Properties

###$value

{% highlight php startinline %}$value : null|string{% endhighlight %}

stores the value of the main field

####Type

{% highlight php startinline %}null|string{% endhighlight %}


###$caption

{% highlight php startinline %}$caption : null|string{% endhighlight %}

stores an associated caption, if available

####Type

{% highlight php startinline %}null|string{% endhighlight %}


###$type

{% highlight php startinline %}$type : string{% endhighlight %}

stores Media object type (Image / Document )

####Type

{% highlight php startinline %}string{% endhighlight %}

##Methods

###__construct()

{% highlight php startinline %}__construct(null|string $value, null|string $caption, string $type){% endhighlight %}

Creates an instance of MediaObject

####Parameters

{% highlight php startinline %}null|string	$value	{% endhighlight %}
{% highlight php startinline %}null|string	$caption	{% endhighlight %}
{% highlight php startinline %}string	$type	{% endhighlight %}

