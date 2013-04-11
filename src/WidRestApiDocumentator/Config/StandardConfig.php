<?php
namespace WidRestApiDocumentator\Config;

use WidRestApiDocumentator\ConfigInterface;

class StandardConfig implements ConfigInterface
{
    protected $name;
    protected $version;
    protected $uri;
    protected $strategy;
    protected $resources;

    public function setOptions(array $array)
    {
        foreach ($array as $name => $value) {
            $method = 'set' . ucfirst($name);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function setUri($value)
    {
        $this->uri = $value;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setResources($value)
    {
        $this->resources = $value;
    }

    public function getResources()
    {
        return $this->resources;
    }

    public function setStrategy($value)
    {
        $this->strategy = $value;
    }

    public function getStrategy()
    {
        return $this->strategy;
    }

    public function setVersion($value)
    {
        $this->version = $value;
    }

    public function getVersion()
    {
        return $this->version;
    }
}