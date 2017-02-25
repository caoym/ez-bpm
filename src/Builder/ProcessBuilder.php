<?php
namespace EzBpm\Builder;
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
    }
    /**
     * @return ProcessBuilderNode
     */
    public function begin(){
        return new ProcessBuilderNode($this->process, 'begin');
    }
    /**
     * @param $name
     * @return self
     */
    public function __get($name){
        return new ProcessBuilderNode($this->process, $name);
    }

    /**
     * @var Process
     */
    private $process;
}