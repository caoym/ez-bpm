<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/28
 * Time: 下午4:25
 */

namespace EzBpm\Process;


class Token
{
    public function disable(){

    }

    public function enable(){

    }
    /**
     * @return Token[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return Token
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * @var Token
     */
    private $parent;

    /**
     * @var Token
     */
    private $children=[];
}