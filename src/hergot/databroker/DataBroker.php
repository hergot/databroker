<?php

namespace hergot\databroker;

use hergot\databroker\DataAdapter\DataAdapterLoaderInterface;

class DataBroker {

    /**
     * @var DataAdapterLoaderInterface
     */
    private $dataAdapterLoader;

    /**
     * @var Plugin\PluginInterface[] 
     */
    private $plugins;

    /**
     * @param $dataAdapterLoader DataAdapterLoaderInterface
     */
    public function __construct(DataAdapterLoaderInterface $dataAdapterLoader) {
        $this->dataAdapterLoader = $dataAdapterLoader;
        $this->plugins = array();
    }

    /**
     * Insert plugin
     * 
     * @param \hergot\databroker\Plugin\PluginInterface $plugin
     */
    public function addPlugin(Plugin\PluginInterface $plugin) {
        $this->plugins[] = $plugin;
    }

    /**
     * Perform data fetch
     * 
     * @param string $adapterName
     * @param array $parameters
     * @return mixed
     * @throws \UnexpectedValueException
     * @throws DataBrokerException
     * @throws Plugin\BubbleExceptionInterface
     */
    public function execute($adapterName, array $parameters = array()) {
        try {
            $dataAdapter = $this->dataAdapterLoader->instantiate($adapterName);
        } catch (\Exception $e) {
            throw new DataBrokerException('Cannot initialize data adapter "'
                    . $adapterName . '"', DataBrokerException::CANNOT_INITIALIZE_DATA_ADAPTER, $e);
        }

        // Check parameters. Adapter can expect some required parameters 
        // or some with default values when no present
        $parameters = $this->checkParameters($dataAdapter, $parameters);

        $result = null;
        foreach ($this->plugins as $plugin) {
            $result = $plugin->runBeforeExecute($dataAdapter, $parameters, $result);
        }

        $exception = null;
        if ($result === null) {
            try {
                $result = $dataAdapter->fetch($parameters);
            } catch (\Exception $e) {
                $result = null;
                if ($e instanceof Plugin\ValueExceptionInterface) {
                    /* @var $e Plugin\ValueExceptionInterface */
                    $result = $e->getValue();
                }
                $exception = $e;
            }
        }

        $reversePlugins = array_reverse($this->plugins);
        foreach ($reversePlugins as $plugin) {
            $result = $plugin->runAfterExecute($dataAdapter, $parameters, $result, $exception);
        }

        if ($exception instanceof Plugin\BubbleExceptionInterface) {
            throw $exception;
        }

        return $result;
    }

    /**
     * Check parameters if they are valid for data adapter
     * 
     * @param \hergot\databroker\DataAdapter\DataAdapterInterface $dataAdapter
     * @param array $parameters
     * @return array
     * @throws \UnexpectedValueException
     * @throws DataBrokerException
     */
    private function checkParameters(DataAdapter\DataAdapterInterface $dataAdapter, array $parameters) {
        $adapterParameters = $dataAdapter->getParameters();
        $adapterName = get_class($dataAdapter);
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
                            . (gettype($parameters[$name]) !== 'object' ? gettype($parameters[$name]) : get_class($parameters[$name]))
                            . '" for parameter "' . $name . '" in adapter "' . $adapterName . '"',
                            DataBrokerException::MISMATCH_PARAMETER_INTERFACE);
                }
            }
        }
        return $parameters;
    }

}