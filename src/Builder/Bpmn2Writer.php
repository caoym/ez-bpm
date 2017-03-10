<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/9
 * Time: 下午9:12
 */

namespace EzBpm\Builder;


use EzBpm\Process\Process;

class Bpmn2Writer
{
    /**
     *
     * @param Process $process
     * @return string
     */
    public function write(Process $process){
        $header = <<<EOD
<?xml version="1.0" encoding="UTF-8"?>
<definitions 
    xmlns="http://www.omg.org/spec/BPMN/20100524/MODEL" 
    xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI" 
    xmlns:omgdi="http://www.omg.org/spec/DD/20100524/DI" 
    xmlns:omgdc="http://www.omg.org/spec/DD/20100524/DC" 
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
    id="sid-38422fae-e03e-43a3-bef4-bd33b32041b2" 
    targetNamespace="http://bpmn.io/bpmn" 
    exporter="http://bpmn.io" exporterVersion="0.10.1">
    
</definitions>
EOD;

        $xml =  new \SimpleXMLElement("<?xml version='1.0' standalone='yes'?></xml>");
        $process = $xml->addChild('process');
        $process->addAttribute('id', 'Process_1');
        $process->addAttribute('isExecutable', 'false');



        return $xml->asXML();
    }
}