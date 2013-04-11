<?php
namespace WidRestApiDocumentator;

interface ParamSetInterface extends \Iterator, \Countable
{
    public function set(ParamInterface $param);
    public function get($param);
}