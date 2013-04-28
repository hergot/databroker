<?php

namespace hergot\databroker\Plugin\Monitor;

class FileMonitorRecordWriter implements MonitorRecordWriterInterface {
    
    /**
     * @var string
     */
    private $folder;
    
    /**
     * Initialize writer
     * 
     * @param string $folder
     * @throws \InvalidArgumentException
     */
    public function __construct($folder)
    {
        if (!file_exists($folder) || !is_dir($folder) || !is_writable($folder)) {
            throw new \InvalidArgumentException('Folder "' . $folder . '" does not exists or is not writeable');
        }
        $this->folder = $folder;
    }
    
    /**
     * Write montor record as a file
     * 
     * @param \hergot\databroker\Plugin\Monitor\MonitorRecord $record
     */
    public function write(MonitorRecord $record)
    {
        $id = microtime(true);
        $filename = $this->folder . DIRECTORY_SEPARATOR . $id;
        file_put_contents($filename, serialize($record));
    }    
}