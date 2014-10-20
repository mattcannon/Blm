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
        $this->testClass = new \MattCannon\Blm\PropertyObject($propertyArray);
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
