<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/23
 * Time: 下午9:37
 */

namespace EzBpm\Process\Nodes;


use EzBpm\Process\ProcessContext;
use EzBpm\Process\ProcessEngine;
use EzBpm\Process\Traits\SingleInput;
use EzBpm\Process\Traits\SingleOutput;

class Listener extends IntermediateEventNode
{
    public function __construct($nodeName, $event){
        $this->event = $event;
        parent::__construct($nodeName);
    }

    public function handle(ProcessContext $context, ProcessEngine $engine){
        count($this->outputs) or Verify::fail("no output connected from Listener '{$this->getName()}'");
        $engine->catchEvent($this->event, $this->outputs[0]->getName(), 'handleEvent',$context);
    }
    public function handleEvent(ProcessContext $context, ProcessEngine $engine){
        parent::handle($context, $engine);
    }
    use SingleInput;
    use SingleOutput;
    /**
     * @var string
     */
    private $event;
}