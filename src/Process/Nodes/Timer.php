<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/23
 * Time: 下午9:36
 */

namespace EzBpm\Process\Nodes;


use EzBpm\Process\ProcessContext;
use EzBpm\Process\ProcessEngine;
use EzBpm\Process\Traits\SingleInput;
use EzBpm\Process\Traits\SingleOutput;
use EzBpm\Utils\Verify;

class Timer extends IntermediateEventNode
{

    public function __construct($nodeName, $second){
        $this->second = $second;
        parent::__construct($nodeName);
    }

    public function handle(ProcessContext $context, ProcessEngine $engine){
        count($this->outputs) or Verify::fail("no output connected from timer '{$this->getName()}'");
        $engine->delayTask($this->second, $this->outputs[0],'handleTimeout', $context);
    }
    public function handleTimeout(ProcessContext $context, ProcessEngine $engine){
        parent::handle($context, $engine);
    }

    use SingleInput;
    use SingleOutput;
    /**
     * @var int
     */
    private $second;
}