<?php
namespace WidRestApiDocumentator\Controller;

use WidRestApiDocumentator\Strategy\Standard;
use Zend\Mvc\Controller\AbstractActionController;

class DocsController extends AbstractActionController
{
    public function listAction()
    {
        /** @var $rq \Zend\Http\PhpEnvironment\Request */
        $rq = $this->getRequest();
        $service = $this->getApiService();
        return $service->getList($rq->getQuery('page'), $rq->getQuery('limit'));
    }

    public function showAction()
    {
        $name = $this->params('name');
        $service = $this->getApiService();
        return $service->getOne($name);
    }
}