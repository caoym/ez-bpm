<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/3/5
 * Time: 下午1:11
 */

namespace EzBpm\Process\Nodes;


use EzBpm\Process\ProcessContext;
use EzBpm\Process\ProcessEngine;

interface ConnectedAble
{

    /**
     * @param ConnectedAble $from
     * @return void
     */
    public function preConnect(ConnectedAble $from);

    /**
     * @param ConnectedAble $from
     * @return void
     */
    public function postConnect(ConnectedAble $from);

    /**
     * @param ConnectedAble $next
     * @return void
     */
    public function connectTo(ConnectedAble $next);

    /**
     * @param ProcessContext $context
     * @param ProcessEngine $engine
     * @return void
     */
    public function handle(ProcessContext $context, ProcessEngine $engine);
    /**
     * @return ConnectedAble[]
     */
    public function getInputs();

    /**
     * @return ConnectedAble[]
     */
    public function getOutputs();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);
}