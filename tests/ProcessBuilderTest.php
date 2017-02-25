<?php
use \EzBpm\ProcessBuilder;
require __DIR__.'/../vendor/autoload.php';
/**
 * Created by PhpStorm.
 * User: caoyangmin
 * Date: 2017/2/21
 * Time: 下午11:31
 */
class ProcessBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testBuilder(){
        $builder = new ProcessBuilder();

        $builder->begin()->a->b->timer(5)->c->d->receiver('event')->end();

    }
}
