<?php
namespace EzBpm\Tests;
use \EzBpm\Builder\ProcessBuilder;
use EzBpm\Process\Nodes\ProcessTaskInterface;
use \EzBpm\Process\Process;
use \EzBpm\Process\Nodes\ProcessNode;
use \EzBpm\Process\ProcessContext;
use EzBpm\Process\ProcessEngine;

require __DIR__.'/../vendor/autoload.php';
ini_set('date.timezone','Asia/Shanghai');


class TestTask implements ProcessTaskInterface{

    public function handle(ProcessContext $context)
    {

    }

    /**
     * 判断是否需要对此节点进行持久化
     *
     * 不进行持久化, 如果过程异常结束, 则无法记录此节点是否已经执行, 过程重启后, 将从最后一个持久化的节点开始执行;但开启持久化会降低系
     * 统吞吐量。
     *
     * 建议幂等的(允许重试的)接口不开启, 而只对不幂等的接口开启。
     * @return bool
     */
    public function needPersistence()
    {
        return true;
    }
}


/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:31
 */
class ProcessBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBuilder(){

        $process = new Process();
        $builder = new ProcessBuilder($process);

        $builder->begin
                        -> xFork('xf')
                                -> otherwise()
                                        -> task('task1', TestTask::class) -> xJoin('xj')->end;


        $builder        -> xFork('xf')
                                -> when(
                                    function(ProcessContext $context){
                                        return true;
                                    })
                                        -> task('task2', TestTask::class) -> xJoin('xj');

        $engine = new ProcessEngine($process);
        $engine->startNewProcess();

    }
}
