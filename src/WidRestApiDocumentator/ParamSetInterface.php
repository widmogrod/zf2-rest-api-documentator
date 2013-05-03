<?php
namespace WidRestApiDocumentator;

use WidRestApiDocumentator\Exception\InvalidArgumentException;

interface ParamSetInterface extends \Iterator, \Countable
{
    /**
     * @param ParamInterface $param
     * @return mixed
     */
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

    /**
     * Hydrate parameters set with $values.
     *
     * @param array|\Traversable $values
     * @throws InvalidArgumentException     When $values isn't traversable or array
     * @return mixed
     */
    public function setValues($values);

    /**
     * Interpret the $resource and retrieve from it parameters witch the implementation is interested in.
     *
     * @param ResourceInterface $resource
     * @return void
     */
    public function interpret(ResourceInterface $resource);

    /**
     * Populate $resource with values for parameters witch the implementations is interested in.
     *
     * @param ResourceInterface $resource
     * @return mixed
     */
    public function populate(ResourceInterface $resource);
}