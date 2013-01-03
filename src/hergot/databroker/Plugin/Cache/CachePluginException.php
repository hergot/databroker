<?php

namespace hergot\databroker\Plugin\Cache;

class CachePluginException extends \RuntimeException {
    const MISSING_BACKEND = 1;
    const MISSING_LIFETIME = 2;
}