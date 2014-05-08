<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 07/05/2014
 * Time: 21:00
 */

namespace mattcannon\Rightmove;

use Illuminate\Support\Collection;

/**
 * Class PropertyObject
 * @property \Illuminate\Support\Collection features
 * @property \Illuminate\Support\Collection images
 * @package mattcannon\Rightmove
 */
class PropertyObject implements \JsonSerializable
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
    private $internal = array('features'=>null,'images'=>null);

    /**
     * Create a new PropertyObject
     * @param array $attributes
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
     */
    public function getImages()
    {
        //gets image keys if already calculated, otherwise calculates them.

        if (!isset($this->internal['images'])) {
            $imageKeys = array_filter(array_keys($this->attributes), function (&$element) {
                    return (strpos($element, 'mediaImage')===0);
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
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        $property = $this->attributes;
        $features = $this->features;
        $images = $this->images;

        return compact('property', 'features', 'images');
    }
}
