<?php
namespace WidRestApiDocumentator\Strategy;

use WidRestApiDocumentator\ConfigInterface;
use WidRestApiDocumentator\Exception;
use WidRestApiDocumentator\Param\GenericParam;
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

        if (array_key_exists('query', $parts)) {
            $this->parseQuery($parts['query'], $resource);
        }
    }

    protected function parseOptions($options, ResourceInterface $resource)
    {
        if (is_string($options)) {
            $resource->setDescription($options);
        }
    }

    protected function parseQuery($query, ResourceInterface $resource)
    {
        // Replacement is done because when I use only
        // "parse_str" function chars like "+" were converted to " "
        $params = array();
        $replaced = preg_replace_callback('/{([^}]+)}/', function ($matches) use (&$params) {
            $value = $matches[1];
            $key = count($params);
            $params[$key] = $value;
            return '{' . $key . '}';
        }, $query);

        parse_str($replaced, $query);

        $query = array_map(function ($value) use (&$params) {
            return preg_replace('/{([^}]+)}/e', "\$params[\$1]", $value);
        }, $query);

        $params = new ParamSet();
        foreach ($query as $name => $value) {
            $param = new GenericParam();
            $param->setName($name);

            $pattern = sprintf('/%s/', $value);
            switch (true) {
                case false !== preg_match($pattern, '1234567890'):
                    $param->getType($param::TYPE_INTEGER);
                    break;
                case false !== preg_match($pattern, 'abcdefghijklmnoprstuwyz'):
                    $param->getType($param::TYPE_STRING);
                    break;
            }

            if (false === preg_match($pattern, '')) {
                $param->setRequired(true);
            }

            $params->set($param);
        }

        $resource->setParams($params);
    }
}