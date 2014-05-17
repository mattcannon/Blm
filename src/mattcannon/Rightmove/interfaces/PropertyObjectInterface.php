<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 17/05/2014
 * Time: 20:59
 */
namespace mattcannon\Rightmove\Interfaces;

use Illuminate\Support\Collection;


/**
 * Interface PropertyObjectInterface
 *
 * This is a convenience object containing all of the standard rightmove fields, plus some additional convenience methods.
 *
 * You can access details like this:
 * ```php
 * $agentRef = $property->agentRef;
 * ```
 *
 * To access an array of epcs:
 * ```php
 * $epcs = $property->getEpcEntries();
 * ```
 *
 * @package mattcannon\Rightmove\Interfaces
 * @property \Illuminate\Support\Collection $features
 * @property \Illuminate\Support\Collection $images
 * @author Matt Cannon
 */
interface PropertyObjectInterface extends \JsonSerializable
{
    /**
     * Create a new PropertyObject
     * @param $attributes array
     * @api
     */
    public function __construct(array $attributes = []);
    /**
     * get all of the non-blank features as a collection.
     * @return \Illuminate\Support\Collection
     * @api
     */
    public function getFeatures();

    /**
     * get all of the non-blank images as a collection.
     * @return \Illuminate\Support\Collection
     * @api
     */
    public function getImages();

    /**
     * Get all non-empty epc properties as a collection
     * @return Collection
     * @api
     */
    public function getEpcEntries();

    /**
     * (PHP 5 >= 5.4.0)
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by __json_encode__,
     * which is a value of any type other than a resource.
     * @api
     */
    public function jsonSerialize();

    /**
     * returns array of property details, images, epcs, and features
     * @return array
     * @api
     */
    public function toArray();
}