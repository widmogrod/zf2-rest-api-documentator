<?php
namespace WidRestApiDocumentator\Body;

use WidRestApiDocumentator\BodyInterface;
use WidRestApiDocumentator\ParamSet\ParamSet;
use WidRestApiDocumentator\ParamSetInterface;

class GenericBody implements BodyInterface
{
    protected $params;

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

    protected $body;

    public function parse($value)
    {
        $result = array();
        $params = $this->getParams();
        foreach ((array)$value as $key => $value) {
            if ($params->has($key)) {
                $result[$key] = $value;
            }
        }

        $this->body = http_build_query($result);
    }

    public function toString()
    {
        return $this->body;
    }
}