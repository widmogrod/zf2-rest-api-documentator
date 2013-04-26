<?php
namespace WidRestApiDocumentator\Body;

use WidRestApiDocumentator\BodyInterface;
use WidRestApiDocumentator\ParamSetInterface;

class NullBody implements BodyInterface
{
    public function setParams(ParamSetInterface $value)
    {}

    public function getParams()
    {}

    public function parse($value)
    {}

    public function toString()
    {}
}