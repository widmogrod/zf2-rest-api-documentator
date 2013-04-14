<?php
namespace WidRestApiDocumentator;

interface ParamInterface
{
    const TYPE_MIXED = 'mixed';
    const TYPE_INTEGER = 'integer';
    const TYPE_STRING = 'string';

    public function setName($value);
    public function getName();
    public function setType($value);
    public function getType();
    public function setDescription($value);
    public function getDescription();
    public function setRequired($value);
    public function isRequired();
    public function setOptions(array $options);
}