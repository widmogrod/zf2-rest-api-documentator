<?php
namespace WidRestApiDocumentator\HeadersSet;

use WidRestApiDocumentator\HeaderInterface;
use WidRestApiDocumentator\HeadersSetInterface;

class HeadersSet extends \ArrayIterator implements HeadersSetInterface
{
    public function __construct()
    {
        parent::__construct(array(), self::ARRAY_AS_PROPS);
    }

    public function set(HeaderInterface $header)
    {
        $this->offsetSet($header->getFieldName(), $header);
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