<?php

namespace hergot\databroker\Tests\DataAdapter;

use hergot\databroker\DataAdapter\Parameter;

class ParameterTest extends \PHPUnit_Framework_TestCase {
    
    public function testName() {
        $p = new Parameter('test');
        $this->assertEquals('test', $p->getName());
    }

    public function testRequired() {
        $p = new Parameter('test');
        $this->assertEquals(false, $p->isRequired());
        $p->setRequired(true);
        $this->assertEquals(true, $p->isRequired());
    }

    public function testDefaultValue() {
        $p = new Parameter('test');
        $this->assertEquals(null, $p->getDefaultValue());
        $p->setDefaultValue('test');
        $this->assertEquals('test', $p->getDefaultValue());
    }

    public function testValidator() {
        $p = new Parameter('test');
        $this->assertEquals(null, $p->getValidator());
        $validatorMock = $this->getMock('\hergot\databroker\DataAdapter\ValidatorInterface');
        $validatorMock->expects($this->never())->method('isValid');
        $p->setValidator($validatorMock);
        $this->assertEquals($validatorMock, $p->getValidator());
                
    }

    public function testValue() {
        $p = new Parameter('test');
        $this->assertEquals(null, $p->getValue());
        $p->setValue('test');
        $this->assertEquals('test', $p->getValue());
        $validatorMock = $this->getMock('\hergot\databroker\DataAdapter\ValidatorInterface');
        $validatorMock->expects($this->once())->method('isValid')->will($this->returnCallback(function($value) {
            $this->assertEquals('test', $value);
            return true;
        }));
        $p->setValidator($validatorMock);
        $p->setValue('test');
        $this->assertEquals('test', $p->getValue());                
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValueValidatorException() {
        $p = new Parameter('test');
        $this->assertEquals(null, $p->getValue());
        $p->setValue('test');
        $this->assertEquals('test', $p->getValue());
        $validatorMock = $this->getMock('\hergot\databroker\DataAdapter\ValidatorInterface');
        $validatorMock->expects($this->once())->method('isValid')->will($this->returnCallback(function($value) {
            $this->assertEquals('test', $value);
            return false;
        }));
        $p->setValidator($validatorMock);
        $p->setValue('test');                
    }
    
}