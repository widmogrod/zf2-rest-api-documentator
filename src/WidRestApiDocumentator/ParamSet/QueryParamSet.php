<?php
namespace WidRestApiDocumentator\ParamSet;

use WidRestApiDocumentator\Param\GenericParam;
use WidRestApiDocumentator\ParamInterface;
use WidRestApiDocumentator\ResourceInterface;

class QueryParamSet extends AbstractSet
{
    /**
     * Interpret the $resource and retrieve from it parameters witch the implementation is interested in.
     *
     * @param ResourceInterface $resource
     * @return void
     */
    public function interpret(ResourceInterface $resource)
    {
        $url = $resource->getUrl();
        $query = parse_url($url, PHP_URL_QUERY);
        if (!$query) {
            return;
        }

        // Do the magick
        parse_str($query, $data);

        foreach ((array) $data as $name => $value) {
            $param = new GenericParam();
            $param->setName($name);
            $param->setValue($value);
            $this->set($param);
        }
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

        $url = $resource->getUrl();
        $query = parse_url($url, PHP_URL_QUERY);
        if (!$query) {
            return;
        }

        $url = str_replace($query, null, $url);

        $query = array();
        foreach ($this as $param /** @var ParamInterface $param */) {
            $query[$param->getName()] = $param->getValue();
        }

        $url = trim($url);
        $url = rtrim($url, '?') . '?';
        $url .= http_build_query($query);

        // update resource with $url values
        $resource->setUrl($url);
    }
}