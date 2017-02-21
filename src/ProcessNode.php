<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/22
 * Time: 上午12:01
 */

namespace EzBpm;


use EzBpm\Utils\Verify;

class ProcessNode
{
    public function preConnect(self $from){
        return true;
    }

    public function didConnect(self $from){
        return void;
    }
    public function connectTo(self $next){
        $next->preConnect($this);
        $this->nextNodes[] = $next;
        $next->didConnect($this);
    }

    public function handle(){

    }
    /**
     * @var ProcessNode[]
     */
    private $nextNodes;


}