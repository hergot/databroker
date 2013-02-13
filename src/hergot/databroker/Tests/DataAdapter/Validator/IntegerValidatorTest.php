<?php

namespace hergot\databroker\Tests\DataAdapter\Validator;

use hergot\databroker\DataAdapter\Validator\IntegerValidator;

class IntegerValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function testMin() {
        $iv = new IntegerValidator();
        $iv->setMin(1);
        $this->assertFalse($iv->isValid(0));
        $this->assertTrue($iv->isValid(10));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMinNonInteger() {
        $iv = new IntegerValidator();
        $iv->setMin('test');
    }
    
    public function testMax() {
        $iv = new IntegerValidator();
        $iv->setMax(1);
        $this->assertFalse($iv->isValid(10));
        $this->assertTrue($iv->isValid(0));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMaxNonInteger() {
        $iv = new IntegerValidator();
        $iv->setMax('test');
    }
    
    public function testIsValid() {
        $iv = new IntegerValidator();
        $this->assertTrue($iv->isValid(12));
        $this->assertFalse($iv->isValid('test'));
    }
    
}