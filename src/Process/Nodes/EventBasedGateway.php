<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/23
 * Time: 下午8:47
 */

namespace EzBpm\Process\Nodes;


use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Process\Traits\SingleInput;
use EzBpm\Utils\SerializableFunc;
use EzBpm\Utils\Verify;

class EventBasedGateway extends Gateway
{
    public function connectTo(ProcessNodeContainer $next){
        //只允许连接事件节点
        $next instanceof IntermediateEventNode or Verify::fail(
            new ProcessDefineException(
                "EventBasedGateway {$this->getName()} should always connect to IntermediateEventNode"
            )
        );
        parent::connectTo($next);

        //set hook
        $next->setPreHandleHook(new SerializableFunc([$this, 'hookPostHandle'], $next));

    }

    public function hookPostHandle(ProcessNodeContainer $hookedNode,
                                   ProcessEngine $engine,
                                   ProcessContext $context,
                                   SerializableFunc $next
                                    ){
        //一旦一个事件触发, 关闭其他链路
        $token = $context->getToken()->getParent();
        foreach ($token->getChildren() as $child){
            if($child !== $context->getToken()){
                $child->disable();
            }
        }
        $next();
    }

    public function handleNext(ProcessEngine $engine, ProcessContext $context)
    {
        //call next nodes
        foreach ($this->outputs as $output){
            $newContext = $engine->createContext($context);
            $engine->pushTask(new SerializableFunc([$output, 'handle'], $newContext));
        }
    }

    use SingleInput;
}