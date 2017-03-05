<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/5
 * Time: 上午11:26
 */

namespace EzBpm\Builder;


use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Process\Nodes\ConnectedAble;
use EzBpm\Process\Nodes\ExclusiveForkGateway;
use EzBpm\Process\Nodes\InclusiveForkGateway;
use EzBpm\Process\Nodes\Timer;
use EzBpm\Process\Process;
use EzBpm\Utils\Verify;

/**
 * Class EventBasedForkGatewayConnector
 * @package EzBpm\Builder
 */
class EventBasedForkGatewayConnector
{
    public  function __construct(Process $process, ConnectedAble $currentNode)
    {
        $this->process = $process;
        $this->currentNode = $currentNode;
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
            $this->process->connect($this->currentNode, $node);
        }
        $this->currentNode = $node;
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
            $this->process->connect($this->currentNode, $node);
        }
        return new Connector($this->process, $node);
    }

    /**
     * @var Process
     */
    private $process;
    /**
     * @var ConnectedAble
     */
    private $currentNode;
}