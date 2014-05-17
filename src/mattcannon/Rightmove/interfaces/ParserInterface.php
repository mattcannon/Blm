<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 17/05/2014
 * Time: 12:20
 */
namespace mattcannon\Rightmove\interfaces;

use mattcannon\Rightmove\Exceptions;
use Illuminate\Support\Collection;


/**
 * Class Parser
 * @package mattcannon\Rightmove
 */
interface ParserInterface
{


    /**
     * Parses the BLM and returns a collection of PropertyObjects
     * @return Collection
     * @throws
     * @throws Exceptions\InvalidBLMException
     */
    public function parseBlm();

    /**
     * Sets the BLM data to parse - if called, will set blmFilePath to null.
     * @param string $blmContentString
     */
    public function setBlmContents($blmContentString);

    /**
     * Sets the path of the BLM file to parse - if called, will set blmContents to null.
     * @param string $filePath
     */
    public function setBlmFilePath($filePath);
    /**
     * returns the BLM data as a string to be parsed.
     * @return string|null
     */
    public function getBlmContents();

    /**
     * returns the file path to the BLM file as a string.
     * @return string|null
     */
    public function getBlmFilePath();
}