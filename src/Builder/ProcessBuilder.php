<?php
namespace EzBpm\Builder;
use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Process\Nodes\EventBasedGateway;
use EzBpm\Process\Nodes\ExclusiveForkGateway;
use EzBpm\Process\Nodes\ExclusiveJoinGateway;
use EzBpm\Process\Nodes\InclusiveForkGateway;
use EzBpm\Process\Nodes\InclusiveJoinGateway;
use EzBpm\Process\Nodes\Listener;
use EzBpm\Process\Nodes\ParallelForkGateway;
use EzBpm\Process\Nodes\ParallelJoinGateway;
use EzBpm\Process\Nodes\ProcessTaskContainer;
use EzBpm\Process\Nodes\Timer;
use EzBpm\Process\Process;
use EzBpm\Utils\Verify;

/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:18
 */
class ProcessBuilder
{
    /**
     * ProcessBuilder constructor.
     * @param Process $process
     */
    public function __construct(Process $process){
        $this->process = $process?:new Process();
        $this->begin = new BeginConnector($this->process, 'begin');
    }

    /**
     * @param string $name
     * @param string $taskClass 不是新建时$taskClass应为空
     * @return Connector
     */
    public function task($name, $taskClass=null){
        $node = null;
        if(!$this->process->hasNode($name)){
            $node = $this->process->addNodeInstance($name, new ProcessTaskContainer($name, $taskClass));
        }else{
            $node = $this->process->getNode($name);
            $node instanceof ProcessTaskContainer
                or Verify::fail(new ProcessDefineException("node $name exist but not a ProcessTaskContainer"));
        }
        return new Connector($this->process, $node);
    }
    /**
     * @param $name
     * @return Connector
     */
    public function __get($name){
        $this->process->hasNode($name) or Verify::fail(new ProcessDefineException("node '$name' not exist"));
        return new Connector($this->process, $name);
    }


    /**
     * @param $name
     * @param null $second
     * @param string $comment
     * @return Connector
     */
    public function timer($name, $second=null, $comment='')
    {
        $node = null;
        if($this->process->hasNode($name)){
            $node = $this->process->getNode($name);
        }else{
            $second !== null or Verify::fail(new ProcessDefineException("param 'second' of timer $name not set"));
            $node = $this->process->addNodeInstance($name, new Timer($name, $second));
            $node instanceof Timer
                or Verify::fail(new ProcessDefineException("node $name exist but not a Timer"));
        }
        return new Connector($this->process, $node);
    }

    /**
     * @param $name
     * @param null $event
     * @param string $comment
     * @return Connector
     */
    public function listener($name, $event=null, $comment='')
    {
        $node = null;
        if($this->process->hasNode($name)){
            $node = $this->process->getNode($name);
        }else{
            $event !== null or Verify::fail(new ProcessDefineException("param 'event' of listener $name not set"));
            $node = $this->process->addNodeInstance($name, new Listener($name, $event));
            $node instanceof Listener
                or Verify::fail(new ProcessDefineException("node $name exist but not a Listener"));
        }
        return new Connector($this->process, $node);
    }

    /**
     * Exclusive Fork Gateway
     * @param string $name
     * @param callable $elseif
     * @param string $comment
     */
    public function xFork($name, $comment = ''){
        $node = $this->addNode($name, ExclusiveForkGateway::class);
        return new ExclusiveForkGatewayConnector($this->process, $node);
    }

    /**
     * Exclusive Join Gateway
     * @param string $name
     * @param callable $elseif
     * @param string $comment
     */
    public function xJoin($name, $comment = ''){
        $node = $this->addNode($name, ExclusiveJoinGateway::class);
        return new Connector($this->process, $node);
    }

    /**
     * Inclusive Fork Gateway
     * @param string $name
     * @param callable $if
     * @param string $comment
     */
    public function oFork($name, $comment = ''){
        $node = $this->addNode($name, InclusiveForkGateway::class);
        return new InclusiveForkGatewayConnector($this->process, $node);
    }

    /**
     * Inclusive Join Gateway
     * @param string $name
     * @param callable $elseif
     * @param string $comment
     */
    public function oJoin($name, $comment = ''){
        $node = $this->addNode($name, InclusiveJoinGateway::class);
        return new Connector($this->process, $node);
    }

    /**
     * Parallel Gateway
     * @param string $name
     */
    public function pFork($name, $comment=''){
        $node = $this->addNode($name, ParallelForkGateway::class);
        return new Connector($this->process, $node);
    }

    /**
     * Parallel Gateway
     * @param string $name
     */
    public function pJoin($name, $comment=''){
        $node = $this->addNode($name, ParallelJoinGateway::class);
        return new Connector($this->process, $node);
    }

    /**
     * Event-based Gateway
     * @param string $name
     */
    public function eFork($name, $comment=''){
        $node = $this->addNode($name, EventBasedGateway::class);
        return new EventBasedForkGatewayConnector($this->process, $node);
    }

    private function addNode($name, $class){
        $node = null;
        if($this->process->hasNode($name)){
            $node = $this->process->getNode($name);
            $node instanceof $class
                or Verify::fail(new ProcessDefineException("node $name exist but not a $class"));
        }else{
            $node = $this->process->addNodeInstance($name, new $class($name));
        }
        return $node;
    }

    /**
     * @var Process
     */
    private $process;

    /**
     * @var BeginConnector;
     */
    public $begin;
}