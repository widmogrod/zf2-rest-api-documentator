<?php
namespace WidRestApiDocumentator;

interface HeaderSetInterface extends \Traversable
{
    public function set(HeaderInterface $header);

    /**
     * @param string $header
     * @return HeaderInterface
     */
    public function get($header);

    /**
     * @param string $header
     * @return bool
     */
    public function has($header);
}