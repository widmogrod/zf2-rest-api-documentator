<?php
namespace WidRestApiDocumentator\Body;

use Zend\Json\Json;

class JsonBody extends GenericBody
{
    public function parse($value)
    {
        $result = array();
        $params = $this->getParams();
        foreach ((array)$value as $key => $value) {
            if ($params->has($key)) {
                $result[$key] = $value;
            }
        }

        $this->body = empty($result) ? null : Json::encode($result);
    }
}