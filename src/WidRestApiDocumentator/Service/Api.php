<?php
namespace WidRestApiDocumentator\Service;

use WidRestApiDocumentator\Strategy\Standard;
use Zend\Http\Client;
use Zend\Stdlib\ParametersInterface;

class Api
{
    /**
     * @var Docs
     */
    protected $docs;

    public function __construct(Docs $docs)
    {
        $this->docs = $docs;
    }

    public function perform($id, $endpoint, ParametersInterface $params)
    {
        $api = $this->docs->getOne($id);
        $resourceSet = $api->getResourceSet();
        $resourceSet->seek($endpoint);
        $resource = $resourceSet->current();

        $config = $api->getConfig();
        $uri = $config->getBaseUrl() . $resource->getUrl();

        // TODO: This, should be move to helper.
        $urlParams = $resource->getUrlParams();
        if (count($urlParams)) {
            $urlParams->rewind();
            $uri = preg_replace_callback('/(?<value><[^>]+>)/', function($matches) use($urlParams, $params){
                $param = $urlParams->current();
                $urlParams->next();
                return $params->get($param->getName());
            }, $uri);
        }

        /** @var $client Client */
        $client = $this->getHttpClient();
        $client->setMethod($resource->getMethod());
        $client->setUri($uri);
//        $client->set
        $response = $client->send();
        return $response->getBody();
    }

    public function getHttpClient()
    {
        return new Client();
    }
}