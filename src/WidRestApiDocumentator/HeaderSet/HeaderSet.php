<?php
namespace WidRestApiDocumentator\HeaderSet;

use WidRestApiDocumentator\HeaderInterface;
use WidRestApiDocumentator\HeaderSetInterface;

class HeaderSet extends \ArrayIterator implements HeaderSetInterface
{
    public function __construct()
    {
        parent::__construct(array(), self::ARRAY_AS_PROPS);
    }

    public function set(HeaderInterface $header)
    {
        $this->offsetSet($header->getName(), $header);
    }

    public function get($header)
    {
        return $this->has($header) ? $this->offsetGet($header) : null;
    }

    public function has($header)
    {
        return $this->offsetExists($header);
    }
}