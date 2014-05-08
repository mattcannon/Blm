<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 07/05/2014
 * Time: 19:42
 */

namespace mattcannon\Rightmove;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use mattcannon\Rightmove\Exceptions\InvalidBLMException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class Parser
 * @package mattcannon\Rightmove
 */
class Parser implements LoggerAwareInterface
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
     * Path to the BLM file to parse
     * @var null|string
     */
    private $filePath;

    /**
     * Create a new parser object - expects a file path to a BLM.
     * @param null|string $filePath
     */
    public function __construct($filePath = null)
    {
        $this->filePath = $filePath;
        $this->logger = new NullLogger();
    }

    /**
     * @return null|string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * returns contents of blm file.
     * @return string
     * @codeCoverageIgnore
     */
    protected function getBlmFileContents()
    {
        return implode('',file($this->filePath));
    }
    /**
     * Parses the BLM and returns a collection of PropertyObjects
     * @return Collection
     * @throws
     * @throws Exceptions\InvalidBLMException
     */
    public function parseFile()
    {
        // Gets content of the BLM file.
        $this->logger->debug('Getting contents of BLM file.',['filePath'=>$this->filePath]);
        $fileContents = $this->getBlmFileContents();
        //Parses the header of the BLM file, and sets the version,eof, and eor instance variables for the parser.
        $this->logger->debug('Parsing header of BLM file.',['filePath'=>$this->filePath]);
        $this->parseHeader($fileContents);

        //Gets the titles from the field definitions
        /** @var array $fieldTitles */
        $this->logger->debug('Parsing field titles of BLM file.',['filePath'=>$this->filePath]);
        $fieldTitles = $this->parseFields($fileContents);

        //Gets the property data from the Data section, and combines it with the field titles.
        /** @var \Illuminate\Support\Collection $properties */
        $this->logger->debug('Parsing properties in BLM file.',['filePath'=>$this->filePath]);
        $properties = $this->parseData($fileContents,$fieldTitles);

        return $properties;
    }

    /**
     * get the Data section of the BLM, and convert it to a Collection of PropertyObjects
     * @param  string              $fileContents
     * @param  array               $fieldTitles
     * @return Collection
     * @throws InvalidBLMException
     */
    public function parseData($fileContents, array $fieldTitles)
    {
        //find the data section, and extract it.
        $dataStartOffset = strpos($fileContents,'#DATA#')+6;
        $dataEndOffset = strpos($fileContents,'#END#') - $dataStartOffset;
        $rows = explode($this->eor,substr($fileContents,$dataStartOffset,$dataEndOffset));

        //remove the last row from the array, as it will be empty
        array_pop($rows);

        $this->logger->debug('Parsed rows for data',[
                'offset start'=>$dataStartOffset,
                'offset finish'=>$dataEndOffset,
                'EoR delimiter'=>$this->eor,
                'rows found'=>sizeof($rows)
            ]);
        //loop over the array, and parse the rows.
        for ($i = 0;$i<sizeof($rows);$i++) {
            $rows[$i] = $this->parseRow(trim($rows[$i]));
        }

        //loop over parsed rows, and generate property objects for them. - throw an exception of there is a size mismatch.
        $finalRows = array();
        foreach ($rows as $row) {
            if (sizeof($row) !== sizeof($fieldTitles)) {
                $this->logger->critical('BLM file definition mismatch',['file'=>$this->filePath,'property'=>$row[0],'expected field count'=>sizeof($fieldTitles),'actual size'=>sizeof($row)]);
                throw new InvalidBLMException('Property with ID:' . $row[0] . ' contains a different number of fields, than the header definition. BLM:' . $this->filePath . ' is invalde');
            }
            $finalRows[] = new PropertyObject(array_combine($fieldTitles,$row));
            $this->logger->debug('Created property object',['property reference'=>$row[0]]);
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
        $result = explode($this->eof,substr($row,0,-1));
        $this->logger->debug('Parsed row.',['fieldCount'=>sizeof($result),'property'=>$result[0]]);
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
        if (!array_key_exists('version',$header)) {
            throw new InvalidBLMException('BLM header invalid - version not found');
        }
        $this->version = $header['version'];

        //set the EoF delimiter for the current BLM
        if (!array_key_exists('eof',$header)) {
            throw new InvalidBLMException('BLM header invalid - End of Field delimiter not found');
        }

        //set the EoR delimiter for the current BLM
        $this->eof = substr($header['eof'], 1, strlen($header['eof']) - 2);
        if (!array_key_exists('eor',$header)) {
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
        for ($i = 0;$i<sizeof($rows);$i++) {
            $rows[$i] = $this->parseRow($rows[$i]);
        }

        //convert field names to camel case
        foreach ($rows[0] as $key => $value) {
            $rows[0][$key] = Str::camel(strtolower($value));
        }

        return $rows[0];
    }

    /**
     * Sets a logger instance on the object
     *
     * @param  LoggerInterface $logger
     * @return null
     * @codeCoverageIgnore
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
