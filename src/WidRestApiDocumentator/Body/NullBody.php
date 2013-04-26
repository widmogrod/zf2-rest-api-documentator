<?php
namespace WidRestApiDocumentator\Body;

use WidRestApiDocumentator\BodyInterface;

class NullBody implements BodyInterface
{
    public function toString()
    {
        return null;
    }
}