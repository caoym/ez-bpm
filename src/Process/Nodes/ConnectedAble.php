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

    public function preConnect(ConnectedAble $from);
    public function postConnect(ConnectedAble $from);
    public function connectTo(ConnectedAble $next);
    public function handle(ProcessContext $context, ProcessEngine $engine);
    public function getInputs();
    public function getOutputs();
    public function getName();
    public function setName($name);
}