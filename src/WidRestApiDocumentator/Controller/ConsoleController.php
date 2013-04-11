<?php
namespace WidRestApiDocumentator\Controller;

use WidRestApiDocumentator\Strategy\Standard;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;

class ConsoleController extends AbstractActionController
{
    public function testAction(){
        $request = $this->getRequest();

        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof ConsoleRequest){
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $config = $this->getServiceLocator()->get('config');
        $docs = $config['zf2-api-documentator'];

        $config = new \WidRestApiDocumentator\Config\Standard();
        $config->setOptions($docs['simple']);

        $strategy = new Standard();
        $resultSet = $strategy->parse($config);

        var_dump($resultSet);
    }
}