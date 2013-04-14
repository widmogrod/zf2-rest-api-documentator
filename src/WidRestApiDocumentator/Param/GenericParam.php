<?php
namespace WidRestApiDocumentator\Param;

use WidRestApiDocumentator\ParamInterface;

class GenericParam implements ParamInterface
{
    protected $name;
    protected $type = self::TYPE_MIXED;
    protected $required = false;
    protected $description;
    protected $availableTypes = array(
        self::TYPE_MIXED => true,
        self::TYPE_INTEGER => true,
        self::TYPE_STRING => true,
    );

    public function setOptions(array $array)
    {
        foreach ($array as $name => $value) {
            $method = 'set' . ucfirst($name);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function setDescription($description)
    {
        $this->description = (string) $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setName($name)
    {
        $this->name = (string) $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setRequired($required)
    {
        $this->required = (bool) $required;
    }

    public function isRequired()
    {
        return $this->required;
    }

    public function setType($type)
    {
        if (isset($this->availableTypes[$type])) {
            $this->type = $type;
        } else {
            $this->type = self::TYPE_MIXED;
        }
    }

    public function getType()
    {
        return $this->type;
    }
}