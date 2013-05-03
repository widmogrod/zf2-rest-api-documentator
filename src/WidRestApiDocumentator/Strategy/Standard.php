<?php
namespace WidRestApiDocumentator\Strategy;

use WidRestApiDocumentator\Body\GenericBody;
use WidRestApiDocumentator\BodyInterface;
use WidRestApiDocumentator\ConfigInterface;
use WidRestApiDocumentator\Exception;
use WidRestApiDocumentator\Header\GenericHeader;
use WidRestApiDocumentator\HeaderSet\HeaderSet;
use WidRestApiDocumentator\Param\GenericParam;
use WidRestApiDocumentator\ParamInterface;
use WidRestApiDocumentator\ParamSet\ParamSet;
use WidRestApiDocumentator\ParamSet\QueryParamSet;
use WidRestApiDocumentator\ParamSet\UrlParamSet;
use WidRestApiDocumentator\ParamSetInterface;
use WidRestApiDocumentator\Resource;
use WidRestApiDocumentator\ResourceInterface;
use WidRestApiDocumentator\ResourceSet;
use WidRestApiDocumentator\StrategyInterface;

class Standard implements StrategyInterface
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * @var ParamSet
     */
    protected $generalParams;

    protected $availableMethods = array(
        self::METHOD_GET => true,
        self::METHOD_POST => true,
        self::METHOD_PUT => true,
        self::METHOD_DELETE => true,
    );

    protected $allowedGeneralKeys = array(
        'params' => true,
        'headers' => true,
        'body' => true,
    );

    /**
     * @param ConfigInterface $config
     * @return \WidRestApiDocumentator\ResourceSet\StandardSet
     * @throws \WidRestApiDocumentator\Exception\InvalidArgumentException   If definition od resource is not valid
     */
    public function parse(ConfigInterface $config)
    {
        $resultSet = new ResourceSet\StandardSet();

        $this->generalParams = new ParamSet();
        $this->parseGeneral($config->getGeneral());

        foreach ($config->getResources() as $definition => $options) {
            $this->validateDefinition($definition, $options);

            $resource = $this->createNewResource();

            // Do the magic.
            $this->parseOptions($options, $resource);
            $this->parseDefinition($definition, $resource);
            $this->parseUrlParams($resource);
            $this->parseQuery($resource);

            $resultSet->append($resource);
        }
        return $resultSet;
    }

    /**
     * @param $definition
     * @param $options
     * @throws \WidRestApiDocumentator\Exception\InvalidArgumentException
     * @return void
     */
    public function validateDefinition(&$definition, &$options)
    {
        if (is_int($definition) && is_string($options)) {
            $definition = $options;
            $options = null;
        }

        $invalidDefinition = !is_string($definition);
        $invalidOptions = (null !== $options) && (!is_string($options) && !is_array($options));
        if ($invalidDefinition || $invalidOptions) {
            $message = 'Resource must be written as $definition(string) => $options(string|array)'
                     . ' but was written as $definition(%s) => $options(%s)';
            $definitionType = gettype($definition);
            $optionsType = is_object($options) ? get_class($options) : gettype($options);
            $message = sprintf($message, $definitionType, $optionsType);
            throw new Exception\InvalidArgumentException($message);
        }
    }

    public function createNewResource() {
        return new Resource\StandardResource();
    }

    protected function parseOptions($options, ResourceInterface $resource)
    {
        if (is_string($options)) {
            $resource->setDescription($options);
        } else if (is_array($options)) {
            $this->parseGeneral($options);
            if (array_key_exists('description', $options)) {
                $resource->setDescription($options['description']);
            }
            if (array_key_exists('headers', $options)) {
                $this->parseHeaders((array) $options['headers'], $resource);
            }
            if (array_key_exists('body', $options)) {
                $this->parseBody((array) $options['body'], $resource);
            }
        }
    }

    public function parseHeaders(array $headers, ResourceInterface $resource)
    {
        $headerSet = new HeaderSet();
        foreach ($headers as $name => $options) {
            $header = new GenericHeader();
            $header->setName($name);
            $header->setOptions($options);
            $headerSet->set($header);
        }
        $resource->setHeaders($headerSet);
    }

    public function parseBody(array $data, ResourceInterface $resource)
    {
        if (!array_key_exists('params', $data) || !is_array($data['params'])) {
            return;
        }

        $body = isset($data['type']) ? $this->loadBody($data['type']) : new GenericBody();
        $paramSet = $this->initBodyParams($data);
        $body->setParams($paramSet);
        $resource->setBody($body);
    }

    /**
     * @param string $class
     * @throws \WidRestApiDocumentator\Exception\DomainException
     * @return BodyInterface
     */
    public function loadBody($class)
    {
        if (!class_exists($class)) {
            $message = 'Type should be valid class name but was given %s';
            $message = sprintf($message, $class);
            throw new Exception\DomainException($message);
        }
        $interfaces = class_implements($class);
        if (!isset($interfaces['WidRestApiDocumentator\BodyInterface'])) {
            $message = 'Body type class "%s" should implement "WidRestApiDocumentator\BodyInterface"';
            $message = sprintf($message, $class);
            throw new Exception\DomainException($message);
        }
        return new $class();
    }

    /**
     * @param array $data
     * @return ParamSet
     */
    public function initBodyParams(array $data)
    {
        $paramSet = new ParamSet();
        $params = (array)$data['params'];
        foreach ($params as $name => $options) {
            $param = new GenericParam();
            $param->setName($name);
            $param->setOptions($options);
            $paramSet->set($param);
        }
        return $paramSet;
    }

    protected function parseGeneral($general)
    {
        if (!is_array($general)) {
            return;
        }

        $general = array_intersect_key($general, $this->allowedGeneralKeys);

        foreach ($general as $namespace => $data) {
            switch ($namespace) {
                case 'params':
                    foreach ((array) $data as $name => $options) {
                        $param = new GenericParam();
                        $param->setName($name);
                        $param->setOptions($options);
                        $this->generalParams->set($param);
                    }
                    break;
            }
        }
    }

    protected function parseDefinition($definition, ResourceInterface $resource)
    {
        $definition = trim($definition);
        if (false === strpos($definition, ':')) {
            $message = 'Definition must contains HTTP Method & URL separator ":"';
            throw new Exception\InvalidArgumentException($message);
        }

        list ($method, $url) = (array)explode(':', $definition, 2);
        $method = trim($method);
        $method = strtoupper($method);
        if (!isset($this->availableMethods[$method])) {
            $message = 'HTTP Method is not supported %s. Method must be one of those: %s';
            $message = sprintf($message, $method, implode(array_keys($this->availableMethods)));
            throw new Exception\InvalidArgumentException($message);
        }

        $url = trim($url);
        $url = ltrim($url, '/');
        $url = '/' . $url;

        $parts = parse_url($url);
        if (!array_key_exists('path', $parts)) {
            $message = 'URL path must be defined. Given URL %s';
            $message = sprintf($message, $url);
            throw new Exception\InvalidArgumentException($message);
        }

        $resource->setMethod($method);
        $resource->setUrl($url);
    }

    protected function parseUrlParams(ResourceInterface $resource)
    {
        $params = new UrlParamSet();
        $params->interpret($resource);
        $resource->setUrlParams($params);

        $this->updateParams($params);
    }

    protected function parseQuery(ResourceInterface $resource)
    {
        $params = new QueryParamSet();
        $params->interpret($resource);
        $resource->setQueryParams($params);

        $this->updateParams($params);
    }

    public function updateParams(ParamSetInterface $params)
    {
        foreach ($params as $param/** @var ParamInterface $param */) {
            if ($this->generalParams->has($param->getName())) {
                $general = $this->generalParams->get($param->getName());
                $param->setDescription($general->getDescription());
                $param->setType($general->getType());
                $param->setRequired($general->isRequired());
            }
        }
    }
}
