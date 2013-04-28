<?php
namespace WidRestApiDocumentator\Controller;

use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\Json\Json;
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

    /**
     * @return \WidRestApiDocumentator\Service\Docs
     */
    public function getDocsService()
    {
        return $this->getServiceLocator()->get('WidRestApiDocumentator\Service\Docs');
    }

    public function listAction()
    {
        /** @var $rq \Zend\Http\PhpEnvironment\Request */
        $rq = $this->getRequest();
        $service = $this->getDocsService();

        $dataSet = $service->getList($rq->getQuery('page'), $rq->getQuery('limit'));
        $dataSet = $service->toArrayDataSet($dataSet);

        // Prepare response object
        $result = array(
            'result' => $dataSet,
            'count' => $service->count(),
        );
        $result = Json::encode($result);

        // Send response
        $response = new Response();
        $response->getHeaders()->addHeaderLine('Content-Type: application/json');
        $response->setContent($result);
        return $response;
    }

    public function showAction()
    {
        /** @var $rq \Zend\Http\PhpEnvironment\Request */
        $rq = $this->getRequest();
        $service = $this->getDocsService();

        $id = $this->params('id');
        $data = $service->getOne($id);
        $result = $service->toArrayData($data);
        $result = Json::encode($result);

        // Send response
        $response = new Response();
        $response->getHeaders()->addHeaderLine('Content-Type: application/json');
        $response->setContent($result);
        return $response;
    }

    public function doAction()
    {
        /** @var $rq Request */
        $rq = $this->getRequest();
        $id = $this->params('id');
        $endpoint = $this->params('endpoint');

        $service = $this->getApiService();
        $result = $service->perform($id, $endpoint, $rq->getPost());
        $result = Json::encode($result);

        $response = new Response();
        $response->setContent($result);
        return $response;
    }
}