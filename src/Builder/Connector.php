<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/3
 * Time: 下午1:23
 */

namespace EzBpm\Builder;


use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Process\Nodes\EndEvent;
use EzBpm\Process\Nodes\Timer;
use EzBpm\Process\Process;
use EzBpm\Process\ProcessNodeContainer;
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
     * @param string|ProcessNodeContainer $currentNode
     */
    public function __construct(Process $process, $currentNode){
        $this->process = $process;
        $this->currentNode = $currentNode;
    }

    /**
     * @param $next
     * @return ActivityConnector
     */
    public function __get($next)
    {
        if (!$this->process->hasNode($next)){
            if($next == 'end'){
                $this->process->addNodeInstance($next, new EndEvent($next));
            }else{
                Verify::fail(new ProcessDefineException("attempt to connect to non exist node $next"));
            }
        }
        $this->process->connect($this->currentNode, $next);
        $this->currentNode = $next;
        return $this;
    }

    /**
     * @param string $name
     * @param string $activityClass
     * @return ActivityConnector
     */
    public function activity($name, $activityClass=null)
    {
        if(!$this->process->hasNode($name)){
            $this->process->addNode($name, $activityClass);
        }
        $this->process->connect($this->currentNode, $name);
        $this->currentNode = $name;
        return $this;
    }

    /**
     * @param $name
     * @param null $second
     * @return $this
     */
    public function timer($name, $second=null)
    {
        $timer = null;
        if($this->process->hasNode($name)){
            $timer = $this->process->getNode($name);
            $timer instanceof Timer or Verify::fail(new ProcessDefineException("node $name exist but not a timer"));

        }else{
            $second !== null or Verify::fail(new ProcessDefineException("param 'second' is required by timer $name"));
            $this->process->addNodeInstance($name, new Timer($name, $second));
            $this->process->connect($this->currentNode, $name);
        }
        $this->currentNode = $name;
        return $this;
    }

    /**
     * @var Process
     */
    protected $process;
    /**
     * @var string|ProcessNodeContainer
     */
    protected $currentNode;

}