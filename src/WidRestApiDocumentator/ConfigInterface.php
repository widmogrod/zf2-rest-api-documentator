<?php
namespace WidRestApiDocumentator;

interface ConfigInterface
{
    public function setName($value);

    public function getName();

    public function setVersion($value);

    public function getVersion();

    public function setUri($value);

    public function getUri();

    public function setStrategy($value);

    public function getStrategy();

    public function setResources($value);

    public function getResources();

    public function setGeneral($value);

    public function getGeneral();

    public function setOptions(array $array);
}