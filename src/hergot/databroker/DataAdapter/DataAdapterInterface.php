<?php

namespace hergot\databroker\DataAdapter;

interface DataAdapterInterface {
    
    /**
     * Retrieve list of parameters needed for fetch data
     * 
     * @param ParameterCollection $parameters
     */
    public function getParameters(ParameterCollection $parameters);
    
    /**
     * Fetch data from data source (e.g. filesystem, database, remote rest server, remote soap server, ...)
     * 
     * @param array $parameters
     * @return mixed
     */
    public function fetch(array $parameters=array());
}