<?php
namespace WidRestApiDocumentator\Resource;

use WidRestApiDocumentator\ResourceInterface;

class Standard implements ResourceInterface
{
    protected $method;
    protected $params;
    protected $description;
    protected $uri;

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }
}