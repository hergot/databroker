<?php

namespace hergot\databroker\Plugin\Cache\Backend\Memcached;

class MemcachedServer {

    /**
     * @var string
     */
    private $host;

    /**
     * @var integer
     */
    private $port;

    /**
     * @var integer
     */
    private $weight;

    /**
     * @param string $host
     * @param integer $port
     * @param integer $weight
     * @throws \InvalidArgumentException
     */
    function __construct($host, $port=11211, $weight=0) {
        $this->host = $host;
        if (!is_int($port) || $port < 0 || $port > 65535) {
            throw new \InvalidArgumentException('Port must be in range 0 - 65535');
        }
        $this->port = (int)$port;
        if (!is_int($weight) || $weight < 0) {
            throw new \InvalidArgumentException('Weight must be integer greather than or equal to 0');
        }
        $this->weight = (int)$weight;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * @return integer
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @param integer $port
     * @throws \InvalidArgumentException
     */
    public function setPort($port) {
        if (!is_int($port) || $port < 0 || $port > 65535) {
            throw new \InvalidArgumentException('Port must be in range 0 - 65535');
        }
        $this->port = (int)$port;
    }

    /**
     * @return integer
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * @param integer $weight
     * @throws \InvalidArgumentException
     */
    public function setWeight($weight) {
        if (!is_int($weight) || $weight < 0) {
            throw new \InvalidArgumentException('Weight must be integer greather than or equal to 0');
        }
        $this->weight = (int)$weight;
    }

}