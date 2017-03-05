<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/3
 * Time: 下午5:45
 */

namespace EzBpm\Process\Nodes;


use EzBpm\Process\Traits\NoInput;
use EzBpm\Process\Traits\SingleOutput;


class BeginEvent extends ProcessNode
{
    use NoInput;
    use SingleOutput;
}