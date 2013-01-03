<?php

namespace hergot\databroker\Plugin\Cache\Backend;

use hergot\databroker\Plugin\Cache\CacheBackendInterface;

class FileCacheBackend implements CacheBackendInterface {

    /**
     * @var string
     */
    private $directory;
    
    /**
     * @param string $directory
     */
    public function __construct($directory) {
        $this->directory = $directory;
        if (!file_exists($this->directory)) {
            mkdir($this->directory);
            chmod($this->directory, 0777);
        }
    }

    /**
     * @param string $name
     */
    public function invalidate($name) {
        $filename = $this->getFilename($name);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
    /**
     * @param string $name
     * @param mixed $defaultValue
     * @return string
     */
    public function read($name, $defaultValue = null) {
        $filename = $this->getFilename($name);
        if (!file_exists($filename)) {
            return $defaultValue;
        }
        $content = file_get_contents($filename);
        $lifetime = unpack("N", substr($content, 0, 4))[1];
        if ($lifetime < time()) {
            unlink($filename);
            return $defaultValue;
        }
        return substr($content, 4);
    }

    /**
     * @param string $name
     * @param string $value
     * @param integer $lifeTime
     * @throws \InvalidArgumentException
     */
    public function write($name, $value, $lifeTime) {
        if (!is_int($lifeTime) || $lifeTime < 0) {
            throw new \InvalidArgumentException('Lifetime must be integer greater than or equal to 0. Get "' . $lifeTime . '"');
        }
        file_put_contents($this->getFilename($name), pack("N", $lifeTime + time()) . $value);
    }

    /**
     * @param string $name
     * @return string
     */
    private function getFilename($name) {
        return $this->directory . DIRECTORY_SEPARATOR . $name;
    }
}