<?php
namespace Tests\Unit;
require_once __DIR__ . '/../vendor/autoload.php';
require_once  dirname(__DIR__)."/Test/Calculator.php"; //  加载需要测试类文件

class CalculatorTest extends PHPUnit\Framework\TestCase
{

    public function testSum()
    {
        $obj = new Calculator;
        $this->assertEquals(0, $obj->sum(0, 0));

    }

}

