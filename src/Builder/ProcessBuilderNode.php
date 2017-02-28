<?php
namespace EzBpm\Builder;
use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Process\ExclusiveGateway;
use EzBpm\Process\Listener;
use EzBpm\Process\Process;
use EzBpm\Process\Receiver;
use EzBpm\Process\Timer;
use EzBpm\Utils\Verify;

/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:21
 */
class ProcessBuilderNode
{
    public function __construct($process, $curNode){
        $this->process = $process;
        $this->curNode = $curNode;
    }

    /**
     * @return void
     */
    public function end(){
        $this->process->connect($this->curNode, 'end');
    }
    /**
     * @param $name
     * @return $this
     */
    public function __get($name)
    {
        $this->process->connect($this->curNode, $name);
        $this->curNode = $name;
        return $this;
    }

    /**
     * @param string $name
     * @param int $second
     * @return $this
     */
    public function timer($name, $second = null){
        $timer = null;
        if($this->process->hasNode($name)){
            $timer = $this->process->getNode($name);
            $timer instanceof Timer or Verify::fail(new ProcessDefineException("node $name exist but not a timer"));

        }else{
            $second !== null or Verify::fail(new ProcessDefineException("param 'second' is required by timer $name"));
            $this->process->addNodeInstance(new Timer($name, $second));
            $this->process->connect($this->curNode, $name);
        }
        $this->curNode = $name;
        return $this;
    }

    /**
     * @param string $name
     * @param string $event
     * @return $this
     */
    public function listener($name, $event = null){

        if($this->process->hasNode($name)){
            $listener = $this->process->getNode($name);
            $listener instanceof Listener or Verify::fail(new ProcessDefineException("node $name exist but not a listener"));

        }else{
            $event or Verify::fail(new ProcessDefineException("param 'event' is required by listener $name"));
            $this->process->addNodeInstance($name, new Listener($name, $event));
            $this->process->connect($this->curNode, $name);
        }

        $this->curNode = $name;
        return $this;
    }

    /**
     * Exclusive Gateway
     * @param string $name
     * @param callable $elseif
     * @param string $comment
     */
    public function xG($name, callable $elseif=null, $comment = ''){
        $node = null;
        if($this->process->hasNode($name)){
            $node = $this->process->getNode($name);
            $node instanceof ExclusiveGateway or Verify::fail(new ProcessDefineException("node $name exist but not a ExclusiveGateway"));

        }else{
            $node = $this->process->addNodeInstance($name, new ExclusiveGateway());
            $this->process->connect($this->curNode, $name);
        }

        $this->curNode = $node->addCondition($elseif, $comment);
        return $this;
    }

    /**
     * Inclusive Gateway
     * @param string $name
     * @param callable $if
     * @param string $comment
     */
    public function oG($name, callable $if=null, $comment = ''){

    }

    /**
     * Parallel Gateway
     * @param string $name
     */
    public function pG($name){

    }

    /**
     * Event-based Gateway
     * @param string $name
     */
    public function eG($name){

    }

    /**
     * @var Process
     */
    private $process;

    /**
     * @var string
     */
    private $curNode;
}