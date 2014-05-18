<?php

/**
 * Created by PhpStorm.
 * User: matt
 * Date: 08/05/2014
 * Time: 19:52
 */

class PropertyObjectTest extends Base
{
    /**
     * setup ready for testing, and default property object with valid items
     */
    public function setUp()
    {
        $propertyArray = [
            'statusId'  => 0,
            'priceQualifier' => 0,
            'publishedFlag' => 0,
            'letTypeId' => 0,
            'letFurnId' => 0,
            'letRentFrequency' => 0,
            'tenureTypeId' => 1,
            'transTypeId' => 1,
            'mediaImage01'=>'image1',
            'mediaImage02'=>'image2',
            'mediaImage03'=>'image3',
            'mediaImage04'=>'',
            'mediaImage60'=>'epc',
            'mediaImageText60'=>'EPC',
            'feature1'=>'feature1',
            'feature2'=>'feature2',
            'feature3'=>'',
            'propertyName' => 'test name'
        ];
        $this->testClass = new \mattcannon\Rightmove\PropertyObject($propertyArray);
        parent::setUp();
    }

    /**
     * clean up after test is complete
     */
    public function tearDown()
    {
        unset($this->testClass);
        parent::tearDown();
    }

    /**
     * tests getters.
     */
    public function testCanGetPropertyName()
    {
        $result = $this->testClass->propertyName;
        $this->assertEquals('test name',$result);
    }

    /**
     * tests features getter.
     */
    public function testCanGetFeatures()
    {
        $result = $this->testClass->features;
        $this->assertEquals(2,sizeof($result));
    }

    /**
     * tests images getter.
     */
    public function testCanGetImages()
    {
        $result = $this->testClass->images;
        $this->assertEquals(3,sizeof($result));
    }

    /**
     * tests jsonable interface
     */
    public function testCanGetDataAsJson()
    {
        $result = json_decode(json_encode($this->testClass),true);
        $this->assertEquals(4,sizeof($result));

    }

    /**
     * tests setters
     */
    public function testCanSetValue()
    {
        $this->testClass->houseName = 'testValue';
        $this->assertEquals('testValue',$this->testClass->houseName);
    }

    public function testCanGetEpcs()
    {
        $result = $this->testClass->epcs;
        $this->assertEquals('Image',$result->first()->type);
    }

    public function testCanGetHips()
    {
        $this->testClass->mediaImageText60 = 'HIP';
        $this->testClass->mediaImage60 = 'hip';
        $result = $this->testClass->hips;
        $this->assertEquals(1,sizeof($result));
    }

    public function testCanGetStatusId()
    {
        $this->assertEquals($this->testClass->statusId,'Available');
    }

    public function testCanGetPriceQualifier()
    {
        $this->assertEquals($this->testClass->priceQualifier,'Default');
    }

    public function testCanGetPublishedFlag()
    {
        $this->assertEquals($this->testClass->publishedFlag,'Hidden/invisible');
    }
    public function testCanGetLetTypeId()
    {
        $this->assertEquals($this->testClass->letTypeId,'Not Specified');
    }
    public function testCanGetLetFurnId()
    {
        $this->assertEquals($this->testClass->letFurnId,'Furnished');
    }
    public function testCanGetLetRentFrequency()
    {
        $this->assertEquals($this->testClass->letRentFrequency,'Weekly');
    }
    public function testCanGetTenureTypeId()
    {
        $this->assertEquals($this->testClass->tenureTypeId,'Freehold');
    }
    public function testCanGetTransTypeId()
    {
        $this->assertEquals($this->testClass->transTypeId,'Resale');
    }
}
