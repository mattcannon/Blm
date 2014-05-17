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
    private $internal = array('features'=>null,'images'=>null,'epcs'=>null);

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
        $epcs = $this->epcs;

        return compact('property', 'features', 'images','epcs');
    }
}
