<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:46
 */

namespace EzBpm\Process;


use EzBpm\Utils\SerializableFunc;

class ProcessEngine
{
    public function run(){
        while(count($this->queue)){
            $todo = $this->queue[0];
            $todo($this);
            array_shift($this->queue);
        }
    }
    public function listen(){

    }

    public function startReceiver($event, ProcessNodeContainer $receiver){

    }
    public function startTimer($name, $interval, ProcessNodeContainer $receiver){

    }
    public function setPreDispatchHook($hookedNode, SerializableFunc $hook){

    }
    public function setPostDispatchHook($hookedNode, SerializableFunc $hook){

    }
    /**
     * @param SerializableFunc $task
     * @return void
     */
    public function pushTask(callable $task){
        $this->queue[] = $task;
    }

    /**
     * 延迟执行
     * @param ProcessNodeContainer $task
     * @param int $seconds
     */
    public function delayTask(ProcessNodeContainer $task, $seconds){

    }

    /**
     * @param ProcessContext|null $context
     * @return ProcessContext
     */
    public function createContext(ProcessContext $context = null){

    }
    /**
     * 执行队列
     * @var SerializableFunc[]
     */
    private $queue=[];
}