<?php
namespace EzBpm\Process;
use EzBpm\Utils\Verify;

/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:18
 */
class Process
{
    public function addNode($name , $class){
        !array_key_exists($name, $this->nodes) or Verify::fail(new \InvalidArgumentException("node $name exist"));
        $this->nodes[$name] = new Activity($name, $class);
    }

    public function addGateway($name, Gateway $gateway){
        !array_key_exists($name, $this->nodes) or Verify::fail(new \InvalidArgumentException("node $name exist"));
        $gateway->setNodeName($name);
        $this->nodes[$name] = $gateway;
    }

    public function connect($from, $to){
        echo "$from --> $to \r\n";
        $this->getNode($from)->connectTo($this->getNode($to));
    }


    public function addEventListener($node, $eventName){
        $listener = new Listener($node, $eventName);
        return $listener;
    }
    public function addTimer($name, $second){
        $timer = new Timer($name, $second);
        return $timer;
    }

    public function getNode($name){
        array_key_exists($name, $this->nodes) or Verify::fail("node $name not found");
        return $this->nodes;
    }
    public function hasNode($name){
        return array_key_exists($name, $this->nodes);
    }
    /**
     * @var ProcessNodeContainer[]
     */
    private $nodes = [];
}