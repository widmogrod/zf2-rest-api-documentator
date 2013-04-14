<?php
namespace WidRestApiDocumentator;

interface ParamSetInterface extends \Iterator, \Countable
{
    public function set(ParamInterface $param);

    /**
     * @param string $param
     * @return ParamInterface
     */
    public function get($param);

    /**
     * @param string $param
     * @return bool
     */
    public function has($param);
}