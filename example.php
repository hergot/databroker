<?php

require 'vendor/autoload.php';

use hergot\databroker\DataAdapter\ParameterCollection;

class MyDataAdapter implements \hergot\databroker\DataAdapter\DataAdapterInterface {
    public function fetch(array $parameters = array()) {
        var_dump('sleep');
        throw new Exception('test');
        sleep(1);        
        return 'test123';
    }

    public function getParameters(ParameterCollection $parameters) {
        return $parameters;
    }
}

$loader = new \hergot\databroker\DataAdapter\DataAdapterCallbackLoader(function($name) {
    return new MyDataAdapter;
});

mkdir('/tmp/monitor');
$writer = new hergot\databroker\Plugin\Monitor\FileMonitorRecordWriter('/tmp/monitor');
$monitorPlugin = new \hergot\databroker\Plugin\MonitorPlugin($writer);
$db = new \hergot\databroker\DataBroker($loader);
$db->addPlugin($monitorPlugin);
var_dump($db->execute('test', array('a' => 1)));