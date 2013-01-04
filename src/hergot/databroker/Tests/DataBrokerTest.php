<?php

namespace hergot\databroker\Tests;

use hergot\databroker\DataBroker;

class DataBrokerTest extends \PHPUnit_Framework_TestCase {
    
    public function testExecute() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('getParameters', 'fetch'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => array('required' => true))));

        $adapterMock->expects($this->once())
                ->method('fetch')
                ->will($this->returnValue('test'));
        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $this->assertEquals('test', $databroker->execute('testAdapter', array('parameter' => 1)));
    }

    /**
     * @expectedException \hergot\databroker\DataBrokerException
     * @expectedExceptionCode \hergot\databroker\DataBrokerException::CANNOT_INITIALIZE_DATA_ADAPTER
     */
    public function testExecuteCannotInitializeDataAdapter() {        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function() {
                    throw new \Exception('test');
                }));
        $databroker = new DataBroker($loaderMock);
        $databroker->execute('testAdapter', array());
    }
    
    /**
     * @expectedException \hergot\databroker\DataBrokerException
     * @expectedExceptionCode \hergot\databroker\DataBrokerException::MISSING_REQUIRED_PARAMETER
     */
    public function testExecuteMissingRequiredParameter() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('getParameters', 'fetch'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => array('required' => true))));
        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $databroker->execute('testAdapter', array());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testExecuteDataAdapterParameterNonArrayDefinition() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('getParameters', 'fetch'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => 1)));
        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $databroker->execute('testAdapter', array());
    }

    public function testExecuteDataAdapterParameterDefaultValue() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('getParameters', 'fetch'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => array('default' => 'test'))));

        $adapterMock->expects($this->once())
                ->method('fetch')
                ->will($this->returnCallback(function(array $parameters) {
                    $this->assertEquals('test', $parameters['parameter']);
                    return 'test';
                }));
        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $this->assertEquals('test', $databroker->execute('testAdapter', array()));
    }

    public function testExecuteDataAdapterParameterNoDefinition() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('getParameters', 'fetch'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter')));

        $adapterMock->expects($this->once())
                ->method('fetch')
                ->will($this->returnCallback(function(array $parameters) {
                    $this->assertEquals(array('parameter' => null), $parameters);
                    return 'test';
                }));
        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $this->assertEquals('test', $databroker->execute('testAdapter', array()));
    }

    /**
     * @expectedException \hergot\databroker\DataBrokerException
     * @expectedExceptionCode \hergot\databroker\DataBrokerException::MISMATCH_PARAMETER_TYPE
     */
    public function testExecuteDataAdapterParameterTypeMismatch() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('getParameters', 'fetch'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => array('type' => 'integer'))));
        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $databroker->execute('testAdapter', array('parameter' => 'test'));
    }

    /**
     * @expectedException \hergot\databroker\DataBrokerException
     * @expectedExceptionCode \hergot\databroker\DataBrokerException::MISMATCH_PARAMETER_INTERFACE
     */
    public function testExecuteDataAdapterParameterInterfaceMismatch() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('getParameters', 'fetch'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array('parameter' => array('interface' => '\stdClass'))));
        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    
                    return $adapterMock;
                }));
        $databroker = new DataBroker($loaderMock);
        $databroker->execute('testAdapter', array('parameter' => 'test'));
    }
 
    public function testExecutePlugin() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('fetch', 'getParameters'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array()));
        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    return $adapterMock;
                }));
                
        $pluginMock = $this->getMock('\hergot\databroker\Plugin\PluginInterface');
        $pluginMock->expects($this->once())
                ->method('runBeforeExecute')
                ->will($this->returnCallback(function(\hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter, array $parameters, $result) use ($adapterMock) {
                    $this->assertEquals($adapterMock, $dataAdapter);
                    $this->assertEquals(array('parameter' => 'test'), $parameters);
                    $this->assertEquals(null, $result);
                    return 'test';
                }));
        $pluginMock->expects($this->once())
                ->method('runAfterExecute')
                ->will($this->returnCallback(function(\hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter, array $parameters, $result, \Exception $exception=null) use ($adapterMock) {
                    $this->assertEquals($adapterMock, $dataAdapter);
                    $this->assertEquals(array('parameter' => 'test'), $parameters);
                    $this->assertEquals('test', $result);
                    $this->assertEquals(null, $exception);
                    return 'test2';
                }));
         
        $databroker = new DataBroker($loaderMock);
        $databroker->addPlugin($pluginMock);
        $this->assertEquals('test2', $databroker->execute('testAdapter', array('parameter' => 'test')));        
    }

    public function testExecutePluginOrder() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('fetch', 'getParameters'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array()));
        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    return $adapterMock;
                }));
                
        $start1Before = null;
        $start1After = null;
        $start2Before = null;
        $start2After = null;
        $pluginMock = $this->getMock('\hergot\databroker\Plugin\PluginInterface');
        $pluginMock->expects($this->once())
                ->method('runBeforeExecute')
                ->will($this->returnCallback(function(\hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter, array $parameters, $result) use ($adapterMock, &$start1Before) {
                    $start1Before = microtime(true); 
                    return 'test';
                }));
        $pluginMock->expects($this->once())
                ->method('runAfterExecute')
                ->will($this->returnCallback(function(\hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter, array $parameters, $result, \Exception $exception=null) use ($adapterMock, &$start1After) {
                    $start1After = microtime(true); 
                    return 'test2';
                }));
        $pluginMock2 = $this->getMock('\hergot\databroker\Plugin\PluginInterface');
        $pluginMock2->expects($this->once())
                ->method('runBeforeExecute')
                ->will($this->returnCallback(function(\hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter, array $parameters, $result) use ($adapterMock, &$start2Before) {
                    $start2Before = microtime(true); 
                    return 'test';
                }));
        $pluginMock2->expects($this->once())
                ->method('runAfterExecute')
                ->will($this->returnCallback(function(\hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter, array $parameters, $result, \Exception $exception=null) use ($adapterMock, &$start2After) {
                    $start2After = microtime(true); 
                    return 'test2';
                }));
         
        $databroker = new DataBroker($loaderMock);
        $databroker->addPlugin($pluginMock);
        $databroker->addPlugin($pluginMock2);
        $this->assertEquals('test2', $databroker->execute('testAdapter', array('parameter' => 'test')));        
        $this->assertTrue($start1Before < $start2Before);
        $this->assertTrue($start1After > $start2After);
    }
    
    public function testExecuteFetchThrowsException() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('fetch', 'getParameters'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array()));
        $adapterMock->expects($this->once())
                ->method('fetch')
                ->will($this->returnCallback(function() {
                    throw new \Exception('test');
                }));
        
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    return $adapterMock;
                }));
                         
        $databroker = new DataBroker($loaderMock);
        $this->assertEquals(null, $databroker->execute('testAdapter', array('parameter' => 'test')));                
    }
    
    public function testExecuteFetchThrowsValueException() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('fetch', 'getParameters'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array()));
        $adapterMock->expects($this->once())
                ->method('fetch')
                ->will($this->returnCallback(function() {
                    throw new \hergot\databroker\Plugin\ValueException('test', 0, null, 'test');
                }));
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    return $adapterMock;
                }));
                         
        $databroker = new DataBroker($loaderMock);
        $this->assertEquals('test', $databroker->execute('testAdapter', array('parameter' => 'test')));                
    }

    /**
     * @expectedException \hergot\databroker\Plugin\BubbleException
     * @expectedExceptionCode -1
     */
    public function testExecuteFetchThrowsBubbleException() {
        $adapterMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterInterface', array('fetch', 'getParameters'));
        $adapterMock->expects($this->once())
                ->method('getParameters')
                ->will($this->returnValue(array()));
        $adapterMock->expects($this->once())
                ->method('fetch')
                ->will($this->returnCallback(function() {
                    throw new \hergot\databroker\Plugin\BubbleException('test', -1, null);
                }));
        $loaderMock = $this->getMock('\hergot\databroker\DataAdapter\DataAdapterLoaderInterface', array('instantiate'));
        $loaderMock->expects($this->once())
                ->method('instantiate')
                ->will($this->returnCallback(function($name) use ($adapterMock) {
                    $this->assertEquals('testAdapter', $name);
                    return $adapterMock;
                }));
                         
        $databroker = new DataBroker($loaderMock);
        $databroker->execute('testAdapter', array('parameter' => 'test'));                
    }
    
}
