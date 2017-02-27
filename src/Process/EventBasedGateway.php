<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/23
 * Time: 下午8:47
 */

namespace EzBpm\Process;


use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Utils\SerializableFunc;
use EzBpm\Utils\Verify;

class EventBasedGateway extends Gateway
{

    public function connectTo(ProcessNodeContainer $next){
        //只允许连接事件节点
        $next instanceof EventNode or Verify::fail(
            new ProcessDefineException(
                "EventBasedGateway {$this->getName()} should always connect to event node"
            )
        );
        parent::connectTo($next);

        //set hook
        $next->setPreHandleHook(new SerializableFunc([$this, 'eventHandleHook'], $next));

    }
    public function eventHandleHook(ProcessNodeContainer $next, ){
        $next
    }
    public function handleNext(ProcessContext $context, ProcessEngine $engine)
    {
        //call next nodes
        foreach ($this->toNodes as $nextNode){
            $newContext = $engine->createContext($context);
            $engine->pushTask(new SerializableFunc([$nextNode, 'handle'], $newContext));
        }
    }
}