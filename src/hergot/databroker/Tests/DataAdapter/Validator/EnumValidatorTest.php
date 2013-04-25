<?php

namespace hergot\databroker\Tests\DataAdapter\Validator;

use hergot\databroker\DataAdapter\Validator\EnumValidator;

class EnumValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function testSetAllowedValues() {
        $ev = new EnumValidator();
        $ev->setAllowedValues(array(1,2,3));
        $this->assertTrue($ev->isValid(1));
        $this->assertTrue($ev->isValid(2));
        $this->assertTrue($ev->isValid(3));
        $this->assertFalse($ev->isValid(4));
        $ev->setAllowedValues(new \ArrayObject(array(1,2,3)));
        $this->assertTrue($ev->isValid(1));
        $this->assertTrue($ev->isValid(2));
        $this->assertTrue($ev->isValid(3));
        $this->assertFalse($ev->isValid(4));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetAllowedValuesBadOptions() {
        $ev = new EnumValidator();
        $ev->setAllowedValues(false);
    }

    public function testAddAllowedValue() {
        $ev = new EnumValidator();
        $ev->addAllowedValue(1);
        $this->assertTrue($ev->isValid(1));
        $this->assertFalse($ev->isValid(2));
        $ev->addAllowedValue(2);
        $this->assertTrue($ev->isValid(2));
        $this->assertFalse($ev->isValid(3));
    }
    
}