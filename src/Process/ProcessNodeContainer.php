<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/22
 * Time: 上午12:01
 */

namespace EzBpm\Process;


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
        return true;
    }

    public function postConnect(ProcessNodeContainer $from){
        $this->fromNodes[] = $from;
        return true;
    }

    public function setPreHandleHook(SerializableFunc $hook){
        $this->preHandleHooks[] = $hook;

    }
    public function setPostHandleHook(SerializableFunc $hook){
        $this->postHandleHooks[] = $hook;
    }
    public function connectTo(ProcessNodeContainer $next){
        $next->preConnect($this);
        $this->toNodes[] = $next;
        $next->postConnect($this);
    }

//    public function eventTo($event, self $next){
//        $next->preConnect($this);
//        $this->eventTo[] = [$event, $next];
//        $next->didConnect($this);
//    }

    public function exceptionTo($exception, ProcessNodeContainer $next){
        $next->preConnect($this);
        $this->exceptionTo[] = [$exception, $next];
        $next->postConnect($this);
    }

//    public function timerTo($delay, self $next){
//        $next->preConnect($this);
//        $this->timerTo[] = [$delay, $next];
//        $next->didConnect($this);
//    }

    public function handle(ProcessContext $context, ProcessEngine $engine){
        // init exceptionTo handler
        $res = null;
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
                    $engine->pushTask(new SerializableFunc([$nextNode, 'handle'], $context));
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
    protected function handleInternal(ProcessNode $node, ProcessContext $context){

        $node->handle($context);
    }

    protected function handleNext(ProcessContext $context, ProcessEngine $engine){
        //call next nodes
        foreach ($this->toNodes as $nextNode){
            $engine->pushTask(new SerializableFunc([$nextNode, 'handle'], $context));
        }
    }
    /**
     * @return mixed
     */
    public function getName(){
        return $this->nodeName;
    }

    public function getClass(){
        return $this->nodeClass;
    }

    /**
     * from nodes
     * @var ProcessNodeContainer[]
     */
    protected $fromNodes = [];
    /**
     * @var ProcessNodeContainer[]
     */
    protected $toNodes=[];

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

    private $preHandleHooks = [];
    private $postHandleHooks=[];


}