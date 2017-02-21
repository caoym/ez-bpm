<?php
namespace EzBpm;
use EzBpm\Utils\Verify;

/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:18
 */
class Process
{
    public function connect($from, $to){
        echo "$from --> $to \r\n";
        $this->getNode($from)->connectTo($this->getNode($to));
    }

    public function addReceiver($node, $eventName){
        return "$node.received($eventName)";
    }
    public function addTimer($node, $second){
        return "$node.timeout($second)";
    }

    private function getNode($name){
        array_key_exists($name, $this->nodes) or Verify::fail("node $name not found");
        return $this->nodes;
    }
    /**
     * @var ProcessNode[]
     */
    private $nodes = [];
}