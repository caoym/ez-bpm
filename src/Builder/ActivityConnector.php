<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/3
 * Time: 下午1:19
 */

namespace EzBpm\Builder;


use EzBpm\Process\Process;

class ActivityConnector extends Connector
{
    public function __construct(Process $process, $currentNode, $activityClass)
    {
        if(!$process->hasNode($currentNode)){
            $process->addNode($currentNode, $activityClass);
        }
        parent::__construct($process, $currentNode);

    }

}