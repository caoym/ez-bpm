<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:53
 */

namespace EzBpm\Process\Nodes;
use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Utils\Verify;

/**
 * Class Activity
 * ONLY & MUST have one input and one output
 * @package EzBpm
 */
class Activity extends ProcessNodeContainer
{
    public function connectTo(ProcessNodeContainer $next)
    {
        count($this->outputs) == 0 or Verify::fail(new ProcessDefineException("connect {$this->getName()}->{$next->getName()} failed, an ".__CLASS__." can only have ONE output"));
        parent::connectTo($next);
    }

    public function preConnect(ProcessNodeContainer $from)
    {
        count($this->inputs) == 0 or Verify::fail(new ProcessDefineException("connect {$from->getName()}->{$this->getName()} failed, an ".__CLASS__." can only have ONE input"));
        parent::preConnect($from);
    }
}