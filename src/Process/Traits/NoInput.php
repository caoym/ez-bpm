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
trait NoInput{
    public function preConnect(ConnectedAble $from)
    {
        Verify::fail(new ProcessDefineException("can not connect a ".get_class($this)));
    }

    public function postConnect(ConnectedAble $from)
    {
        Verify::fail(new ProcessDefineException("can not connect a ".get_class($this)));
    }
}