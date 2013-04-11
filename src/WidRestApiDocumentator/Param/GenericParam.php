<?php
namespace WidRestApiDocumentator\Param;

use WidRestApiDocumentator\ParamInterface;

class GenericParam implements ParamInterface
{
    protected $name;
    protected $type = self::TYPE_MIXED;
    protected $required = false;
    protected $description;

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setRequired($required)
    {
        $this->required = $required;
    }

    public function isRequired()
    {
        return $this->required;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}