<?php
namespace EzBpm\Builder;
use EzBpm\Exceptions\ProcessDefineException;
use EzBpm\Process\Activity;
use EzBpm\Process\Process;
use EzBpm\Utils\Verify;

/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:18
 */
class ProcessBuilder
{
    /**
     * ProcessBuilder constructor.
     * @param Process $process
     */
    public function __construct(Process $process){
        $this->process = $process?:new Process();
        $this->begin = new BeginConnector($this->process, 'begin');
    }

    /**
     * @param string $name
     * @param string $activityClass 不是新建时$activityClass应为空
     * @return ActivityConnector
     */
    public function activity($name, $activityClass=null){
        return new ActivityConnector($this->process, $name, $activityClass);
    }
    /**
     * @param $name
     * @return ActivityConnector
     */
    public function __get($name){
        $this->process->hasNode($name) or Verify::fail(new ProcessDefineException("node '$name' not exist"));
        return new ActivityConnector($this->process, $name, null);
    }

    /**
     * @var Process
     */
    private $process;

    /**
     * @var BeginConnector;
     */
    public $begin;
}