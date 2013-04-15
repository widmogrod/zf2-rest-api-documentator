<?php
namespace WidRestApiDocumentator;

interface ResourceInterface
{
    public function setMethod($value);
    public function getMethod();
    public function setQueryParams(ParamSetInterface $value);

    /**
     * @return ParamSetInterface
     */
    public function getQueryParams();
    public function setUrlParams(ParamSetInterface $value);

    /**
     * @return ParamSetInterface
     */
    public function getUrlParams();
    public function setDescription($value);
    public function getDescription();
    public function setUrl($value);
    public function getUrl();
}