<?php

namespace hergot\databroker\Tests\Plugin\Cache;

use hergot\databroker\Plugin\Cache\Backend\FileCacheBackend;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

class FileCacheBackendTest extends \PHPUnit_Framework_TestCase {
    
    public function testInvalidate() {
        $root = vfsStream::setup('root');
        $fc = new FileCacheBackend(vfsStream::url('root'));
        $file = new vfsStreamFile('test');
        $root->addChild($file);
        $fc->invalidate('test');
        $this->assertFalse($root->hasChild('test'));
    }

    public function testAutocreateFolder() {
        $root = vfsStream::setup('root');
        $fc = new FileCacheBackend(vfsStream::url('root') . '/test');
        $this->assertTrue($root->hasChild('test'));
        $this->assertEquals(0777, $root->getChild('test')->getPermissions());
    }
    
    public function testWrite() {
        $root = vfsStream::setup('root');
        $fc = new FileCacheBackend(vfsStream::url('root'));
        $fc->write('1', 'test', 10);
        $this->assertTrue($root->hasChild('1'));
        $this->assertEquals(pack("N", time() + 10) . 'test', $root->getChild('1')->getContent());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWriteLifetimeNotInteger() {
        vfsStream::setup('root');
        $fc = new FileCacheBackend(vfsStream::url('root'));
        $fc->write('1', 'test', 'abc');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWriteLifetimeNegativeNumber() {
        vfsStream::setup('root');
        $fc = new FileCacheBackend(vfsStream::url('root'));
        $fc->write('1', 'test', -1);
    }
    
    public function testReadNotCached() {
        vfsStream::setup('root');
        $fc = new FileCacheBackend(vfsStream::url('root'));
        $this->assertEquals(null, $fc->read('1', null));
    }
    
    public function testReadCached() {
        $root = vfsStream::setup('root');
        $fc = new FileCacheBackend(vfsStream::url('root'));
        $file = new vfsStreamFile('test');
        $file->setContent(pack("N", time() + 10) . 'test');
        $root->addChild($file);        
        $this->assertEquals('test', $fc->read('test', null));
    }

    public function testReadExpiredCached() {
        $root = vfsStream::setup('root');
        $fc = new FileCacheBackend(vfsStream::url('root'));
        $file = new vfsStreamFile('test');
        $file->setContent(pack("N", time() - 10) . 'test');
        $root->addChild($file);        
        $this->assertEquals(null, $fc->read('test', null));
        $this->assertFalse($root->hasChild('test'));
    }    
}