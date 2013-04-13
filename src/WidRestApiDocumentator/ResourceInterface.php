<?php
namespace WidRestApiDocumentator;

interface ResourceInterface
{
    public function setMethod($value);
    public function getMethod();
    public function setParams(ParamSetInterface $value);

    /**
     * @return ParamSetInterface
     */
    public function getParams();
    public function setDescription($value);
    public function getDescription();
    public function setUrl($value);
    public function getUrl();
}