<?php
namespace WidRestApiDocumentator;

interface ConfigInterface
{
    public function setName($value);

    public function getName();

    public function setVersion($value);

    public function getVersion();

    public function setBaseUrl($value);

    public function getBaseUrl();

    public function setStrategy($value);

    public function getStrategy();

    public function setResources($value);

    public function getResources();
}