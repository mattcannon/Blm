<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 07/05/2014
 * Time: 23:02
 */

abstract class Base extends \PHPUnit_Framework_TestCase
{
    protected $testClass;

    protected $reflection;

    public  function setUp()
    {
        $this->reflection = new \ReflectionClass($this->testClass);
    }
    public function tearDown()
    {
        unset($this->reflection);
    }

    public function getMethod($method)
    {
        $method = $this->reflection->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }

    public function getProperty($property)
    {
        $property = $this->reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($this->testClass);
    }

    public function setProperty($property, $value)
    {
        $property = $this->reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->setValue($this->testClass, $value);
    }
}