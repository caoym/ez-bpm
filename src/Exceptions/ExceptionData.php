<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/22
 * Time: 下午5:59
 */

namespace EzBpm\Exceptions;


class ExceptionData
{
    public function __construct($nodeName, \RuntimeException $exception){
    }

    /**
     * name of node throw the exception
     * @var string
     */
    public $fromNode;
    /**
     * name of the exception
     * @var string
     */
    public $exception;

    /**
     * error message of the exception
     * @var string
     */
    public $message;

    /**
     * error code of the exception
     * @var int
     */
    public $code;

    /**
     * user defined data
     * @var mixed
     */
    public $userData;
}