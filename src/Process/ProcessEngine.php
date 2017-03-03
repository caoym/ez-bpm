<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:46
 */

namespace EzBpm\Process;


use EzBpm\Process\Nodes\ProcessNodeContainer;
use EzBpm\Utils\SerializableFunc;

class ProcessEngine
{
    public function run(){
        while(count($this->queue)){
            list($node, $context) = $this->queue[0];
            $node->handle($context, $this);
            array_shift($this->queue);
        }
    }
    public function listen(){

    }

    public function startReceiver($event, ProcessNodeContainer $receiver){

    }
    public function startTimer($interval, ProcessNodeContainer $receiver){

    }
    public function setPreDispatchHook($hookedNode, SerializableFunc $hook){

    }
    public function setPostDispatchHook($hookedNode, SerializableFunc $hook){

    }

    /**
     * @return void
     */
    public function pushTask(ProcessNodeContainer $node, ProcessContext $context=null){
        if(!$context){
            $context = $this->createContext();
        }
        $this->queue[] = [$node, $context];
    }

    /**
     * 延迟执行
     * @param ProcessNodeContainer $task
     * @param int $seconds
     */
    public function delayTask($seconds, ProcessNodeContainer $node, ProcessContext $context=null){

    }

    /**
     * @param ProcessContext|null $context
     * @return ProcessContext
     */
    public function createContext(ProcessContext $context = null){
        return new ProcessContext();
    }
    /**
     * 执行队列
     * @var SerializableFunc[]
     */
    private $queue=[];
}