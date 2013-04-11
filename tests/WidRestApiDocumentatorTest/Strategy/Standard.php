<?php
namespace WidRestApiDocumentatorTest\Strategy;

use WidRestApiDocumentator\Resource\StandardResource;
use WidRestApiDocumentator\Strategy\Standard as TestObject;

class Standard extends \PHPUnit_Framework_TestCase {
    /**
     * @var TestObject
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    public function setUp() {
        $this->object = new TestObject();
        $this->config = $this->getMock('WidRestApiDocumentator\ConfigInterface');
    }

    /**
     * @expectedException \WidRestApiDocumentator\Exception\InvalidArgumentException
     * @dataProvider getParseInvalidResourcesProvider
     */
    public function testParseInvalidResources($resurces) {
        // prepare config mock
        {{
            $call = 0;
            $this->config->expects($this->at($call++))->method('getResources')->will($this->returnValue($resurces));
        }}
        $this->object->parse($this->config);
    }

    public function getParseInvalidResourcesProvider() {
        return array(
            'no string' => array(
                'resources' => array(
                    1 => array(),
                ),
            ),
            'only array' => array(
                'resources' => array(
                    array(),
                ),
            ),
            'object' => array(
                'resources' => array(
                    new \stdClass(),
                ),
            ),
        );
    }

    /**
     * @expectedException \WidRestApiDocumentator\Exception\InvalidArgumentException
     * @dataProvider getParseInvalidDefinitionProvider
     */
    public function testParseInvalidDefinition($resurces) {
        // prepare config mock
        {{
            $call = 0;
            $this->config->expects($this->at($call++))->method('getResources')->will($this->returnValue($resurces));
        }}
        $this->object->parse($this->config);
    }

    public function getParseInvalidDefinitionProvider() {
        return array(
            'no separator' => array(
                'resources' => array(
                    'GETx /http'
                ),
            ),
            'invalid method' => array(
                'resources' => array(
                    'invalid: /http'
                ),
            ),
            'no path' => array(
                'resources' => array(
                    'invalid:'
                ),
            ),
        );
    }

    /**
     * @dataProvider getParseSuccessProvider
     */
    public function testParseISuccess($resurces) {
        // prepare config mock
        {{
            $call = 0;
            $this->config->expects($this->at($call++))->method('getResources')->will($this->returnValue($resurces));
        }}
        $result = $this->object->parse($this->config);
        $this->assertInstanceOf('WidRestApiDocumentator\ResourceSetInterface', $result);
        $this->assertEquals(count($result), count($resurces));

        foreach ($result as $key => $resource) {
            /** @var $resource StandardResource */
            $this->assertInstanceOf('WidRestApiDocumentator\ResourceInterface', $resource);
            $this->assertEquals($resource->get)
        }
    }

    public function getParseSuccessProvider() {
        return array(
            'no separator' => array(
                'resources' => array(
                    'GETx /http'
                ),
            ),
            'invalid method' => array(
                'resources' => array(
                    'invalid: /http'
                ),
            ),
            'no path' => array(
                'resources' => array(
                    'invalid:'
                ),
            ),
        );
    }
}