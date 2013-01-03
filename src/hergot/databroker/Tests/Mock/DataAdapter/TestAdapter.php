<?php

namespace hergot\databroker\Tests\Mock\DataAdapter;

class TestAdapter implements \hergot\databroker\DataAdapter\DataAdapterInterface {
    public function fetch(array $parameters = array()) {
        return null;
    }

    public function getParameters() {
        return array();
    }
}

