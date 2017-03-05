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
trait NoOutput{

    public function connectTo(ConnectedAble $next)
    {
        Verify::fail(new ProcessDefineException("can not connect from a ".get_class($this)));
    }
}