<?php
namespace WidRestApiDocumentator;

interface DataSetInterface extends \Iterator, \Countable
{
    public function append(DataInterface $data);
}