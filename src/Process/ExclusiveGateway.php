<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/23
 * Time: 下午7:30
 */

namespace EzBpm\Process;
use EzBpm\Utils\SerializableFunc;
use EzBpm\Utils\Verify;

/**
 * Class ExclusiveGateway
 * @package EzBpm
 * 排他网关
 *
 * 输出时, 只选择第一个满足条件的链路输出, 若没有满足条件的输出, 抛出异常
 * 输入时, 直接透传
 */
class ExclusiveGateway extends Gateway
{

    public function addCondition(callable $elseif, $comment)
    {
        $next = new ProcessNodeContainer($this->nodeName.'. '.count($this->conditions), NullNode::class);
        $this->conditions[] = [$elseif, $next, $comment];
        return $next;
    }

    public function handle(ProcessContext $context, ProcessEngine $engine)
    {
        foreach ($this->conditions as $output){
            list($elseif, $next, $comment) = $output;
            if($elseif == null || $elseif($context)){
                $engine->pushTask(new SerializableFunc([$next, 'handle'], $context));
                return;
            }
        }
        Verify::fail(new ProcessDefineException("Error at ExclusiveGateway: {$this->getName()} $comment, no sequence flow can be selected"));
    }

    /**
     * @var ProcessNodeContainer[]
     */
    private $conditions=[];
}