<?php
namespace WidRestApiDocumentator\ParamSet;

use WidRestApiDocumentator\ParamInterface;
use WidRestApiDocumentator\ParamSetInterface;

class ParamSet extends \ArrayIterator implements ParamSetInterface
{
    public function __construct()
    {
        parent::__construct(array(), self::ARRAY_AS_PROPS);
    }

    public function set(ParamInterface $param)
    {
        $this->offsetSet($param->getName(), $param);
    }

    public function get($param)
    {
        return $this->has($param) ? $this->offsetGet($param) : null;
    }

    public function has($param)
    {
        return $this->offsetExists($param);
    }
}