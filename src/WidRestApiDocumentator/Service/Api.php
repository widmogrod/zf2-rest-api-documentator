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

        $urlValues = (array) $params->get('urlParams');
        $queryValues = (array) $params->get('queryParams');

        // TODO: This, should be move to helper.
        $urlParams = $resource->getUrlParams();
        if (count($urlParams)) {
            $urlParams->rewind();
            $uri = preg_replace_callback('/(?<value><[^>]+>)/', function() use($urlParams, $urlValues){
                $param = $urlParams->current();
                $key = $param->getName();
                $urlParams->next();
                return array_key_exists($key, $urlValues) ? $urlValues[$key] : null;
            }, $uri);
        }

        $queryParams = $resource->getQueryParams();
        if (count($queryParams)) {
            $query = array();
            foreach ($queryParams as $param) {
                $key = $param->getName();
                if (array_key_exists($key, $queryValues)) {
                    $query[$key] = $queryValues[$key];
                }
            }
            if (count($query)) {
                $uri .= '?';
                $uri .= http_build_query($query);
            }
        }

        /** @var $client Client */
        $client = $this->getHttpClient();
        $client->setMethod($resource->getMethod());
        $client->setUri($uri);

        // Setup headers
        $headers = $client->getRequest()->getHeaders();
        foreach($resource->getHeaders() as $header) {
            $headers->addHeader($header);
        }

        // Setup body
        $client->setRawBody($resource->getBody()->toString());

        $response = $client->send();
        return $response->getBody();
    }

    public function getHttpClient()
    {
        return new Client();
    }
}