<?php

namespace hergot\databroker\Tests\DataAdapter\Validator;

use hergot\databroker\DataAdapter\Validator\StackValidator;

class StackValidatorTest extends \PHPUnit_Framework_TestCase {
    
    public function testAddValidator() {
        $sv = new StackValidator();
        $vm = $this->getMock('\hergot\databroker\DataAdapter\ValidatorInterface');
        $this->assertTrue($sv === $sv->addValidator($vm));
        $vm->expects($this->once())->method('isValid')->will($this->returnCallback(function($value) {
            $this->assertTrue($value === 1);
            return true;
        }));
        $sv->isValid(1);
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetValidatorsBadValidators() {
        $sv = new StackValidator();
        $sv->setValidators(1);
    }

    public function testSetValidators() {
        $sv = new StackValidator();
        $vm = $this->getMock('\hergot\databroker\DataAdapter\ValidatorInterface');
        $this->assertTrue($sv === $sv->setValidators(array($vm)));
        $vm->expects($this->once())->method('isValid')->will($this->returnCallback(function($value) {
            $this->assertTrue($value === 1);
            return false;
        }));
        $this->assertFalse($sv->isValid(1));
        $this->assertTrue($sv === $sv->setValidators(new \ArrayObject(array($vm))));
    }
    
}