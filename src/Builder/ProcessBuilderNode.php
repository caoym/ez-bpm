<?php
namespace EzBpm\Builder;
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
     * @param $second
     * @return $this
     */
    public function timer($second){
        $this->curNode = $this->process->addTimer($this->curNode, $second);
        return $this;
    }

    /**
     * @param $event
     * @return $this
     */
    public function receiver($event){
        $this->curNode = $this->process->addReceiver($this->curNode, $event);
        return $this;
    }

    /**
     * Exclusive Gateway
     * @param string $name
     * @param callable $elseif
     * @param string $comment
     */
    public function xG($name, callable $elseif=null, $comment = ''){
        $node = new ExclusiveGateway();
        $this->process->addGateway($name, $node);
        $node = $this->process->getNode($this->curNode);
        if ($node instanceof ExclusiveGateway){
            $this->curNode = $node->addCondition($elseif, $comment);
        }
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