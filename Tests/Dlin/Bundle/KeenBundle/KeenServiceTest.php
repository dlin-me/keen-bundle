<?php
namespace Dlin\Bundle\KeenBundle\Test\Dlin\Bundle\KeenBundle;

use Dlin\Bundle\KeenBundle\Service\KeenService;

class KeenServiceTest extends \PHPUnit_Framework_TestCase{


    public function testGetCollectionName()
    {
        $service = new KeenService('project','write','read');

        $this->assertEquals('keenServiceTest', $service->getCollectionName($this));
        $this->assertEquals('MyName', $service->getCollectionName($this,'MyName'));

        $this->assertEquals('TestMe', $service->getCollectionName(new MyTest()));



    }


    public function testGetVars()
    {
        $data = get_object_vars(new MyTest());
        $this->assertEquals('value1', $data['p1']);
        $this->assertArrayHasKey('p2', $data);
        $this->assertArrayNotHasKey('p3', $data);
        $this->assertArrayNotHasKey('p4', $data);

    }


}

class MyTest{
    public $p1 = "value1";
    public $p2 = "value2";
    private $p3 = "p3";
    protected $p4 = 'p4';

    public function getCollectionName(){
        return 'TestMe';
    }

}