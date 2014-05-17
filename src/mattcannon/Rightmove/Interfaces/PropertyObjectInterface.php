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
