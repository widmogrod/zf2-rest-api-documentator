<?php
namespace WidRestApiDocumentator\Resource;

use WidRestApiDocumentator\ParamSet\ParamSet;
use WidRestApiDocumentator\ParamSetInterface;
use WidRestApiDocumentator\ResourceInterface;

class StandardResource implements ResourceInterface
{
    protected $method;
    protected $queryParams;
    protected $urlParams;
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

    public function setQueryParams(ParamSetInterface $params)
    {
        $this->queryParams = $params;
    }

    public function getQueryParams()
    {
        if (null === $this->queryParams) {
            $this->queryParams = new ParamSet();
        }
        return $this->queryParams;
    }

    public function setUrlParams(ParamSetInterface $value)
    {
        $this->urlParams = $value;
    }

    public function getUrlParams()
    {
        if (null === $this->urlParams) {
            $this->urlParams = new ParamSet();
        }
        return $this->urlParams;
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