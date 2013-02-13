<?php

namespace hergot\databroker\Tests\DataAdapter\Validator;

use hergot\databroker\DataAdapter\Validator\InterfaceValidator;

class InterfaceValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function testInterface() {
        $iv = new InterfaceValidator();
        $iv->setInterface('stdClass');
        $this->assertFalse($iv->isValid(null));
        $this->assertFalse($iv->isValid(new \Directory()));
        $this->assertTrue($iv->isValid(new \stdClass()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetInterface() {
        $iv = new InterfaceValidator();
        $iv->setInterface(null);
    }
    
}