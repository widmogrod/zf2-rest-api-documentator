<?php
namespace WidRestApiDocumentator\Strategy;

use WidRestApiDocumentator\ConfigInterface;
use WidRestApiDocumentator\Exception;
use WidRestApiDocumentator\Param\GenericParam;
use WidRestApiDocumentator\ParamInterface;
use WidRestApiDocumentator\ParamSet\ParamSet;
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

    /**
     * @param ConfigInterface $config
     * @return \WidRestApiDocumentator\ResourceSet\StandardSet
     * @throws \WidRestApiDocumentator\Exception\InvalidArgumentException
     */
    public function parse(ConfigInterface $config)
    {
        $resultSet = new ResourceSet\StandardSet();

        $this->generalParams = new ParamSet();
        $this->parseGeneral($config->getGeneral());

        foreach ($config->getResources() as $definition => $options) {
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

            $resource = new Resource\StandardResource();
            $this->parseDefinition($definition, $resource);
            $this->parseOptions($options, $resource);
            $resultSet->append($resource);
        }
        return $resultSet;
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
        $resource->setUrl($parts['path']);
        $this->parseUrlParams($resource);

        if (array_key_exists('query', $parts)) {
            $this->parseQuery($parts['query'], $resource);
        }
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
        }
    }

    protected function parseGeneral($general)
    {
        if (!is_array($general)) {
            return;
        }

        if (isset($general['params']) && !count($general['params'])) {
            return;
        }

        foreach ((array)$general['params'] as $name => $options) {
            $param = new GenericParam();
            $param->setName($name);
            $param->setOptions($options);
            $this->generalParams->set($param);
        }
    }

    protected function parseUrlParams(ResourceInterface $resource)
    {
        $generalParams = $this->generalParams;
        $params = new ParamSet();
        preg_replace_callback('/<(?<name>[^}>\/]+)>/', function ($matches) use ($params, $generalParams) {
            // $value = $matches['value'];
            $name = $matches['name'];

            if ($generalParams->has($name)) {
                $param = clone $generalParams->get($name);
            } else {
                $param = new GenericParam();
                $param->setName($name);
            }
            $params->set($param);

            return '<' . $name . '>';
        }, $resource->getUrl());
        $resource->setUrlParams($params);
    }

    protected function parseQuery($query, ResourceInterface $resource)
    {
        parse_str($query, $query);
        $params = new ParamSet();
        foreach ($query as $name => $value) {
            if ($this->generalParams->has($name)) {
                $param = clone $this->generalParams->get($name);
            } else {
                $param = new GenericParam();
                $param->setName($name);
            }
            $params->set($param);
        }
        $resource->setQueryParams($params);
    }
}