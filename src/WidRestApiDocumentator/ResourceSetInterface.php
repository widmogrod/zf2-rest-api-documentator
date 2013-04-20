<?php
namespace WidRestApiDocumentator;

interface ResourceSetInterface extends \Iterator, \Countable, \SeekableIterator
{
    public function append(ResourceInterface $resource);

    /**
     * @return ResourceInterface
     */
    public function current();
}