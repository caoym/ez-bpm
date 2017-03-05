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
 * Class ExclusiveJoinGateway
 * @package EzBpm
 * 排他网关(合并)
 *
 * 输出时, 只选择第一个满足条件的链路输出, 若没有满足条件的输出, 抛出异常
 * 输入时, 直接透传
 */
class ExclusiveJoinGateway extends Gateway
{
    use SingleOutput;


    public function handle(ProcessContext $context, ProcessEngine $engine)
    {
        //合并输入时, 重置token为流程分支前的token
        $context->setToken(
            $context->getToken()->getParent()->getParent()
        );
        parent::handle($context, $engine);
    }
}