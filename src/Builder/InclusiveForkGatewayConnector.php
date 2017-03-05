<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/5
 * Time: 上午11:26
 */

namespace EzBpm\Builder;


use EzBpm\Process\Nodes\ExclusiveForkGateway;
use EzBpm\Process\Nodes\InclusiveForkGateway;
use EzBpm\Process\Process;

/**
 * Class InclusiveJoinGatewayConnector
 * @package EzBpm\Builder
 */
class InclusiveForkGatewayConnector
{
    public  function __construct(Process $process, InclusiveForkGateway $gateway )
    {
        $this->gateway = $gateway;
        $this->process = $process;
    }

    /**
     * @param callable $condition
     * @param string $comment
     * @return TaskConnector
     */
    public function when(callable $condition, $comment=''){
        $cond = $this->gateway->addCondition($condition, $comment);
        return new Connector($this->process, $cond);
    }

    /**
     * @var InclusiveForkGateway
     */
    private $gateway;
    /**
     * @var Process
     */
    private $process;
}