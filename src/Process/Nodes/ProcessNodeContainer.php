<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/22
 * Time: 上午12:01
 */

namespace EzBpm\Process\Nodes;


use EzBpm\Process\ProcessContext;
use EzBpm\Process\ProcessEngine;
use EzBpm\Utils\SerializableFunc;
use EzBpm\Utils\Verify;

class ProcessNodeContainer
{
    /**
     * ProcessNodeContainer constructor.
     * @param string $nodeName node name
     * @param string $nodeClass class name of the node
     */
    public function __construct($nodeName, $nodeClass){
        $this->nodeName = $nodeName;
        $this->nodeClass = $nodeClass;
    }

    public function preConnect(ProcessNodeContainer $from){

    }

    public function postConnect(ProcessNodeContainer $from){
        $this->inputs[] = $from;
    }

    public function setHook(SerializableFunc $hook){
        $this->hook = $hook;
    }
    public function getHook(){
        return $this->hook;
    }
    public function connectTo(ProcessNodeContainer $next){
        $next->preConnect($this);
        $this->outputs[] = $next;
        $next->postConnect($this);
    }

    public function exceptionTo($exception, ProcessNodeContainer $next){
        $next->preConnect($this);
        $this->exceptionTo[] = [$exception, $next];
        $next->postConnect($this);
    }

    public function handler(ProcessContext $context, ProcessEngine $engine){
        if($this->hook){
            $this->hook($context, $engine, [$this, 'handleImpl']);
        }else{
            $this->handleImpl($context, $engine);
        }
    }
    public function handleImpl(ProcessContext $context, ProcessEngine $engine){
        // init exceptionTo handler
        try{
            //call
            $node = new $this->nodeClass;
            $this->handleInternal($node, $context, $engine);

        }catch (\Exception $e){
            $context->setLastException($this->name, $e);
            $handled = false;
            foreach ($this->exceptionTo as $to){
                list($key, $nextNode) = $to;
                if ($e instanceof $key){
                    $handled = true;
                    $engine->pushTask($nextNode->getName(), 'handle', $context);
                }
            }
            if(!$handled){
                throw $e;
            }else{
                return;
            }
        }
        $this->handleNext($context, $engine);

    }

    protected function handleInternal(ProcessNode $node, ProcessContext $context, ProcessEngine $engine){

        $node->handle($context);
    }

    protected function handleNext(ProcessContext $context, ProcessEngine $engine){
        //call next nodes
        foreach ($this->outputs as $nextNode){
            $engine->pushTask($nextNode, $context);
        }
    }
    /**
     * @return mixed
     */
    public function getName(){
        return $this->nodeName;
    }

    /**
     * @param string $nodeName
     */
    public function setName($nodeName)
    {
        $this->nodeName = $nodeName;
    }

    public function getClass(){
        return $this->nodeClass;
    }

    /**
     * from nodes
     * @var ProcessNodeContainer[]
     */
    protected $inputs = [];
    /**
     * @var ProcessNodeContainer[]
     */
    protected $outputs=[];

//    /**
//     * @var ProcessNodeContainer[]
//     */
//    private $eventTo;
//
//    /**
//     * @var ProcessNodeContainer[]
//     */
//    private $timerTo;

    /**
     * @var ProcessNodeContainer[]
     */
    protected $exceptionTo;

    private $name;

    private $hook = null;


}