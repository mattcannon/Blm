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
 * foreach ($data as $property) {
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
     * Sets the BLM data to parse
     * @param string $blmContentString
     */
    public function setBlmContents($blmContentString);
    /**
     * returns the BLM data as a string to be parsed.
     * @return string|null
     */
    public function getBlmContents();

}
