<?php
namespace WidRestApiDocumentator\ParamSet;

use WidRestApiDocumentator\Param\GenericParam;
use WidRestApiDocumentator\ResourceInterface;

class UrlParamSet extends AbstractSet
{
    /**
     * Parameters regexp.
     *
     * @var string
     */
    const PARAM_REGEXP = '/<(?<name>[^>\/]+)>/';

    /**
     * Interpret the $resource and retrieve from it parameters witch the implementation is interested in.
     *
     * @param ResourceInterface $resource
     * @return void
     */
    public function interpret(ResourceInterface $resource)
    {
        $url = $resource->getUrl();
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            // TODO: Should throw exception ?
            return;
        }

        $self = $this;
        preg_replace_callback(self::PARAM_REGEXP, function ($matches) use($self) {
            $param = new GenericParam();
            $param->setName($matches['name']);
            $self->set($param);
            // make cleaner param
            return '<' . $param->getName() . '>';
        }, $path);
    }

    /**
     * Populate $resource with values for parameters witch the implementations is interested in.
     *
     * @param ResourceInterface $resource
     * @return mixed
     */
    public function populate(ResourceInterface $resource)
    {
        if (!count($this)) {
            return;
        }

        $self = $this;
        $url = preg_replace_callback(self::PARAM_REGEXP, function($matches) use ($self) {
            $name = $matches['name'];
            return $self->has($name) ? $self->get($name)->getValue() : null;
        }, $resource->getUrl());

        // update resource with $url values
        $resource->setUrl($url);
    }
}