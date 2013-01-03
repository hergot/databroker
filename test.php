<?php

require 'bootstrap.php';

class MyDataAdapter implements \hergot\databroker\DataAdapter\DataAdapterInterface {
    public function fetch(array $parameters = array()) {
        var_dump('sleep');
        sleep(3);        
        return 'test123';
    }

    public function getParameters() {
        return array();
    }
}

$loader = new \hergot\databroker\DataAdapter\DataAdapterCallbackLoader(function($name) {
    return new MyDataAdapter;
});

$backend1 = new \hergot\databroker\Plugin\Cache\Backend\FileCacheBackend('cache');
$backend2 = new \hergot\databroker\Plugin\Cache\Backend\FileCacheBackend('cache2');
$backend = new hergot\databroker\Plugin\Cache\Backend\StackCacheBackend();
$backend->addBackend($backend1);
$backend->addBackend($backend2);
$cachePlugin = new hergot\databroker\Plugin\CachePlugin();
$cachePlugin->setup('*')
        ->setCacheable(true)
        ->setLifeTime(100)
        ->setRefreshTime(10)
        ->setBackend($backend);
$db = new \hergot\databroker\DataBroker($loader);
$db->addPlugin($cachePlugin);
var_dump($db->execute('test', array()));