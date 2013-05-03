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

        $urlValues = (array) $params->get('urlParams');
        $queryValues = (array) $params->get('queryParams');
        $headerValues = (array) $params->get('headers');
        $bodyValue = $params->get('body');

        // Prepare url params
        $urlParams = $resource->getUrlParams();
        $urlParams->setValues($urlValues);
        $urlParams->populate($resource);

        // Prepare http query params
        $queryParams = $resource->getQueryParams();
        $queryParams->setValues($queryValues);
        $queryParams->populate($resource);

        $config = $api->getConfig();
        $uri = $config->getBaseUrl() . $resource->getUrl();

        /** @var $client Client */
        $client = $this->getHttpClient();
        $client->setMethod($resource->getMethod());
        $client->setUri($uri);

        // Setup headers
        $request = $client->getRequest();
        $headers = $request->getHeaders();
        foreach($resource->getHeaders() as $header /** @var HeaderInterface $header */) {
            $key = $header->getName();
            if (array_key_exists($key, $headerValues)) {
                $value = $headerValues[$key];
                $headers->addHeaderLine($key, $value);
            }
        }

        // Setup body
        $body = $resource->getBody();
        $body->parse($bodyValue);
        $request->setContent($body->toString());

        $response = $client->send();

        // If response is JSON then parse it to JSON.
        $body = $response->getBody();
        $responseHeaders = $response->getHeaders();
        $contentType = $responseHeaders->get('Content-type');
        if ($contentType instanceof ContentType) {
            $value = $contentType->getFieldValue();
            if (false !== strpos($value, 'json')) {
                $body = Json::decode($body);
            }
        }

        return array(
            'requestUri' => $request->getUriString(),
            'requestBody' => $request->getContent(),
            'requestHeaders' => $headers->toArray(),
            'responseHeaders' => $responseHeaders->toArray(),
            'responseBody' => $body,
        );
    }

    public function getHttpClient()
    {
        return new Client();
    }
}