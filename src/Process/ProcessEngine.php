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
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    public function startNewProcess(){
        $this->pushTask('begin', 'handle', null);
        while (count($this->queue)) {
            list($node, $invokeMethod, $context) = $this->queue[0];
            $node->{$invokeMethod}($context, $this);
            array_shift($this->queue);
        }

    }

    public function listen()
    {

    }

    public function catchEvent($event,
                               $nodeName,
                               $invokeMethod,
                               ProcessContext $context = null)
    {

    }

    public function delayTask($event,
                              $nodeName,
                              $invokeMethod,
                              ProcessContext $context = null)
    {

    }

    /**
     * @return void
     */
    public function pushTask($nodeName, $invokeMethod, ProcessContext $context = null)
    {
        if (!$context) {
            $context = $this->createContext();
        }
        $this->queue[] = [$nodeName, $invokeMethod, $context];
    }

    /**
     * @param ProcessContext|null $context
     * @return ProcessContext
     */
    public function createContext(ProcessContext $context = null)
    {
        return new ProcessContext();
    }

    /**
     * 执行队列
     * [$event,$nodeName,$invokeMethod,ProcessContext][]
     * @var array
     */
    private $queue = [];

    private $timers = [];

    public $listeners = [];


    /**
     * @var Process
     */
    public $process;
}