<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2014 Matt Cannon
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH
 * THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace MattCannon\Blm;

use Illuminate\Support\Collection;
use MattCannon\Blm\Interfaces\PropertyObjectInterface;

/**
 * Class PropertyObject
 *
 * Please see documentation for [MattCannon\Blm\Interfaces\PropertyObjectInterface](mattcannon.Rightmove.interfaces.PropertyObjectInterface.html) to see how
 * this should be used. Any Methods not listed in PropertyObjectInterface, or JsonSerializable
 * are not considered public API, and may change without notice.
 *
 * @package MattCannon\Blm
 * @property \Illuminate\Support\Collection $features
 * @property \Illuminate\Support\Collection $images
 * @property \Illuminate\Support\Collection $epcs
 * @property \Illuminate\Support\Collection $hips
 * @property string $statusId
 * @property string $priceQualifier
 * @property string $publishedFlag
 * @property string $letTypeId
 * @property string $letFurnId
 * @property string $letRentFrequency
 * @property string $tenureTypeId
 * @property string $transTypeId
 * @author Matt Cannon
 */
class PropertyObject implements PropertyObjectInterface
{
    /**
     * Contains the property attributes
     * @var array
     */
    private $attributes;
    /**
     * Internal cache of feature/image keys
     * @var array
     */
    private $internal = array('features'=>null,'images'=>null,'epcs'=>null);
    /**
     * Internal cache of public getters available for use by magic get method
     * @var array
     */
    private $internalMethods = [];
    /**
     * used in magic get method to get a list of publicly available methods.
     * @var \ReflectionClass
     */
    private $reflector;
    /**
     * Create a new PropertyObject
     * @param $attributes array
     * @api
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->reflector = new \ReflectionClass($this);
    }

    /**
     * Magic method, to allow getting values of pseudo-properties from different sources.
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        $methodName = 'get'.ucfirst($key);
        if (in_array($key,['epcs','hips'])) {
            $methodName = substr($methodName,0,-1);
            $methodName .='Entries';
        }
        if (sizeof($this->internalMethods) == 0) {
            $methods = $this->reflector->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $k => $v) {
                $methods[$k] = $v->name;
            }
            $methods = array_filter($methods,function (&$element) {
                    return substr($element,0,2) !== '__';
                });
            $this->internalMethods = $methods;
        }
        if (in_array($methodName,$this->internalMethods)) {
            return $this->{$methodName}();
        }

        return $this->attributes[$key];
    }

    /**
     * Magic method, to allow setting values of pseudo-properties from different sources.
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * get all of the non-blank features as a collection.
     * @return \Illuminate\Support\Collection
     * @api
     */
    public function getFeatures()
    {
        //gets feature keys if already calculated, otherwise calculates them.
        if (!isset($this->internal['features'])) {
            $featureKeys = array_filter(array_keys($this->attributes), function (&$element) {
                    return (strpos($element, 'feature')===0);
            });
            $this->internal['features'] = $featureKeys;
        }
        //returns a collection of all non-blank feature properties as a Collection.
        return  Collection::make(array_filter(
            array_intersect_key(
                $this->attributes,
                array_flip($this->internal['features'])
            )
        ));
    }

    /**
     * get all of the non-blank images as a collection.
     * @return \Illuminate\Support\Collection
     * @api
     */
    public function getImages()
    {
        //gets image keys if already calculated, otherwise calculates them.

        if (!isset($this->internal['images'])) {
            $imageKeys = array_filter(array_keys(array_filter($this->attributes)), function (&$element) {
                    $success = (preg_match('/mediaImage[0-9][0-9]/',$element)); //test to confirm it is an image
                    if ($success) { //test to confirm it is not an epc.
                        preg_match('!\d+!', $element, $matches);
                        $success = $success && ($matches[0] < 60);
                    }

                    return $success;
            });
            $this->internal['images'] = $imageKeys;
        }
        $imageArray = array_intersect_key(
            $this->attributes,
            array_flip($this->internal['images'])
        );
        foreach ($imageArray as $k => $v) {
            $imageCaptionKey = str_replace('mediaImage','mediaImageText',$k);
            $imageCaption = '';
            if (array_key_exists($imageCaptionKey,$this->attributes)) {
                $imageCaption = $this->attributes[$imageCaptionKey];
            }
            $imageArray[$k] = new MediaObject($v, $imageCaption);
        }
        //returns a collection of all non-blank image properties as a Collection.
        return  Collection::make($imageArray);
    }

    /**
     * Get all non-empty epc properties as a collection
     * @return Collection
     * @api
     */
    public function getEpcEntries()
    {
        //gets image keys if already calculated, otherwise calculates them.

        if (!isset($this->internal['epcs'])) {
            $imageKeys = array_filter(array_keys($this->attributes), function (&$element) {
                    return (preg_match('/mediaImage6[0-1]/',$element) || preg_match('/mediaDocument[5-9][0-9]/',$element));
                });
            $this->internal['epcs'] = $imageKeys;
        }
        $keyIntersects = array_intersect_key(
            $this->attributes,
            array_flip($this->internal['epcs'])
        );
        foreach ($keyIntersects as $k => $v) {
            $captionKey = str_replace('mediaImage','mediaImageText',$k);
            $captionKey = str_replace('mediaDocument','mediaDocumentText',$captionKey);
            $type = (strpos($k,'mediaDocument') ===false) ? 'Image' : 'Document';
            if ($this->{$captionKey} == 'EPC') {
                $keyIntersects[$k] = new MediaObject($v, $this->{$captionKey},$type);
            } else {
                unset($keyIntersects[$k]);
            }
        }

        return  Collection::make($keyIntersects);
    }
    
