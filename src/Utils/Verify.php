<?php

namespace EzBpm\Utils;

/**
 * Class Verify
 */
class Verify
{
    /**
     * @param string|\Exception $err
     * @throws \Exception
     */
    static public function fail($err=''){
        if($err instanceof \Exception){
            throw $err;
        }else{
            throw new \Exception($err);
        }
    }

    /**
     * @param mixed $var
     * @param \Exception|string $msg
     * @return mixed
     * @throws \Exception
     */
    static public function isTrue($var, $msg=''){
        if (!$var) {
            if($msg === null || is_string($msg)){
                error_log($msg);
                throw new \Exception($msg);
            }else{
                error_log($msg->__toString());
                throw $msg;
            }
        } else {
            return $var;
        }
    }

}