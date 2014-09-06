<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 06/09/2014
 * Time: 00:44
 */
namespace mattcannon\Rightmove\Interfaces;


/**
 * Class MediaObject
 * @package mattcannon\Rightmove
 */
interface MediaObjectInterface
{
    /**
     * @return null|string
     */
    public function getCaption();

    /**
     * @param null|string $caption
     */
    public function setCaption($caption);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @return null|string
     */
    public function getValue();

    /**
     * @param null|string $value
     */
    public function setValue($value);
}