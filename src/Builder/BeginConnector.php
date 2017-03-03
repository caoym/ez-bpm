<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/3
 * Time: 下午5:34
 */

namespace EzBpm\Builder;


use EzBpm\Process\Nodes\BeginEvent;
use EzBpm\Process\Process;

class BeginConnector extends Connector
{
    function __construct(Process $process, $currentNode)
    {
        if(!$process->hasNode($currentNode)){
            $process->addNodeInstance($currentNode, new BeginEvent($currentNode));
        }
        parent::__construct($process, $currentNode);
    }
}