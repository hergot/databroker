<?php

namespace hergot\databroker\DataAdapter;

interface DataAdapterLoaderInterface {
    /**
     * Instantiate data adapter
     * 
     * @param string $name
     * @return DataAdapterInterface
     */
    public function instantiate($name);
}