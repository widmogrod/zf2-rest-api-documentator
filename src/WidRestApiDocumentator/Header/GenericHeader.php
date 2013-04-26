<?php
namespace WidRestApiDocumentator\Header;

use WidRestApiDocumentator\HeaderInterface;

class GenericHeader implements HeaderInterface
{
    protected $name;
    protected $value;
    protected $required = false;
    protected $description;

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

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}