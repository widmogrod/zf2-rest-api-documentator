<?php
namespace WidRestApiDocumentatorTest\Controller;

use WidRestApiDocumentator\Service\Docs as TestObject;
use WidRestApiDocumentator\StrategyManager;

class Docs extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestObject
     */
    protected $object;
    /**
     * @var StrategyManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new StrategyManager();
        $this->object = new TestObject(
            include __DIR__ . '/_files/rest-api-docs-for-dock-service.php',
            $this->manager
        );
    }

    /**
     * @dataProvider getListParamsProvider
     */
    public function testGetList($page, $limit)
    {
        $dataSet = $this->object->getList($page, $limit);
        $this->assertInstanceOf('WidRestApiDocumentator\DataSetInterface', $dataSet);
        if ($limit > 0) {
            $this->assertEquals($limit, count($dataSet));
        }
        foreach ($dataSet as $data) {
            $this->assertInstanceOf('WidRestApiDocumentator\DataInterface', $data);
            $this->assertInstanceOf('WidRestApiDocumentator\ConfigInterface', $data->getConfig());
            $this->assertInstanceOf('WidRestApiDocumentator\ResourceSetInterface', $data->getResourceSet());
        }
    }

    public function getListParamsProvider()
    {
        return array(
            'unlimited' => array(
                '$page' => null,
                '$limit' => null,
            ),
        );
    }
}