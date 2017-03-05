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
use EzBpm\Process\Traits\SingleOutput;
use EzBpm\Utils\SerializableFunc;
use EzBpm\Utils\Verify;

/**
 * Class InclusiveJoinGateway
 * @package EzBpm
 * 排他网关(合并)
 *
 * 等待所有满足条件的输入到达, 触发输出
 */
class InclusiveJoinGateway extends Gateway
{
    use SingleOutput;

    public function handle(ProcessContext $context, ProcessEngine $engine)
    {
        //合并输入时, 重置token为流程分支前的token
        $inputs = $engine->getNodeStack($this->getName());
        $inputs += [$context];

        $childTokens = $context->getToken()->getParent()->getChildren();
        $childTokenNames = [];
        foreach ($childTokens as $childToken){
            $childTokenNames[$childToken->getName()] = 1;
        }

        $contexts = [];
        //判断是否所有token均已达到
        foreach ($inputs as $input){
            $name = $input->getToken()->getName();
            if(array_key_exists($name,$childTokenNames)){
                $childTokenNames[$name] = 0;
                $contexts[] =  $input;
            }
        }
        if(array_sum($childTokenNames) == 0){ //合并
            $merged = $engine->mergeContexts($contexts);
            $merged->setToken(
                $context->getToken()->getParent()->getParent()
            );
            parent::handle($context, $engine);
        }else{
            $engine->pushNodeStack($this->getName(), $context);
        }

    }
}
