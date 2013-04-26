<?php
namespace WidRestApiDocumentator;

interface HeaderInterface {
    public function setName($value);
    public function getName();

    public function setDescription($value);
    public function getDescription();

    public function setRequired($value);
    public function isRequired();

    public function setValue($value);
    public function getValue();

    public function setOptions(array $options);
}