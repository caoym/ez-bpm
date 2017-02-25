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
        while($todo = array_shift($this->queue)){
            $todo($this);
        }
    }
    public function listen(){

    }
    /**
     * @param SerializableFunc $task
     * @return void
     */
    public function pushTask(SerializableFunc $task){
        $this->queue[] = $task;
    }

    /**
     * 延迟执行
     * @param SerializableFunc $task
     * @param int $seconds
     */
    public function delayTask(SerializableFunc $task, $seconds){

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