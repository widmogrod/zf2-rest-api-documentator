<?php
namespace WidRestApiDocumentator\Controller;

use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;

class ApiController extends AbstractActionController
{
    /**
     * @return \WidRestApiDocumentator\Service\Api
     */
    public function getApiService()
    {
        return $this->getServiceLocator()->get('WidRestApiDocumentator\Service\Api');
    }

    public function doAction()
    {
        /** @var $rq Request */
        $rq = $this->getRequest();
        $id = $this->params('id');
        $endpoint = $this->params('endpoint');

        $service = $this->getApiService();
        $result = $service->perform($id, $endpoint, $rq->getPost());

        $response = new Response();
        $response->setContent($result);
        return $response;
    }
}