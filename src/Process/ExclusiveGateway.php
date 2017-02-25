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
 */
class ExclusiveGateway extends Gateway
{

    public function addCondition(callable $elseif, $comment)
    {
        $next = new ProcessNodeContainer('', NullNode::class);
        $this->outputs[] = [$elseif, $next, $comment];
        return $next;
    }

    public function handle(ProcessContext $context, ProcessEngine $engine)
    {
        foreach ($this->outputs as $output){
            list($elseif, $next, $comment) = $output;
            if($elseif($context)){
                $engine->pushTask(new SerializableFunc([$next, 'handle'], $context));
                return;
            }
        }
        Verify::fail(new ProcessDefineException("Error at ExclusiveGateway: {$this->getName()} $comment, no sequence flow can be selected"));
    }

    /**
     * @var []
     */
    private $outputs=[];
}