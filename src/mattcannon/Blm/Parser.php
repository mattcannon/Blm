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
use Illuminate\Support\Str;
use MattCannon\Blm\Exceptions\InvalidBLMException;
use MattCannon\Blm\Interfaces\BlmLoaderInterface;
use MattCannon\Blm\Interfaces\ParserInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Parser
 *
 * Please see documentation for [MattCannon\Blm\Interfaces\ParserInterface](mattcannon.Rightmove.interfaces.ParserInterface.html) to see how
 * this should be used. Any Methods not listed in ParserInterface, or LoggerAwareInterface
 * are not considered public API, and may change without notice.
 *
 * @link mattcannon.Rightmove.interfaces.ParserInterface.html
 * @package MattCannon\Blm
 * @author Matt Cannon
 */
class Parser implements ParserInterface
{
    /**
     * The version specified in the BLM header
     * @var string
     */
    protected $version;
    /**
     * The End of Field delimiter specified in the BLM header
     * @var string
     */
    protected $eof;
    /**
     * The End of Row delimiter specified in the BLM header
     * @var string
     */
    protected $eor;
    /**
     * PSR compliant logger to use
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * String containing BLM data to parse
     * @var string|null
     */
    private $blmContents;

    /**
     * BlmLoader which supplies content from strings, or files/zips/remotes etc. if another form is needed
     * there is an interface that can be used and substituted in.
     * @var BlmLoaderInterface $blmLoader
     */
    private $blmLoader;
    /**
     * Create a new parser object. expects an psr complient logger to be passed in.
     * @api
     * @param LoggerInterface $logger
     * @param BlmLoaderInterface $blmLoader
     */
    public function __construct(LoggerInterface $logger, BlmLoaderInterface $blmLoader)
    {
        $this->logger = $logger;
        $this->blmLoader = $blmLoader;
    }
    /**
     * Parses the BLM and returns a collection of PropertyObjects
     * @return \Illuminate\Support\Collection
     * @throws
     * @throws Exceptions\InvalidBLMException
     * @api
     */
    public function parseBlm()
    {
        // Gets content of the BLM.
        if (is_null($this->blmLoader)) {
            throw new Exception('No Blm Loader found - unable to parse blm');
        }
        $this->logger->debug('Getting contents of BLM.');
        $this->setBlmContents($this->blmLoader->getBlmContents());

        //Parses the header of the BLM, and sets the version,eof, and eor instance variables for the parser.
        $this->logger->debug('Parsing header of BLM file.');
        $this->parseHeader($this->getBlmContents());

        //Gets the titles from the field definitions
        /** @var array $fieldTitles */
        $this->logger->debug('Parsing field titles of BLM file.');
        $fieldTitles = $this->parseFields($this->getBlmContents());

        //Gets the property data from the Data section, and combines it with the field titles.
        /** @var \Illuminate\Support\Collection $properties */
        $this->logger->debug('Parsing properties in BLM file.');
        $properties = $this->parseData($this->getBlmContents(), $fieldTitles);

        return $properties;
    }

    /**
     * get the Data section of the BLM, and convert it to a Collection of PropertyObjects
     * @param  string                         $fileContents
     * @param  array                          $fieldTitles
     * @return \Illuminate\Support\Collection
     * @throws InvalidBLMException
     */
    public function parseData($fileContents, array $fieldTitles)
    {
        //find the data section, and extract it.
        $dataStartOffset = strpos($fileContents, '#DATA#') + 6;
        $dataEndOffset = strpos($fileContents, '#END#') - $dataStartOffset;
        $rows = explode($this->eor, substr($fileContents, $dataStartOffset, $dataEndOffset));

        //remove the last row from the array, as it will be empty
        array_pop($rows);

        $this->logger->debug('Parsed rows for data', [
                'offset start'=>$dataStartOffset,
                'offset finish'=>$dataEndOffset,
                'EoR delimiter'=>$this->eor,
                'rows found'=>sizeof($rows)
            ]);
        //loop over the array, and parse the rows.
        for ($i = 0; $i < sizeof($rows); $i++) {
            $rows[$i] = $this->parseRow(trim($rows[$i]));
        }

        /*
         * Loop over parsed rows, and generate property objects for them.
         * throw an exception of there is a size mismatch.
         */
        $finalRows = array();
        foreach ($rows as $row) {
            if (sizeof($row) !== sizeof($fieldTitles)) {
                $this->logger->critical('BLM file definition mismatch', [
                        'property'=>$row[0],
                        'expected field count'=>sizeof($fieldTitles),
                        'actual size'=>sizeof($row)
                    ]);
                throw new InvalidBLMException(
                    'Property with ID:' . $row[0]
                    .' contains a different number of fields, than the header definition.'
                );
            }
            $finalRows[] = new PropertyObject(array_combine($fieldTitles, $row));
            $this->logger->debug('Created property object', ['property reference'=>$row[0]]);
        }
        $collection = new Collection($finalRows);

        return $collection;
    }

