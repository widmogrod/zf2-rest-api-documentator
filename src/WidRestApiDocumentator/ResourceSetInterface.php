<?php
namespace WidRestApiDocumentator;

interface ResourceSetInterface extends \Iterator, \Countable
{
    public function append(ResourceInterface $resource);
}