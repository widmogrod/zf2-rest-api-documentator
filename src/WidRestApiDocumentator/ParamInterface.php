<?php
namespace WidRestApiDocumentator;

interface ParamInterface
{
    const TYPE_MIXED = 1;
    const TYPE_INTEGER = 2;
    const TYPE_STRING = 4;

    public function setName($value);
    public function getName();
    public function setType($value);
    public function getType();
    public function setDescription($value);
    public function getDescription();
    public function setRequired($value);
    public function isRequired();
}