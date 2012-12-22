<?php

namespace hergot\databroker\DataAdapter;

interface DataAdapterInterface {
    
    /**
     * Retrieve list of parameters needed for fetch data
     * 
     * @return array in form of array('parameterName', 'parameterName2' => array('required' => true), 'parameterName3' => array('default' => 'value'))
     */
    public function getParameters();
    
    /**
     * Fetch data from data source (e.g. filesystem, database, remote rest server, remote soap server, ...)
     * 
     * @param array $parameters
     * @return mixed
     */
    public function fetch(array $parameters=array());
}