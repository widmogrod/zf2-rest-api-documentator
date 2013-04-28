<?php
namespace WidRestApiDocumentator\Service;

use WidRestApiDocumentator\HeaderInterface;
use WidRestApiDocumentator\ParamInterface;
use WidRestApiDocumentator\Strategy\Standard;
use Zend\Http\Client;
use Zend\Json\Json;
use Zend\Http\Header\ContentType;
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
        $headerValues = (array) $params->get('headers');
        $bodyValue = $params->get('body');

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
            foreach ($queryParams as $param /** @var ParamInterface $param */) {
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
        foreach($resource->getHeaders() as $header /** @var HeaderInterface $header */) {
            $key = $header->getName();
            // TODO: add validation if required (?)
            if (array_key_exists($key, $headerValues)) {
                $value = $headerValues[$key];
                $headers->addHeaderLine($header->getName(), $value);
            }
        }

        // Setup body
        $body = $resource->getBody();
        $body->parse($bodyValue);
        $client->setRawBody($body->toString());

        $response = $client->send();
        $body = $response->getBody();

        $contentType = $response->getHeaders()->get('Content-type');
        if ($contentType instanceof ContentType) {
            $value = $contentType->getFieldValue();
            if (false !== strpos($value, 'json')) {
                $body = Json::decode($body);
            }
        }

        return array(
            'requestUri' => $client->getUri()->toString(),
            'requestBody' => $client->getRequest()->getContent(),
            'requestHeaders' => $client->getRequest()->getHeaders()->toArray(),
            'responseHeaders' => $response->getHeaders()->toArray(),
            'responseBody' => $body,
        );
    }

    public function getHttpClient()
    {
        return new Client();
    }
}