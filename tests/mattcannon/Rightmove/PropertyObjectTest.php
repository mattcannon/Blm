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
            'mediaImage01'=>'image1',
            'mediaImage02'=>'image2',
            'mediaImage03'=>'image3',
            'mediaImage04'=>'',
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
        $this->assertEquals(3,sizeof($result));

    }

    /**
     * tests setters
     */
    public function testCanSetValue()
    {
        $this->testClass->houseName = 'testValue';
        $this->assertEquals('testValue',$this->testClass->houseName);
    }

}
