<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:53
 */

namespace EzBpm\Process\Nodes;


class Gateway extends ProcessNodeContainer
{
    public function __construct()
    {
        parent::__construct('', NullNode::class);
    }

    /**
     * @param string $nodeName
     */
    public function setNodeName($nodeName)
    {
        $this->nodeName = $nodeName;
    }
}