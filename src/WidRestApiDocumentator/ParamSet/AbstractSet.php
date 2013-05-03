<?php
namespace WidRestApiDocumentator\ParamSet;

use WidRestApiDocumentator\Exception\InvalidArgumentException;
use WidRestApiDocumentator\ParamInterface;
use WidRestApiDocumentator\ParamSetInterface;

abstract class AbstractSet extends \ArrayIterator implements ParamSetInterface
{
    public function __construct()
    {
        parent::__construct(array(), self::ARRAY_AS_PROPS);
    }

    public function set(ParamInterface $param)
    {
        $this->offsetSet($param->getName(), $param);
    }

    /**
     * @param string $param
     * @return null|ParamInterface
     */
    public function get($param)
    {
        return $this->has($param) ? $this->offsetGet($param) : null;
    }

    public function has($param)
    {
        return $this->offsetExists($param);
    }

    /**
     * Hydrate parameters set with $values.
     *
     * @param array|\Traversable $values
     * @throws InvalidArgumentException     When $values isn't traversable or array
     * @return mixed
     */
    public function setValues($values)
    {
        if (!is_array($values) && !($values instanceof \Traversable)) {
            $givenType = is_object($values) ? get_class($values) : gettype($values);
            $message = 'Argument should be array or instance of \Traversable, but type "%s" was given';
            $message = sprintf($message, $givenType);
            throw new InvalidArgumentException($message);
        }

        foreach ($values as $key => $value) {
            if ($this->has($key)) {
                $this->get($key)->setValue($value);
            }
        }
    }
}