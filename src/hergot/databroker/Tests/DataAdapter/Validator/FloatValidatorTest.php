<?php

namespace hergot\databroker\Tests\DataAdapter\Validator;

use hergot\databroker\DataAdapter\Validator\FloatValidator;

class FloatValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function testMin() {
        $fv = new FloatValidator();
        $fv->setMin(1.0);
        $this->assertFalse($fv->isValid(0.5));
        $this->assertTrue($fv->isValid(10.5));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMinNonFloat() {
        $fv = new FloatValidator();
        $fv->setMin('test');
    }
    
    public function testMax() {
        $fv = new FloatValidator();
        $fv->setMax(1.0);
        $this->assertFalse($fv->isValid(10.5));
        $this->assertTrue($fv->isValid(0.3));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMaxNonFloat() {
        $fv = new FloatValidator();
        $fv->setMax('test');
    }
    
    public function testIsValid() {
        $fv = new FloatValidator();
        $this->assertTrue($fv->isValid(12.33));
        $this->assertFalse($fv->isValid('test'));
    }
    
}