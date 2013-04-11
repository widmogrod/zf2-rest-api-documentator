<?php
namespace WidRestApiDocumentator\Strategy;

use WidRestApiDocumentator\ConfigInterface;
use WidRestApiDocumentator\Exception;
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

    protected $availableMethods = array(
        self::METHOD_GET => true,
        self::METHOD_POST => true,
        self::METHOD_PUT => true,
        self::METHOD_DELETE => true,
    );

    public function parse(ConfigInterface $config)
    {
        $resultSet = new ResourceSet\StandardSet();
        foreach ($config->getResources() as $definition => $options) {
            if (is_int($definition) && is_string($options)) {
                $definition = $options;
                $options = null;
            }

            $invalidDefinition = !is_string($definition);
            $invalidOptions = (null !== $options) && (!is_string($options) || !is_array($options));
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
    }

    protected function parseDefinition($definition, ResourceInterface $resource)
    {
        $definition = trim($definition);
        list ($method, $url) = explode(':', $definition, 1);
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

        $resource->setUri($url);

        if (array_key_exists('query', $parts)) {
            parse_str($parts['query'], $query);
            $resource->setParams($query);
        }
    }

    protected function parseOptions($options, ResourceInterface $resource) {
        if (is_string($options)) {
            $resource->setDescription($options);
        }
    }
}