<?php

namespace hergot\databroker\Tests\DataAdapter\Validator;

use hergot\databroker\DataAdapter\Validator\FloatValidator;

class FloatValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function testMin() {
        $sv = new FloatValidator();
        $sv->setMin(1.0);
        $this->assertFalse($sv->isValid(0.5));
        $this->assertTrue($sv->isValid(10.5));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMinNonFloat() {
        $sv = new FloatValidator();
        $sv->setMin('test');
    }
    
    public function testMax() {
        $sv = new FloatValidator();
        $sv->setMax(1.0);
        $this->assertFalse($sv->isValid(10.5));
        $this->assertTrue($sv->isValid(0.3));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMaxNonFloat() {
        $sv = new FloatValidator();
        $sv->setMax('test');
    }
    
    public function testIsValid() {
        $sv = new FloatValidator();
        $this->assertTrue($sv->isValid(12.33));
        $this->assertFalse($sv->isValid('test'));
    }
    
}