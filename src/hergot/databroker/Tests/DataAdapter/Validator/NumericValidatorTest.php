<?php

namespace hergot\databroker\Tests\DataAdapter\Validator;

use hergot\databroker\DataAdapter\Validator\NumericValidator;

class NumericValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function testMin() {
        $sv = new NumericValidator();
        $sv->setMin(1);
        $this->assertFalse($sv->isValid(0));
        $this->assertTrue($sv->isValid(10));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMinNonInteger() {
        $sv = new NumericValidator();
        $sv->setMin('test');
    }
    
    public function testMax() {
        $sv = new NumericValidator();
        $sv->setMax(1);
        $this->assertFalse($sv->isValid(10));
        $this->assertTrue($sv->isValid(0));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMaxNonInteger() {
        $sv = new NumericValidator();
        $sv->setMax('test');
    }
    
    public function testIsValid() {
        $sv = new NumericValidator();
        $this->assertTrue($sv->isValid(12));
        $this->assertFalse($sv->isValid('test'));
    }
    
}