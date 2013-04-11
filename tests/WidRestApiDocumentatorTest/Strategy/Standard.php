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
    public function testParseISuccess($resurces, $methods, $params, $urls) {
        // prepare config mock
        {{
            $call = 0;
            $this->config->expects($this->at($call++))->method('getResources')->will($this->returnValue($resurces));
        }}
        $result = $this->object->parse($this->config);
        $this->assertInstanceOf('WidRestApiDocumentator\ResourceSetInterface', $result);
        $this->assertEquals(count($result), count($resurces));

        foreach ($result as $key => $resource) {
            $this->assertArrayHasKey($key, $methods);
            $this->assertArrayHasKey($key, $params);
            $this->assertArrayHasKey($key, $urls);

            /** @var $resource StandardResource */
            $this->assertInstanceOf('WidRestApiDocumentator\ResourceInterface', $resource);
            $this->assertEquals($methods[$key], $resource->getMethod());
            $this->assertEquals($urls[$key], $resource->getUrl());

            $paramSet = $resource->getParams();
            $this->assertInstanceOf('WidRestApiDocumentator\ParamSetInterface', $paramSet);
            $this->assertEquals(count($params[$key]), count($paramSet));
            foreach ($paramSet as $param) {
                $this->assertArrayHasKey($param->getName(), $params[$key]);
            }
        }
    }

    public function getParseSuccessProvider() {
        return array(
            'GET' => array(
                '$resources' => array(
                    'GET: /test'
                ),
                '$methods' => array('GET'),
                '$params' => array(null),
                '$urls' => array('/test'),
            ),
            'with query regexp' => array(
                '$resources' => array(
                    'GET : /keywords/{<id>[\d]+}/search_engines?limit={[\d+]}&order={(asc|desc)}'
                ),
                '$methods' => array('GET'),
                '$params' => array(array('limit' => '[\d+]', 'order' => '(asc|desc)')),
                '$urls' => array('/keywords/{<id>[\d]+}/search_engines'),
            ),
            'with query' => array(
                '$resources' => array(
                    'GET : /keywords/{<id>[\d]+}/search_engines?limit=&order='
                ),
                '$methods' => array('GET'),
                '$params' => array(array('limit' => '', 'order' => '')),
                '$urls' => array('/keywords/{<id>[\d]+}/search_engines'),
            ),
            'POST' => array(
                '$resources' => array(
                    'POST: /test'
                ),
                '$methods' => array('POST'),
                '$params' => array(null),
                '$urls' => array('/test'),
            ),
            'PUT' => array(
                '$resources' => array(
                    'PUT: /test'
                ),
                '$methods' => array('PUT'),
                '$params' => array(null),
                '$urls' => array('/test'),
            ),
            'DELETE' => array(
                '$resources' => array(
                    'DELETE: /test'
                ),
                '$methods' => array('DELETE'),
                '$params' => array(null),
                '$urls' => array('/test'),
            ),
        );
    }
}