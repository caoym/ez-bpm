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
trait SingleInput{
    public function preConnect(ProcessNodeContainer $from)
    {
        count($this->inputs) == 0 or Verify::fail(new ProcessDefineException("connect {$from->getName()}->{$this->getName()} failed, an ".get_class($this)." can only have ONE input"));
        parent::preConnect($from);
    }
}