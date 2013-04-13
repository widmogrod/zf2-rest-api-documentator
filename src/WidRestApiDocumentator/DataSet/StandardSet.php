<?php
namespace WidRestApiDocumentator\DataSet;

use WidRestApiDocumentator\DataInterface;
use WidRestApiDocumentator\DataSetInterface;

class StandardSet implements DataSetInterface {
    protected $data = array();
    protected $pointer = 0;
    protected $count = 0;

    /**
     * @return DataInterface
     */
    public function current()
    {
        return $this->data[$this->pointer];
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

    public function append(DataInterface $resource)
    {
        $this->data[] = $resource;
        $this->count++;
    }
}