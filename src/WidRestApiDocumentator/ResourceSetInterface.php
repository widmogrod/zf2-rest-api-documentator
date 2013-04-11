<?php
namespace WidRestApiDocumentator;

interface ResourceInterface
{
    public function setMethod($value);
    public function getMethod();
    public function setParams($value);
    public function getParams();
    public function setDescription($value);
    public function getDescription();
    public function setUri($value);
    public function getUri();
}