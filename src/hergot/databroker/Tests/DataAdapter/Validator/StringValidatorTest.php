<?php

namespace hergot\databroker\Tests\DataAdapter\Validator;

use hergot\databroker\DataAdapter\Validator\StringValidator;

class StringValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function testMinLength() {
        $sv = new StringValidator();
        $sv->setMinLength(1);
        $this->assertFalse($sv->isValid(''));
        $this->assertTrue($sv->isValid('test'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMinLengthNonInteger() {
        $sv = new StringValidator();
        $sv->setMinLength('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMinLengthLessThanZero() {
        $sv = new StringValidator();
        $sv->setMinLength(-1);
    }
    
    public function testMaxLength() {
        $sv = new StringValidator();
        $sv->setMaxLength(1);
        $this->assertFalse($sv->isValid('test'));
        $this->assertTrue($sv->isValid('t'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMaxLengthNonInteger() {
        $sv = new StringValidator();
        $sv->setMaxLength('test');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMaxLengthLessThanZero() {
        $sv = new StringValidator();
        $sv->setMaxLength(-1);
    }
    
    public function testIsValid() {
        $sv = new StringValidator();
        $this->assertTrue($sv->isValid('test'));
        $this->assertFalse($sv->isValid(1.2));
    }
    
}