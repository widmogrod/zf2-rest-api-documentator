<?php
namespace WidRestApiDocumentator;

interface BodyInterface
{
    public function setParams(ParamSetInterface $value);

    /**
     * @return ParamSetInterface
     */
    public function getParams();

    public function parse($value);

    public function toString();
}