<?php
namespace EzBpm;
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
    public function timeout($second){
        $this->curNode = $this->process->addTimer($this->curNode, $second);
        return $this;
    }

    /**
     * @param $event
     * @return $this
     */
    public function received($event){
        $this->curNode = $this->process->addReceiver($this->curNode, $event);
        return $this;
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