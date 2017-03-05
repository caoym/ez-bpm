<?php

namespace EzBpm\Process\Traits;

use \EzBpm\Process\Nodes\ConnectedAble;
use \EzBpm\Exceptions\ProcessDefineException;
use \EzBpm\Utils\Verify;
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/3
 * Time: 下午7:11
 */
trait SingleOutput{

    public function connectTo(ConnectedAble $next)
    {
        count($this->getOutputs()) == 0 or Verify::fail(new ProcessDefineException("connect {$this->getName()} to {$next->getName()} failed, an ".get_class($this)." can only have ONE output"));
        parent::connectTo($next);
    }
}