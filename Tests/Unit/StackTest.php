<?php


/**
 *
 * 测试简单函数
 *
*/


require_once __DIR__ . '/../vendor/autoload.php';
define("TEST_PATH", dirname(__DIR__) . "/");

class StackTest extends PHPUnit\Framework\TestCase
{


    /**
     *
     * 注意点：
     * @test 测试array_push、array_pop函数
     * 1、StackTest 测试类
     * 2、StackTest 继承于 PHPUnit\Framework\TestCase
     * 3、testPushAndPop()，测试方法必须为public权限，一般以test开头
     * 4、assertEquals() 此方法用来对实际值与预期值的匹配
     *
    */
    public function testPushAndPop()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');

        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }


}
