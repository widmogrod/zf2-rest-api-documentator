<?php
namespace WidRestApiDocumentator;

interface DataInterface
{
    public function __construct($id, ConfigInterface $config, ResourceSetInterface $resourceSet);

    public function getId();

    /**
     * @return ConfigInterface
     */
    public function getConfig();

    /**
     * @return ResourceSetInterface
     */
    public function getResourceSet();
}