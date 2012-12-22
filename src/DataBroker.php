<?php

namespace hergot\databroker;

use hergot\DataAdapter\DataAdapterLoaderInterface;

class DataBroker {

    /**
     * @var DataAdapterLoaderInterface
     */
    private $dataAdapterLoader;
    
    /**
     * @param $dataAdapterLoader DataAdapterLoaderInterface
     */
    public function __construct(DataAdapterLoaderInterface $dataAdapterLoader) {
	$this->dataAdapterLoader = $dataAdapterLoader;
    }
    
    /**
     * Perform data fetch
     * 
     * @param string $adapterName
     * @param array $parameters
     * @return mixed
     * @throws \UnexpectedValueException
     * @throws DataBrokerException
     */
    public function execute($adapterName, array $parameters=array()) {
	$dataAdapter = $this->dataAdapterLoader->instantiate($adapterName);
        $adapterParameters = $dataAdapter->getParameters();
        foreach ($adapterParameters as $name => $data) {
            if (is_int($name)) {
                if (!isset($parameters[$data])) {
                    $parameters[$data] = null;
                }
            } else {
                if (!is_array($data)) {
                    throw new \UnexpectedValueException('Parameter definition must be array. Get: ' 
                            . gettype($data));
                }
                if (!isset($parameters[$name])) {
                    if (isset($data['required']) && $data['required'] === true) {
                        throw new DataBrokerException('Missing required parameter "' 
                                . $name . '" for adapter "' . $adapterName . '"', 
                                DataBrokerException::MISSING_REQUIRED_PARAMETER);
                    }
                    if (isset($data['default'])) {
                        $parameters[$name] = $data['default'];
                    }
                }
                
                if (isset($data['type']) && gettype($parameters[$name]) !== $data['type']) {
                    throw new DataBrokerException('Mismatched parameter type.' .
                            ' Expected "' . $data['type'] . '" get "' . gettype($parameters[$name]) 
                            . '" for parameter "' . $name . '" in adapter "' . $adapterName . '"',
                            DataBrokerException::MISMATCH_PARAMETER_TYPE);
                }
                if (isset($data['interface']) && (gettype($parameters[$name]) !== 'object' 
                        || !($parameters[$name] instanceof $data['interface']))) {
                    throw new DataBrokerException('Mismatched parameter interface.' .
                            ' Expected "' . $data['interface'] . '" get "' 
                            . (gettype($parameters[$name]) !== 'object' 
                            ? gettype($parameters[$name]) : get_class($parameters[$name]))
                            . '" for parameter "' . $name . '" in adapter "' . $adapterName . '"',
                            DataBrokerException::MISMATCH_PARAMETER_INTERFACE);
                }
            }
        }
        
        $result = $dataAdapter->fetch($parameters);
        
        return $result;
    }
}