<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/3
 * Time: 下午5:47
 */

namespace EzBpm\Process\Nodes;


use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Process\Traits\NoOutput;
use EzBpm\Process\Traits\SingleInput;
use EzBpm\Utils\Verify;

class EndEvent extends ProcessNodeContainer
{
    public function __construct($nodeName){
        parent::__construct($nodeName, NullNode::class);
    }

    use SingleInput;
    use NoOutput;
}