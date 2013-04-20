<?php
namespace WidRestApiDocumentator\ResourceSet;

use WidRestApiDocumentator\Exception\OutOfBoundsException;
use WidRestApiDocumentator\ResourceInterface;
use WidRestApiDocumentator\ResourceSetInterface;

class StandardSet implements ResourceSetInterface
{
    protected $resources = array();
    protected $position = 0;
    protected $count = 0;

    /**
     * @return ResourceInterface
     */
    public function current()
    {
        return $this->resources[$this->position];
    }

    /**
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return boolean The return value will be casted to boolean and then evaluated.
     */
    public function valid()
    {
        return $this->position < $this->count;
    }

    /**
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->position = 0;
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

    /**
     * Seeks to a position
     *
     * @param int $position
     * @throws \WidRestApiDocumentator\Exception\OutOfBoundsException
     * @return void
     */
    public function seek($position)
    {
        $this->position = (int)$position;
        if (!$this->valid()) {
            $message = 'Invalid seek position %s';
            $message = sprintf($message, $position);
            throw new OutOfBoundsException($message);
        }
    }

    public function append(ResourceInterface $resource)
    {
        $this->resources[] = $resource;
        $this->count++;
    }
}