<?php
use mattcannon\Rightmove\Parser;

/**
 * Created by PhpStorm.
 * User: matt
 * Date: 07/05/2014
 * Time: 22:31
 */

class ParserTest extends Base
{
    /**
     * @var mattcannon\Rightmove\Parser;
     */
    protected $parser;
    /**
     * @var string
     */
    protected $validDocument;

    /**
     *
     */
    public function setUp()
    {
        $this->parser = new Parser();

        $this->testClass = $this->parser;
        parent::setUp();
        $this->validDocument = <<<BLM
#HEADER#
VERSION : 3i
EOF : '|'
EOR : '~'

#DEFINITION#
AGENT_REF|HOUSE_NAME_NUMBER|STREET_NAME|OS_TOWN_CITY|OS_REGION|ZIPCODE|COUNTRY_CODE|FEATURE1|FEATURE2|FEATURE3|FEATURE4|FEATURE5|FEATURE6|FEATURE7|FEATURE8|FEATURE9|FEATURE10|SUMMARY|DESCRIPTION|CREATE_DATE|UPDATE_DATE|BRANCH_ID|STATUS_ID|BEDROOMS|PRICE|PRICE_QUALIFIER|NEW_HOME_FLAG|PROP_SUB_ID|DISPLAY_ADDRESS|PUBLISHED_FLAG|LET_DATE_AVAILABLE|LET_BOND|LET_TYPE_ID|LET_FURN_ID|LET_RENT_FREQUENCY|TRANS_TYPE_ID|DEVELOPMENT_NAME|MEDIA_IMAGE_00|MEDIA_IMAGE_01|MEDIA_IMAGE_02|MEDIA_IMAGE_03|MEDIA_IMAGE_04|MEDIA_IMAGE_05|MEDIA_IMAGE_06|MEDIA_IMAGE_07|MEDIA_IMAGE_08|MEDIA_IMAGE_09|MEDIA_VIRTUAL_TOUR_00|~

#DATA#
37565_1160298|Panorama Beach |Sunny Beach |NESSEBAR|BURGAS||BG|||||||||||A fully furnished studio apartment in the Panorama Beach complex benefiting from on site facilities including restaurant and bar, spa, supermarket, banks, separate swimming pools for adults and chi...|A fully furnished studio apartment in the Panorama Beach complex benefiting from on site facilities including restaurant and bar, spa, supermarket, banks, separate swimming pools for adults and children and 24 hour security. Further benefits include South Coast beach situated just 200 metres away and Burgas International Airport is approximately 23km away.<br/> <BR><BR><B>FEATURES</B><BR><BR><LI>WIDE CHOICE OF SHOPS & RESTAURANTS<LI>ONE OF THE OLDEST TOWNS IN EUROPE<LI>OLD TOWN IS PROTECTED AS A UNESCO WORLD HERITAGE SITE<LI>MOST FAVOURED BY TOURISTS ON THE SOUTHERN COAST<LI>NESSEBAR BOASTS FOUR MILES OF GOLDEN SANDY BEACHES WITH BLUE FLAG AWARD STATUS<BR><BR><br><br><p><b>Lounge/Bedroom:</b><br>laminate flooring, painted walls,air conditioning unit, PVC joinery.<br/></p><p><b>Kitchen:</b><br>laminate flooring, painted walls, tap with a counter, inside doors MDF, front/entrance doors - MDF, option for cable television and one telephone.<br/></p><p><b>Bathroom:</b><br>terracotta flooring, tiled walls, low level WC, wash hand basin with mixer tap, shower cubicle, electric boiler.<br/></p><p><b>Terrace:</b><br>approx 6.72 square metres, roof terrace/garden overlooking ocean and swimming pool.<br/></p>|2008-03-16 21:29:36|2008-03-15 16:28:36|37565|0|0|53,366||N|8|Panorama Beach  ,Sunny Beach  ,Nessebar ,Burgas ,Bulgaria|1|||0|2||1||37565_1160298_IMG_00.jpg|37565_1160298_IMG_01.jpg|37565_1160298_IMG_02.jpg|||||||||~
#END#
BLM;

    }

    /**
     *
     */
    public function tearDown()
    {
        unset($this->parser);
        parent::tearDown();
    }

    /**
     * tests that a parser object is correctly instantiated
     */
    public function testCanConstructParser()
    {
        $parser = new Parser();
        $this->assertTrue(get_class($parser)=='mattcannon\Rightmove\Parser');
    }

