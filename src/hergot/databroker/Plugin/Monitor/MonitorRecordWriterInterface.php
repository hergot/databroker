<?php

namespace hergot\databroker\Plugin\Monitor;

interface MonitorRecordWriterInterface {
    
    /**
     * Persist record
     * 
     * @param \hergot\databroker\Plugin\Monitor\MonitorRecord $record
     */
    public function write(MonitorRecord $record);
}