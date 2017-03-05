<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/23
 * Time: 下午7:30
 */

namespace EzBpm\Process\Nodes;
use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Process\ProcessContext;
use EzBpm\Process\ProcessEngine;
use EzBpm\Process\Traits\SingleInput;
use EzBpm\Utils\Verify;

/**
 * Class InclusiveForkGateway
 * @package EzBpm
 * 包含网关(分发)
 *
 * 所有满足条件的输出都会被触发, 若没有任何输出触发, 则抛出异常
 */
class InclusiveForkGateway extends Gateway
{
    use SingleInput;

    public function addCondition(callable $cond, $comment='')
    {
        $next = new GatewayBranch($this, count($this->conditions));
        $this->conditions[] = [$cond, $next, $comment];
        return $next;
    }


    /**
     * @param ProcessContext $context
     * @param ProcessEngine $engine
     */
    public function dispatchNext(ProcessContext $context, ProcessEngine $engine)
    {
        $context->setToken($engine->createToken($context->getToken()));

        //创建新令牌, 并为所有分支分配子令牌, 以便可以统一控制分支
        foreach ($this->conditions as $output){
            list($cond, $next, $comment) = $output;
            if($cond == null || $cond($context)){
                //创建子ProcessContext和子ProcessToken
                $branchContext = $engine->createContext($context);
                $branchContext->setToken(
                    $engine->createToken($context->getToken())
                );
                $next->handle($branchContext, $engine);
            }
        }
        Verify::fail(new ProcessDefineException("Error at ".__CLASS__.": {$this->getName()}, no sequence flow can be selected"));
    }

    /**
     * @var array [[$condition, $next, $comment], ... ]
     */
    private $conditions=[];

}