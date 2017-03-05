<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/3
 * Time: 下午1:23
 */

namespace EzBpm\Builder;


use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Process\Nodes\ConnectedAble;
use EzBpm\Process\Nodes\EndEvent;
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
 * Class Connector
 * @package EzBpm\Builder
 *
 * @property $end 用于连接end事件
 */
class Connector
{
    /**
     * Connector constructor.
     * @param Process $process
     * @param string|ConnectedAble $currentNode
     */
    public function __construct(Process $process, $currentNode){
        $this->process = $process;
        if(is_string($currentNode)){
            $this->currentNode = $process->getNode($currentNode);
        }else{
            $currentNode instanceof ConnectedAble
                or Verify::fail(new \InvalidArgumentException('$currentNode not a ProcessTaskContainer'));
            $this->currentNode = $currentNode;
        }
    }

    /**
     * @param $next
     * @return TaskConnector
     */
    public function __get($next)
    {
        $node = null;
        if ($this->process->hasNode($next)){
            $node = $this->process->getNode($next);
        }else{
            if($next == 'end'){
                $node = $this->process->addNodeInstance($next, new EndEvent($next));
            }else{
                Verify::fail(new ProcessDefineException("attempt to connect to non exist node $next"));
            }
            $this->process->connect($this->currentNode, $node);
        }
        $this->currentNode = $node;
        return $this;
    }

    /**
     * @param string $name
     * @param string $taskClass
     * @return TaskConnector
     */
    public function task($name, $taskClass=null, $comment='')
    {
        $node = null;
        if($this->process->hasNode($name)){
            $node = $this->process->getNode($name);
            $node instanceof ProcessTaskContainer
                or Verify::fail(new ProcessDefineException("node $name exist but not a ProcessTaskContainer"));
        }else{
            $node = $this->process->addNodeInstance($name, new ProcessTaskContainer($name, $taskClass));
            $this->process->connect($this->currentNode, $node);
        }
        $this->currentNode = $node;
        return $this;
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
            $node instanceof Timer
                or Verify::fail(new ProcessDefineException("node $name exist but not a Timer"));
        }else{
            $second !== null or Verify::fail(new ProcessDefineException("param 'second' of timer $name not set"));
            $node = $this->process->addNodeInstance($name, new Timer($name, $second));
            $this->process->connect($this->currentNode, $node);
        }
        $this->currentNode = $node;
        return $this;
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
            $node instanceof Listener
                or Verify::fail(new ProcessDefineException("node $name exist but not a Listener"));
        }else{
            $event !== null or Verify::fail(new ProcessDefineException("param 'event' of listener $name not set"));
            $node = $this->process->addNodeInstance($name, new Listener($name, $event));
            $this->process->connect($this->currentNode, $node);
        }
        $this->currentNode = $node;
        return $this;
    }

    /**
     * Exclusive Fork Gateway
     * @param string $name
     * @param callable $elseif
     * @param string $comment
     */
    public function xFork($name, $comment = ''){
        $this->connect($name, ExclusiveForkGateway::class);
        return new ExclusiveForkGatewayConnector($this->process, $this->currentNode);
    }

    /**
     * Exclusive Join Gateway
     * @param string $name
     * @param callable $elseif
     * @param string $comment
     */
    public function xJoin($name, $comment = ''){
        $this->connect($name, ExclusiveJoinGateway::class);
        return $this;
    }

    /**
     * Inclusive Fork Gateway
     * @param string $name
     * @param callable $if
     * @param string $comment
     */
    public function oFork($name, $comment = ''){
        $this->connect($name, InclusiveForkGateway::class);
        return new InclusiveForkGatewayConnector($this->process, $this->currentNode);
    }

    /**
     * Inclusive Join Gateway
     * @param string $name
     * @param callable $elseif
     * @param string $comment
     */
    public function oJoin($name, $comment = ''){
        $this->connect($name, InclusiveJoinGateway::class);
        return $this;
    }

    /**
     * Parallel Gateway
     * @param string $name
     */
    public function pFork($name, $comment=''){
        $this->connect($name, ParallelForkGateway::class);
        return $this;
    }

    /**
     * Parallel Gateway
     * @param string $name
     */
    public function pJoin($name, $comment=''){
        $this->connect($name, ParallelJoinGateway::class);
        return $this;
    }

    /**
     * Event-based Gateway
     * @param string $name
     */
    public function eFork($name, $comment=''){
        $this->connect($name, EventBasedGateway::class);
        return new EventBasedForkGatewayConnector($this->process, $this->currentNode);
    }

    private function connect($name, $class){
        $node = null;
        if($this->process->hasNode($name)){
            $node = $this->process->getNode($name);
            $node instanceof $class
            or Verify::fail(new ProcessDefineException("node $name exist but not a $class"));
        }else{
            $node = $this->process->addNodeInstance($name, new $class($name));
            $this->process->connect($this->currentNode, $name);
        }
        $this->currentNode = $node;
    }
    /**
     * @var Process
     */
    protected $process;
    /**
     * @var string|ConnectedAble
     */
    protected $currentNode;

}