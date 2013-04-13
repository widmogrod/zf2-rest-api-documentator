<?php
namespace WidRestApiDocumentatorTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class DocsController extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include 'tests/config/application.config.php'
        );
        parent::setUp();
    }

    public function testListAction()
    {
        $this->dispatch('/rest-api-docs');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('WidRestApiDocumentator');
        $this->assertControllerName('WidRestApiDocumentator\Controller\Docs');
        $this->assertControllerClass('DocsController');
        $this->assertMatchedRouteName('rest-api-docs');
    }
}