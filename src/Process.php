<?php
namespace EzBpm;
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
    }

    public function addReceiver($node, $eventName){
        return "$node.received($eventName)";
    }
    public function addTimer($node, $second){
        return "$node.timeout($second)";
    }
}