    /**
     * Get all non-empty floorplan properties as a collection
     * @return Collection
     * @api
     */
    public function getFloorplanEntries()
    {
        //gets image keys if already calculated, otherwise calculates them.

        if (!isset($this->internal['floorplans'])) {
            $imageKeys = array_filter(array_keys($this->attributes), function (&$element) {
                    return (preg_match('/mediaFloorPlan0[0-1]/',$element));
                });
            $this->internal['floorplans'] = $imageKeys;
        }
        $keyIntersects = array_intersect_key(
            $this->attributes,
            array_flip($this->internal['floorplans'])
        );
        foreach ($keyIntersects as $k => $v) {
            $captionKey = str_replace('mediaFloorPlan','mediaFloorPlanText',$k);
            $type = 'Image';
            if ($this->{$captionKey} == 'Floorplan') {
                $keyIntersects[$k] = new MediaObject($v, $this->{$captionKey},$type);
            } else {
                unset($keyIntersects[$k]);
            }
        }

        return  Collection::make($keyIntersects);
    }

    /**
     * Get all non-empty hip properties as a collection
     * @return Collection
     * @api
     */
    public function getHipEntries()
    {
        //gets image keys if already calculated, otherwise calculates them.
        if (!isset($this->internal['epcs'])) {
            $imageKeys = array_filter(array_keys($this->attributes),
                /**
                 * filters array down to only contain media images, and media documents.
                 * @param $element
                 */
                function (&$element) {
                    return (preg_match('/mediaImage6[0-1]/',$element) || preg_match('/mediaDocument[5-9][0-9]/',$element));
                });
            $this->internal['epcs'] = $imageKeys;
        }
        $keyIntersects = array_intersect_key(
            $this->attributes,
            array_flip($this->internal['epcs'])
        );
        foreach ($keyIntersects as $k => $v) {
            $captionKey = str_replace('mediaImage','mediaImageText',$k);
            $captionKey = str_replace('mediaDocument','mediaDocumentText',$captionKey);
            if ($this->{$captionKey} == 'HIP') {
                $keyIntersects[$k] = new MediaObject($v, $this->{$captionKey});
            } else {
                unset($keyIntersects[$k]);
            }
        }

        return  Collection::make($keyIntersects);
    }

    /**
     * (PHP 5 >= 5.4.0)
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by __json_encode__,
     *               which is a value of any type other than a resource.
     * @api
     */
    public function jsonSerialize()
    {
        $property = $this->attributes;
        $features = $this->features;
        $images = $this->images;
        $epcs = $this->epcs;

        return compact('property', 'features', 'images', 'epcs');
    }

    /**
     * returns array of property details, images, epcs, and features
     * @return array
     * @api
     */
    public function toArray()
    {
        return $this->jsonSerialize();
    }

    /**
     * returns current property status as a string
     * @return string
     * @api
     */
    public function getStatusId()
    {
        $map = [
            0 => 'Available',
            1 => 'SSTC',
            2 => 'SSTCM',
            3 => 'Under Offer',
            4 => 'Reserved',
            5 => 'Let Agreed'
        ];

        return $map[$this->attributes['statusId']];
    }

    /**
     * returns current price qualifier
     * @return string
     * @api
     */
    public function getPriceQualifier()
    {
        $map = [
            0 => "Default",
            1 => "POA",
            2 => "Guide Price",
            3 => "Fixed Price",
            4 => "Offers in Excess of",
            5 => "OIRO",
            6 => "Sale by Tender",
            7 => "From",
            9 => "Shared Ownership",
            10 => "Offers Over",
            11 => "Part Buy Part Rent",
            12 => "Shared Equity"
        ];

        return $map[$this->attributes['priceQualifier']];
    }

    /**
     * returns current published status
     * @return string
     * @api
     */
    public function getPublishedFlag()
    {
        $map = [
            0 => 'Hidden/invisible',
            1 => 'Visible'
        ];

        return $map[$this->attributes['publishedFlag']];
    }

    /**
     * returns let type
     * @return string
     * @api
     */
    public function getLetTypeId()
    {
        $map = [
            0 => 'Not Specified',
            1 => 'Long Term',
            2 => 'Short Term',
            3 => 'Student',
            4 => 'Commercial'
        ];

        return $map[$this->attributes['letTypeId']];
    }

    /**
     * returns property furnishing type
     * @return string
     * @api
     */
    public function getLetFurnId()
    {
        $map = [
            0 => "Furnished",
            1 => "Part Furnished",
            2 => "Unfurnished",
            3 => "Not Specified",
            4 => "Furnished/Un Furnished"
        ];

        return $map[$this->attributes['letFurnId']];
    }

    /**
     * returns frequency of let.
     * @return string
     * @api
     */
    public function getLetRentFrequency()
    {
        if (!array_key_exists('letRentFrequency',$this->attributes)) {
            return 'Price per Month';
        } elseif (strlen($this->attributes['letRentFrequency']) == 0) {
            return 'Price per Month';
        }
        $map = [
            0 => "Weekly" ,
            1 => "Monthly",
            2 => "Quarterly",
            3 => "Annual",
            5 => "Per person per week"
        ];

        return $map[$this->attributes['letRentFrequency']];
    }

    /**
     * returns tenure type
     * @return string
     * @api
     */
    public function getTenureTypeId()
    {
        $map = [
            1 => "Freehold",
            2 => "Leasehold",
            3 => "Feudal",
            4 => "Commonhold",
            5 => "Share of Freehold"
        ];

        return $map[$this->attributes['tenureTypeId']];
    }

    /**
     * returns property type (Resale | Lettings)
     * @return string
     * @api
     */
    public function getTransTypeId()
    {
        $map = [
            1 => "Resale",
            2 => "Lettings"
        ];

        return $map[$this->attributes['transTypeId']];
    }

}
