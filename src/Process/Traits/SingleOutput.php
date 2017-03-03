<?php

namespace EzBpm\Process\Traits;

use \EzBpm\Process\Nodes\ProcessNodeContainer;
use \EzBpm\Exceptions\ProcessDefineException;
use \EzBpm\Utils\Verify;
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/3
 * Time: 下午7:11
 */
trait SingleOutput{

    public function connectTo(ProcessNodeContainer $next)
    {
        count($this->outputs) == 0 or Verify::fail(new ProcessDefineException("connect {$this->getName()}->{$next->getName()} failed, an ".get_class($this)." can only have ONE output"));
        parent::connectTo($next);
    }
}