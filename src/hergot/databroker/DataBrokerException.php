<?php

namespace hergot\databroker;

class DataBrokerException extends \RuntimeException {
    const MISSING_REQUIRED_PARAMETER = 1;
    const INVALID_PARAMETER_VALUE = 2;
    const CANNOT_INITIALIZE_DATA_ADAPTER = 3;
}