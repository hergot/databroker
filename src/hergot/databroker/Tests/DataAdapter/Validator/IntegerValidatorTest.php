<?php

namespace hergot\databroker\Tests\DataAdapter\Validator;

use hergot\databroker\DataAdapter\Validator\IntegerValidator;

class IntegerValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function testMin() {
        $sv = new IntegerValidator();
        $sv->setMin(1);
        $this->assertFalse($sv->isValid(0));
        $this->assertTrue($sv->isValid(10));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMinNonInteger() {
        $sv = new IntegerValidator();
        $sv->setMin('test');
    }
    
    public function testMax() {
        $sv = new IntegerValidator();
        $sv->setMax(1);
        $this->assertFalse($sv->isValid(10));
        $this->assertTrue($sv->isValid(0));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMaxNonInteger() {
        $sv = new IntegerValidator();
        $sv->setMax('test');
    }
    
    public function testIsValid() {
        $sv = new IntegerValidator();
        $this->assertTrue($sv->isValid(12));
        $this->assertFalse($sv->isValid('test'));
    }
    
}