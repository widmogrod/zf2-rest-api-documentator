<?php
namespace WidRestApiDocumentator\Controller;

use WidRestApiDocumentator\Strategy\Standard;
use Zend\Mvc\Controller\AbstractActionController;

class DocsController extends AbstractActionController
{
    /**
     * @return \WidRestApiDocumentator\Service\Docs
     */
    public function getApiService()
    {
        return $this->getServiceLocator()->get('WidRestApiDocumentator\Service\Docs');
    }

    public function listAction()
    {
        $this->layout()->setTemplate('wid-rest-api-documentator/docs/layout');

        /** @var $rq \Zend\Http\PhpEnvironment\Request */
        $rq = $this->getRequest();
        $service = $this->getApiService();
        return $service->getList($rq->getQuery('page'), $rq->getQuery('limit'));
    }

    public function showAction()
    {
        $this->layout()->setTemplate('wid-rest-api-documentator/docs/layout');

        $name = $this->params('name');
        $service = $this->getApiService();
        return $service->getOne($name);
    }
}