    /**
     * test that a parser can receive file paths correctly
     */
    public function testCanSetPathCorrectly()
    {
        $parser = new Parser();
        $parser->setBlmFilePath('withPath');
        $this->assertTrue($parser->getBlmFilePath() == 'withPath');
    }

    /**
     * tests that the parser can interpret the version field of the file
     * @throws mattcannon\Rightmove\Exceptions\InvalidBLMException
     */
    public function testCanParseHeaderVersion()
    {
        $this->parser->parseHeader($this->validDocument);
        $version = $this->getProperty("version");
        $this->assertTrue(isset($version),'version should be set');
    }

    /**
     * tests that the parser can interpret the end of row property correctly
     * @throws mattcannon\Rightmove\Exceptions\InvalidBLMException
     */
    public function testCanParseHeaderEOR()
    {
        $this->parser->parseHeader($this->validDocument);
        $version = $this->getProperty("eor");
        $this->assertTrue(isset($version),'eor should be set');
    }

    /**
     * tests that the parser can interpret the end of field property correctly
     * @throws mattcannon\Rightmove\Exceptions\InvalidBLMException
     */
    public function testCanParseHeaderEOF()
    {
        $this->parser->parseHeader($this->validDocument);
        $version = $this->getProperty("eof");
        $this->assertTrue(isset($version),'eof should be set');
    }

    /**
     * test that an exception is thrown if there isn't a version number set
     * @expectedException mattcannon\Rightmove\Exceptions\InvalidBLMException
     */
    public function testDoesThrowExceptionForMissingVersion()
    {
        $document = $this->validDocument;
        $document = str_replace('VERSION','vers',$document);
        $this->parser->parseHeader($document);
    }
    /**
     * test that an exception is thrown if there isn't an EOR delimiter set
     * @expectedException mattcannon\Rightmove\Exceptions\InvalidBLMException
     */
    public function testDoesThrowExceptionForMissingEor()
    {
        $document = $this->validDocument;
        $document = str_replace('EOR','sub',$document);
        $this->parser->parseHeader($document);
    }
    /**
     * test that an exception is thrown if there isn't an EOF delimiter set
     * @expectedException mattcannon\Rightmove\Exceptions\InvalidBLMException
     */
    public function testDoesThrowExceptionForMissingEof()
    {
        $document = $this->validDocument;
        $document = str_replace('EOF','sub',$document);
        $this->parser->parseHeader($document);
    }

    /**
     * tests that the parser can correctly seperate fields into the correct number of elements.
     */
    public function testCanParseFields()
    {
        $this->setProperty('eof','|');
        $this->setProperty('eor','~');
        $result = $this->parser->parseFields($this->validDocument);
        $this->assertTrue(sizeof($result)==48);
    }

    /**
     * tests that the parser correctly parses a document into the correct number of properties.
     * @throws mattcannon\Rightmove\Exceptions\InvalidBLMException
     */
    public function testCanParseData()
    {
        $this->setProperty('eof','|');
        $this->setProperty('eor','~');
        $headers = array();
        for ($i=0;$i<48;$i++) {
            $headers[] = $i;
        }
        $result = $this->parser->parseData($this->validDocument,$headers);
        $this->assertTrue(sizeof($result)==1);
    }

    /**
     * tests that the blm will correctly parse a file.
     */
    public function testCanParseFile()
    {

        $oClass = $this->getMock('mattcannon\Rightmove\Parser', array('getBlmFileContents'),array('./testPath'));
        $oClass->expects($this->any())->method('getBlmFileContents')->will($this->returnValue($this->validDocument));
        $oClass->setBlmFilePath('anyPath');
        $result = $oClass->parseBlm();
        $this->assertTrue(sizeof($result)==1);
    }

    /**
     * tests that the parser can correctly parse an injected string.
     * @throws mattcannon\Rightmove\Exceptions\InvalidBLMException
     */
    public function testCanParseBlmContentOnly()
    {
        $this->parser->setBlmContents($this->validDocument);
        $result = $this->parser->parseBlm();
        $this->assertTrue(sizeof($result)>0);
    }
    /**
     * tests that the parser correctly throws an error if there is a mismatch between fields defined, and parsed.
     * @expectedException mattcannon\Rightmove\Exceptions\InvalidBLMException
     */
    public function testDoesThrowExceptionForFieldCountMismatch()
    {
        $this->setProperty('eof','|');
        $this->setProperty('eor','~');
        $headers = array();
        for ($i=0;$i<46;$i++) {
            $headers[] = $i;
        }
        $this->parser->parseData($this->validDocument,$headers);
    }

}
