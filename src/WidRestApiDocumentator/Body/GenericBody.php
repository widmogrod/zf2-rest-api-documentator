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

    public function parse($value)
    {}

    public function toString()
    {}
}