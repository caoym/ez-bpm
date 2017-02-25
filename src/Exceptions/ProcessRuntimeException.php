<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/22
 * Time: 下午6:07
 */

namespace EzBpm\Exceptions;


class ProcessRuntimeException extends \RuntimeException
{

    public function __construct($message = "", $code = 0, $userData = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->userData = $userData;
    }

    /**
     * @return mixed
     */
    public function getUserData(){
        return $this->userData;
    }
    /**
     * user defined data
     * @var mixed
     */
    private $userData;
}