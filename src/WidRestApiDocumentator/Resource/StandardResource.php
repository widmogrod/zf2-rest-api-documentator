<?php
namespace WidRestApiDocumentator\Resource;

use WidRestApiDocumentator\ParamSet\ParamSet;
use WidRestApiDocumentator\ParamSetInterface;
use WidRestApiDocumentator\ResourceInterface;

class StandardResource implements ResourceInterface
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

    public function setParams(ParamSetInterface $params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        if (null === $this->params) {
            $this->params = new ParamSet();
        }
        return $this->params;
    }

    public function setUrl($uri)
    {
        $this->uri = $uri;
    }

    public function getUrl()
    {
        return $this->uri;
    }
}