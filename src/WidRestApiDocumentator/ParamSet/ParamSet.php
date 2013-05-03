<?php
namespace WidRestApiDocumentator\ParamSet;

use WidRestApiDocumentator\ParamInterface;
use WidRestApiDocumentator\ParamSetInterface;
use WidRestApiDocumentator\ResourceInterface;

class ParamSet extends AbstractSet
{
    /**
     * Interpret the $resource and retrieve from it parameters witch the implementation is interested in.
     *
     * @param ResourceInterface $resource
     * @return void
     */
    public function interpret(ResourceInterface $resource)
    {
        // not interested of given context
    }

    /**
     * Populate $resource with values for parameters witch the implementations is interested in.
     *
     * @param ResourceInterface $resource
     * @return mixed
     */
    public function populate(ResourceInterface $resource)
    {
        // not interested of given context
    }
}