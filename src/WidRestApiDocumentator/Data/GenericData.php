<?php
namespace WidRestApiDocumentator\Data;

use WidRestApiDocumentator\ConfigInterface;
use WidRestApiDocumentator\DataInterface;
use WidRestApiDocumentator\ResourceSetInterface;

class GenericData implements DataInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var ResourceSetInterface
     */
    protected $resourceSet;

    public function __construct($id, ConfigInterface $config, ResourceSetInterface $resourceSet)
    {
        $this->id = $id;
        $this->config = $config;
        $this->resourceSet = $resourceSet;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getResourceSet()
    {
        return $this->resourceSet;
    }

}