<?php
namespace WidRestApiDocumentatorTest\Controller;

use WidRestApiDocumentator\Controller\DocsController as TestObject;
use Zend\EventManager\EventManager;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use Zend\Http\Response;

class DocsController extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Response
     */
    protected $response;
    /**
     * @var RouteMatch
     */
    protected $routeMatch;
    /**
     * @var EventManager
     */
    protected $eventManager;
    /**
     * @var MvcEvent
     */
    protected $event;
    /**
     * @var ServiceManager
     */
    protected $serviceLocator;
    /**
     * @var TestObject
     */
    protected $object;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $docs;

    public function setUp()
    {
        $this->eventManager = new EventManager();
        $this->event = new MvcEvent();
        $this->object = new TestObject();
        $this->serviceLocator = new ServiceManager();

        $this->docs = $this->getMockBuilder('WidRestApiDocumentator\Service\Docs')->disableOriginalConstructor()->getMock();
        $this->serviceLocator->setService('WidRestApiDocumentator\Service\Docs', $this->docs);

        $this->routeMatch = new RouteMatch(array(
            'controller' => 'WidRestApiDocumentator\Controller\Docs',
        ));
        $this->event->setRouteMatch($this->routeMatch);

        $this->request = new Request();
        $this->response = new Response();

        $this->object->setServiceLocator($this->serviceLocator);
        $this->object->setEventManager($this->eventManager);
        $this->object->setEvent($this->event);
    }

    /**
     * @dataProvider getListActionProvider
     */
    public function testListAction($page, $limit)
    {
        $query = $this->request->getQuery();
        $query->set('page', $page);
        $query->set('limit', $limit);
        $this->docs->expects($this->once())->method('getList')->with($page, $limit);
        $this->routeMatch->setParam('action', 'list');
        $result = $this->object->dispatch($this->request, $this->response);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('dataSet', $result);
    }

    public function getListActionProvider()
    {
        return array(
            'none' => array(
                '$page' => null,
                '$limit' => null,
            ),
            'simple' => array(
                '$page' => 10,
                '$limit' => 1,
            ),
        );
    }

    /**
     * @dataProvider getShowActionProvider
     */
    public function testShowAction($id)
    {
        $this->docs->expects($this->once())->method('getOne')->with($id);
        $this->routeMatch->setParam('action', 'show');
        $this->routeMatch->setParam('id', $id);
        $result = $this->object->dispatch($this->request, $this->response);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('data', $result);
    }

    public function getShowActionProvider()
    {
        return array(
            'simple' => array(
                '$id' => 'asdasd',
            ),
            'dots in name' => array(
                '$id' => 'as.da.sd',
            ),
            'strage params' => array(
                '$id' => '123dasd123!@#',
            ),
        );
    }

    /**
     * @dataProvider tetShowBackButtonDefaultProvider
     */
    public function testShowBackButtonDefault($id, $backLinkValue, $expectedBackLink)
    {
        $this->docs->expects($this->once())->method('getOne')->with($id);
        $this->routeMatch->setParam('action', 'show');
        $this->routeMatch->setParam('id', $id);
        if (null !== $backLinkValue){
            $this->routeMatch->setParam('show_back_link', $backLinkValue);
        }
        $result = $this->object->dispatch($this->request, $this->response);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('showBackLink', $result);
        $this->assertEquals($expectedBackLink, $result['showBackLink']);
    }

    public function tetShowBackButtonDefaultProvider()
    {
        return array(
            'default back' => array(
                '$id' => 'asdasd',
                '$backLinkValue' => null,
                '$expectedBackLink' => true,
            ),
            'back disabled' => array(
                '$id' => 'asdasd',
                '$backLinkValue' => 0,
                '$expectedBackLink' => false,
            ),
            'back enabled' => array(
                '$id' => 'asdasd',
                '$backLinkValue' => 1,
                '$expectedBackLink' => true,
            ),
        );
    }
}