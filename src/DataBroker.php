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
        
        // Check parameters. Adapter can expect some required parameters 
        // or some with default values when no present
        $adapterParameters = $dataAdapter->getParameters();
        foreach ($adapterParameters as $name => $specification) {
            if (is_int($name)) { // parameter without any restriction or specification
                if (!isset($parameters[$specification])) {
                    $parameters[$specification] = null; 
                }
            } else { // parameter with some restriction or specification
                if (!is_array($specification)) { 
                    throw new \UnexpectedValueException('Parameter specification must be array. Get: ' 
                            . gettype($specification));
                }
                if (!isset($parameters[$name])) {
                    if (isset($specification['required']) && $specification['required'] === true) {
                        throw new DataBrokerException('Missing required parameter "' 
                                . $name . '" for adapter "' . $adapterName . '"', 
                                DataBrokerException::MISSING_REQUIRED_PARAMETER);
                    }
                    if (isset($specification['default'])) {
                        $parameters[$name] = $specification['default'];
                    }
                }
                
                if (isset($specification['type']) && gettype($parameters[$name]) !== $specification['type']) {
                    throw new DataBrokerException('Mismatched parameter type.' .
                            ' Expected "' . $specification['type'] . '" get "' . gettype($parameters[$name]) 
                            . '" for parameter "' . $name . '" in adapter "' . $adapterName . '"',
                            DataBrokerException::MISMATCH_PARAMETER_TYPE);
                }
                if (isset($specification['interface']) && (gettype($parameters[$name]) !== 'object' 
                        || !($parameters[$name] instanceof $specification['interface']))) {
                    throw new DataBrokerException('Mismatched parameter interface.' .
                            ' Expected "' . $specification['interface'] . '" get "' 
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