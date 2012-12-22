<?php

namespace hergot\databroker\test\mock\DataAdapter;

class TestAdapter implements \hergot\databroker\DataAdapter\DataAdapterInterface {
    public function fetch(array $parameters = array()) {
        return null;
    }

    public function getParameters() {
        return array();
    }
}

