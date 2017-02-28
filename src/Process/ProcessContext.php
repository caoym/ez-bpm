<?php
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/22
 * Time: 下午5:39
 */

namespace EzBpm\Process;


class ProcessContext extends \ArrayObject
{

    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->token;
    }
    /**
     * @param Token $token
     */
    public function setToken(Token $token)
    {
        $this->token = $token;
    }
    /**
     * @param ProcessNodeContainer $fromNode
     * @param \Exception $e
     * @return void
     */
    public function setLastException(ProcessNodeContainer $fromNode, \Exception $e)
    {
        $this->lastException->fromNode = $fromNode->getName();
        $this->lastException->code = $e->getCode();
        $this->lastException->message = $e->getMessage();
        $this->lastException->exception = get_class($e);
        if ($e instanceof ProcessRuntimeException::class){
            $this->lastException->userData = $e->getUserData();
        }else{
            $this->lastException->userData = null;
        }
    }

    /**
     * @var ExceptionData
     */
    private $lastException;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $parentId;

    /**
     * @var Token
     */
    private $token;

}