<?php
/**
 * Created by PhpStorm.
 * User: matt
 * Date: 07/05/2014
 * Time: 23:02
 */

abstract class Base extends \PHPUnit_Framework_TestCase
{
    /**
     * @var
     */
    protected $testClass;

    /**
     * @var ReflectionClass
     */
    protected $reflection;

    /**
     * sets up reflection class, to enable setting of private variables in testing.
     */
    public function setUp()
    {
        $this->reflection = new \ReflectionClass($this->testClass);
    }

    /**
     * cleans up reflection class after test.
     */
    public function tearDown()
    {
        unset($this->reflection);
    }

    /**
     * @param $method
     * @return mixed
     */
    public function getMethod($method)
    {
        $method = $this->reflection->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @param $property
     * @return mixed
     */
    public function getProperty($property)
    {
        $property = $this->reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($this->testClass);
    }

    /**
     * @param $property
     * @param $value
     * @return mixed
     */
    public function setProperty($property, $value)
    {
        $property = $this->reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->setValue($this->testClass, $value);
    }
}
