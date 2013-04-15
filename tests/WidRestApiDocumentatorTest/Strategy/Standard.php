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
            $this->config->expects($this->at($call++))->method('getGeneral');
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
            $this->config->expects($this->at($call++))->method('getGeneral');
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
    public function testParseSuccess($resurces, $methods, $urlParams, $queryParams, $urls) {
        // prepare config mock
        {{
            $call = 0;
            $this->config->expects($this->at($call++))->method('getGeneral');
            $this->config->expects($this->at($call++))->method('getResources')->will($this->returnValue($resurces));
        }}
        $result = $this->object->parse($this->config);
        $this->assertInstanceOf('WidRestApiDocumentator\ResourceSetInterface', $result);
        $this->assertEquals(count($result), count($resurces));

        foreach ($result as $key => $resource) {
            $this->assertArrayHasKey($key, $methods);
            $this->assertArrayHasKey($key, $queryParams);
            $this->assertArrayHasKey($key, $urls);

            /** @var $resource StandardResource */
            $this->assertInstanceOf('WidRestApiDocumentator\ResourceInterface', $resource);
            $this->assertEquals($methods[$key], $resource->getMethod());
            $this->assertEquals($urls[$key], $resource->getUrl());

            $urlParamSet = $resource->geturlParams();
            $this->assertInstanceOf('WidRestApiDocumentator\ParamSetInterface', $urlParamSet);
            $this->assertEquals(count($urlParams[$key]), count($urlParamSet));
            foreach ($urlParamSet as $param) {
                $this->assertArrayHasKey($param->getName(), $urlParams[$key]);
            }

            $queryParamSet = $resource->getQueryParams();
            $this->assertInstanceOf('WidRestApiDocumentator\ParamSetInterface', $queryParamSet);
            $this->assertEquals(count($queryParams[$key]), count($queryParamSet));
            foreach ($queryParamSet as $param) {
                $this->assertArrayHasKey($param->getName(), $queryParams[$key]);
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
                '$urlParams' => array(null),
                '$queryParams' => array(null),
                '$urls' => array('/test'),
            ),
            'with query regexp' => array(
                '$resources' => array(
                    'GET : /keywords/{<id>[\d]+}/search_engines?limit={[\d+]}&order={(asc|desc)}'
                ),
                '$methods' => array('GET'),
                '$urlParams' => array(array('id' => '[\d]+')),
                '$queryParams' => array(array('limit' => '[\d+]', 'order' => '(asc|desc)')),
                '$urls' => array('/keywords/{<id>[\d]+}/search_engines'),
            ),
            'with query' => array(
                '$resources' => array(
                    'GET : /keywords/{<id>[\d]+}/search_engines?limit=&order='
                ),
                '$methods' => array('GET'),
                '$urlParams' => array(array('id' => '[\d]+')),
                '$queryParams' => array(array('limit' => '', 'order' => '')),
                '$urls' => array('/keywords/{<id>[\d]+}/search_engines'),
            ),
            'POST' => array(
                '$resources' => array(
                    'POST: /test'
                ),
                '$methods' => array('POST'),
                '$urlParams' => array(null),
                '$queryParams' => array(null),
                '$urls' => array('/test'),
            ),
            'PUT' => array(
                '$resources' => array(
                    'PUT: /test'
                ),
                '$methods' => array('PUT'),
                '$urlParams' => array(null),
                '$queryParams' => array(null),
                '$urls' => array('/test'),
            ),
            'DELETE' => array(
                '$resources' => array(
                    'DELETE: /test'
                ),
                '$methods' => array('DELETE'),
                '$urlParams' => array(null),
                '$queryParams' => array(null),
                '$urls' => array('/test'),
            ),
        );
    }

    /**
     * @dataProvider getParseSuccessWithGeneralProvider
     */
    public function testParseSuccessWithGeneral($general, $resources, $methods, $urlParams, $queryParams, $urls) {
        // prepare config mock
        {{
            $call = 0;
            $this->config->expects($this->at($call++))->method('getGeneral')->will($this->returnValue($general));
            $this->config->expects($this->at($call++))->method('getResources')->will($this->returnValue($resources));
        }}

        $result = $this->object->parse($this->config);
        $this->assertInstanceOf('WidRestApiDocumentator\ResourceSetInterface', $result);
        $this->assertEquals(count($result), count($resources));

        foreach ($result as $key => $resource) {
            $this->assertArrayHasKey($key, $methods);
            $this->assertArrayHasKey($key, $queryParams);
            $this->assertArrayHasKey($key, $urls);

            /** @var $resource StandardResource */
            $this->assertInstanceOf('WidRestApiDocumentator\ResourceInterface', $resource);
            $this->assertEquals($methods[$key], $resource->getMethod());
            $this->assertEquals($urls[$key], $resource->getUrl());

            $urlParamSet = $resource->getUrlParams();
            $this->assertInstanceOf('WidRestApiDocumentator\ParamSetInterface', $urlParamSet);
            $this->assertEquals(count($urlParams[$key]), count($urlParamSet));
            foreach ($urlParamSet as $param) {
                $this->assertArrayHasKey($param->getName(), $urlParams[$key]);
                $paramOptions = $urlParams[$key][$param->getName()];
                $this->assertEquals($paramOptions['description'], $param->getDescription());
                $this->assertEquals($paramOptions['required'], $param->isRequired());
                $this->assertEquals($paramOptions['type'], $param->getType());
            }

            $queryParamSet = $resource->getQueryParams();
            $this->assertInstanceOf('WidRestApiDocumentator\ParamSetInterface', $queryParamSet);
            $this->assertEquals(count($queryParams[$key]), count($queryParamSet));
            foreach ($queryParamSet as $param) {
                $this->assertArrayHasKey($param->getName(), $queryParams[$key]);
                $paramOptions = $queryParams[$key][$param->getName()];
                $this->assertEquals($paramOptions['description'], $param->getDescription());
                $this->assertEquals($paramOptions['required'], $param->isRequired());
                $this->assertEquals($paramOptions['type'], $param->getType());
            }
        }
    }

    public function getParseSuccessWithGeneralProvider() {
        return array(
            'GET' => array(
                '$general' => array(
                    'params' => array(),
                ),
                '$resources' => array(
                    'GET: /test'
                ),
                '$methods' => array('GET'),
                '$urlParams' => array(null),
                '$queryParams' => array(null),
                '$urls' => array('/test'),
            ),
            'with query regexp' => array(
                '$general' => array(
                    'params' => array(
                        'id' => array(
                            'type' => 'integer',
                            'required' => true,
                            'description' => 'ID of resource'
                        ),
                        'limit' => array(
                            'type' => 'integer',
                            'required' => false,
                            'description' => 'Limit API result to given value. Value must be between 1-100'
                        ),
                        'order' => array(
                            'type' => 'string',
                            'required' => false,
                            'description' => 'Retrieve API result ordered by given value'
                        ),
                    ),
                ),
                '$resources' => array(
                    'GET : /keywords/{<id>[\d]+}/search_engines?limit={[\d+]}&order={(asc|desc)}'
                ),
                '$methods' => array('GET'),
                '$urlParams' => array(
                    array(
                        'id' => array(
                            'type' => 'integer',
                            'required' => true,
                            'description' => 'ID of resource'
                        ),
                    )
                ),
                '$queryParams' => array(
                    array(
                        'limit' => array(
                            'type' => 'integer',
                            'required' => false,
                            'description' => 'Limit API result to given value. Value must be between 1-100'
                        ),
                        'order' => array(
                            'type' => 'string',
                            'required' => false,
                            'description' => 'Retrieve API result ordered by given value'
                        ),
                    )
                ),
                '$urls' => array('/keywords/{<id>[\d]+}/search_engines'),
            ),
        );
    }
}