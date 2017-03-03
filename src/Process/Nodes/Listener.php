<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/23
 * Time: 下午9:37
 */

namespace EzBpm\Process\Nodes;


class Listener extends EventNode
{
    public function __construct($nodeName, $event){
        $this->event = $event;
        parent::__construct($nodeName, NullNode::class);
    }

    /**
     * @var string
     */
    private $event;
}