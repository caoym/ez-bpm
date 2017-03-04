<?php
namespace EzBpm\Process;
use EzBpm\Process\Nodes\Activity;
use EzBpm\Process\Nodes\BeginEvent;
use EzBpm\Process\Nodes\ProcessNodeContainer;
use EzBpm\Utils\SerializableFunc;
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
    public function addNodeInstance($name, ProcessNodeContainer $node){
        !array_key_exists($name, $this->nodes) or Verify::fail(new \InvalidArgumentException("node $name exist"));
        $node->setName($name);
        $this->nodes[$name] = $node;
        return $node;
    }

    public function connect($from, $to){
        $this->getNode($from)->connectTo($this->getNode($to));
    }

    public function getNode($name){
        array_key_exists($name, $this->nodes) or Verify::fail("node $name not found");
        return $this->nodes[$name];
    }
    public function hasNode($name){
        return array_key_exists($name, $this->nodes);
    }

    /**
     * @var ProcessNodeContainer[]
     */
    private $nodes = [];
}