    /**
     * parse the row into an array.
     * @param  string $row
     * @return array
     */
    public function parseRow($row)
    {
        $result = explode($this->eof, substr($row, 0, -1));
        $this->logger->debug('Parsed row.', [
                'fieldCount'=>sizeof($result),
                'property'=>$result[0]
            ]);

        return $result;
    }

    /**
     * Gets the header section of the BLM, and uses it to configure the parser.
     *
     * @param  string              $fileContents
     * @throws InvalidBLMException
     */
    public function parseHeader($fileContents)
    {
        //Gets finishing position of the Header.
        $headerOffset = strpos($fileContents, '#DEFINITION#');
        $headerRows = explode("\n", trim(substr($fileContents, 9, $headerOffset - 9)));

        //extract the rows, and convert them into an array.
        $header = array();
        foreach ($headerRows as $row) {
            list($key, $value) = explode(':', $row);
            $header[trim(strtolower($key))] = trim($value);
        }

        //set the version of the BLM schema.
        if (!array_key_exists('version', $header)) {
            throw new InvalidBLMException('BLM header invalid - version not found');
        }
        $this->version = $header['version'];

        //set the EoF delimiter for the current BLM
        if (!array_key_exists('eof', $header)) {
            throw new InvalidBLMException('BLM header invalid - End of Field delimiter not found');
        }

        //set the EoR delimiter for the current BLM
        $this->eof = substr($header['eof'], 1, strlen($header['eof']) - 2);
        if (!array_key_exists('eor', $header)) {
            throw new InvalidBLMException('BLM header invalid - End of Row delimiter not found');
        }
        $this->eor = substr($header['eor'], 1, strlen($header['eor']) - 2);
    }

    /**
     * Calculate Field Titles from the Definition section of the BLM.
     *
     * @param  string $fileContents
     * @return array
     */
    public function parseFields($fileContents)
    {
        //get the start and finish markers for the definitions
        $definitionsStartOffset = strpos($fileContents, '#DEFINITION#') + 12;
        $definitionsFinishOffset = strpos($fileContents, '#DATA#') - $definitionsStartOffset;
        $definitions = trim(substr($fileContents, $definitionsStartOffset, $definitionsFinishOffset));

        //remove empty sections from the array
        $rows = array_filter(explode($this->eor, $definitions));

        //loop over rows, and calculate field titles.
        for ($i = 0; $i<sizeof($rows); $i++) {
            $rows[$i] = $this->parseRow($rows[$i]);
        }

        //convert field names to camel case
        foreach ($rows[0] as $key => $value) {
            $rows[0][$key] = Str::camel(strtolower($value));
        }

        return $rows[0];
    }

    /**
     * Sets the BLM data to parse - if called, will set blmFilePath to null.
     * @param $blmContentString
     * @api
     */
    public function setBlmContents($blmContentString)
    {
        $this->blmContents = mb_convert_encoding($blmContentString,'UTF-8');
    }

    /**
     * sets the BlmLoader, this is used to return the BLM data to parse
     * @param BlmLoaderInterface $blmLoader
     */
    public function setBlmLoader(BlmLoaderInterface $blmLoader)
    {
        $this->blmLoader = $blmLoader;
    }

    /**
     * gets the BlmLoader to enable parsing of the blm
     * @return BlmLoaderInterface $blmLoader
     */
    public function getBlmLoader()
    {
        return $this->blmLoader;
    }
    /**
     * returns the BLM data as a string to be parsed.
     * @return string|null
     * @api
     */
    public function getBlmContents()
    {
        return $this->blmContents;
    }
    /**
     * Sets a logger instance on the object
     *
     * @param  LoggerInterface $logger
     * @return null
     * @api
     * @codeCoverageIgnore
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
