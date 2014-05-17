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

namespace mattcannon\Rightmove;

use Illuminate\Support\Collection;
use mattcannon\Rightmove\Interfaces\PropertyObjectInterface;

/**
 * Class PropertyObject
 *
 * Please see documentation for [mattcannon\Rightmove\Interfaces\PropertyObjectInterface](mattcannon.Rightmove.interfaces.PropertyObjectInterface.html) to see how
 * this should be used. Any Methods not listed in PropertyObjectInterface, or JsonSerializable
 * are not considered public API, and may change without notice.
 *
 * @package mattcannon\Rightmove
 * @property \Illuminate\Support\Collection $features
 * @property \Illuminate\Support\Collection $images
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
     * Create a new PropertyObject
     * @param $attributes array
     * @api
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Magic method, to allow getting values of pseudo-properties from different sources.
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        switch ($key) {
            case 'features':
                return $this->getFeatures();
            case 'images':
                return $this->getImages();
            case 'epcs':
                return $this->getEpcEntries();
            default:
                return $this->attributes[$key];
        }
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
            $imageKeys = array_filter(array_keys($this->attributes), function (&$element) {
                    $success = (strpos($element, 'mediaImage')===0); //test to confirm it is an image
                    if($success) { //test to confirm it is not an epc.
                        preg_match('!\d+!', $element, $matches);
                        $success = $success && ($matches[0] < 60);
                    }
                    return $success;
            });
            $this->internal['images'] = $imageKeys;
        }
        //returns a collection of all non-blank image properties as a Collection.
        return  Collection::make(array_filter(
            array_intersect_key(
                $this->attributes,
                array_flip($this->internal['images'])
            )
        ));
    }

    /**
     * Get all non-empty epc properties as a collection
     * @return Collection
     * @api
     */
    public function getEpcEntries(){
        //gets image keys if already calculated, otherwise calculates them.

        if (!isset($this->internal['epcs'])) {
            $imageKeys = array_filter(array_keys($this->attributes), function (&$element) {
                    return (strpos($element, 'mediaImage')===0);
                });
            $this->internal['epcs'] = $imageKeys;
        }
        $keyIntersects = array_intersect_key(
            $this->attributes,
            array_flip($this->internal['epcs'])
        );
        //returns a collection of all non-blank image properties as a Collection.
        $this->filterArrayToEpcEntries($keyIntersects);
        return  Collection::make($keyIntersects);
    }

    /**
     * filters the array down to only EPC data.
     * @param array $entries
     */
    private function filterArrayToEpcEntries(array &$entries){
        foreach($entries as $key => $value){
            if($value == false || (preg_match('/mediaImage6/',$key)+preg_match('/mediaImageText6/',$key)<1)){
                unset($entries[$key]);
            }
        }
    }

    /**
     * (PHP 5 >= 5.4.0)
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by __json_encode__,
     * which is a value of any type other than a resource.
     * @api
     */
    public function jsonSerialize()
    {
        $property = $this->attributes;
        $features = $this->features;
        $images = $this->images;
        $epcs = $this->epcs;

        return compact('property', 'features', 'images','epcs');
    }

    /**
     * returns array of property details, images, epcs, and features
     * @return array
     * @api
     */
    public function toArray(){
        return $this->jsonSerialize();
    }
}
