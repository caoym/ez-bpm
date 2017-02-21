<?php
namespace EzBpm;
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:18
 */
class ProcessBuilder
{
    public function __construct(){
        $this->process = new Process();
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