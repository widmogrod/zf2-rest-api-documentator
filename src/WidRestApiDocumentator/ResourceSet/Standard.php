<?php
namespace WidRestApiDocumentator\ResourceSet;

use WidRestApiDocumentator\ResourceInterface;
use WidRestApiDocumentator\ResourceSetInterface;

class Standard implements ResourceSetInterface {
    protected $resources = array();
    protected $pointer = 0;
    protected $count = 0;

    /**
     * @return ResourceInterface
     */
    public function current()
    {
        return $this->resources[$this->pointer];
    }

    /**
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        ++$this->pointer;
    }

    /**
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * @return boolean The return value will be casted to boolean and then evaluated.
     */
    public function valid()
    {
        return $this->pointer < $this->count;
    }

    /**
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * Count elements of an object
     *
     * @return integer The return value is cast to an integer.
     */
    public function count()
    {
        return $this->count;
    }

    public function append(ResourceInterface $resource)
    {
        $this->resources[] = $resource;
        $this->count++;
    }
}