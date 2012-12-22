<?php

namespace hergot\databroker\DataAdapter;

class DataAdapterSimpleLoader implements DataAdapterLoaderInterface {
    
    /**
     * @var string
     */
    private $adapterNamespace;

    /**
     * @param string $adapterNamespace
     */
    public function __construct($adapterNamespace) {
        $this->adapterNamespace = $adapterNamespace;
    }
    
    /**
     * Instantiate data adapter
     * 
     * @param string $name
     * @return DataAdapterInterface
     * @throws \ReflectionException
     * @throws \UnexpectedValueException
     */
    public function instantiate($name) {
        $dataAdapterClass = $this->adapterNamespace . '\\' . $name;
        $reflectionClass = new \ReflectionClass($dataAdapterClass);
        $dataAdapterInstance = $reflectionClass->newInstance();
        if (!$dataAdapterInstance instanceof DataAdapterInterface) {
            throw new \UnexpectedValueException('"' . $dataAdapterClass . '" not implements "\hergot\DataAdapter\DataAdapterInterface"');
        }
        return $dataAdapterInstance;
    }
}