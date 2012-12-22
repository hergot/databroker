<?php

namespace hergot\databroker\DataAdapter;

class DataAdapterCallbackLoader implements DataAdapterLoaderInterface {
    
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback) {
        $this->callback = $callback;
    }
    
    /**
     * Instantiate data adapter
     * 
     * @param string $name
     * @return DataAdapterInterface
     * @throws \UnexpectedValueException
     */
    public function instantiate($name) {
        $dataAdapterInstance = call_user_func($this->callback, $name);
        if (gettype($dataAdapterInstance) !== 'object') {
            throw new \UnexpectedValueException('Unexpected data adapter loader result. Expected object get "' . gettype($dataAdapterInstance) . '" for adapter name "' . $name . '"');
        }
        if (!$dataAdapterInstance instanceof DataAdapterInterface) {
            throw new \UnexpectedValueException('"' . get_class($dataAdapterInstance) . '" not implements "\hergot\DataAdapter\DataAdapterInterface"');
        }
        return $dataAdapterInstance;
    }
}