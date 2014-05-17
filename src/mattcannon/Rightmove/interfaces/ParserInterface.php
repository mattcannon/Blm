<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 17/05/2014
 * Time: 12:20
 */
namespace mattcannon\Rightmove\Interfaces;

use mattcannon\Rightmove\Exceptions;
use Illuminate\Support\Collection;


/**
 * Interface ParserInterface
 *
 * To use the parser you should create a new instance of the Parser class, then set the BLM contents, or pass in a file path to a BLM.
 * To access the data in the BLM, you should call ```parseBlm()```.
 *
 * ```php
 * $parser = new \mattcannon\Rightmove\Parser;
 * $parser->setBlmFilePath('/path/to/blm/file');
 *
 * $data = $parser->parseBlm();
 *
 * foreach($data as $property){
 *      var_dump($property);
 * }
 * ```
 *
 * @package mattcannon\Rightmove\Interfaces
 * @author Matt Cannon
 *
 */
interface ParserInterface extends \Psr\Log\LoggerAwareInterface
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