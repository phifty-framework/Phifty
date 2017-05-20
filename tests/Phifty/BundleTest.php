<?php
use Phifty\Bundle;
use Phifty\Kernel;

class TestBundle extends Bundle
{
    public function init()
    {
        $this->route('/foo', 'foo');
    }

}

class BundleTest extends \PHPUnit\Framework\TestCase
{
    public function testBundleRoutes()
    {
        $bundle = new TestBundle(new Kernel, []);
        $bundle->init();
    }
}

