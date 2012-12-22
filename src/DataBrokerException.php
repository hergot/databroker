<?php

namespace hergot\databroker;

class DataBrokerException extends \RuntimeException {
    const MISSING_REQUIRED_PARAMETER = 1;
    const MISMATCH_PARAMETER_TYPE = 2;
    const MISMATCH_PARAMETER_INTERFACE = 3;
}