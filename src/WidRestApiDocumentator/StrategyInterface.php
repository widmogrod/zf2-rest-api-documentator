<?php
namespace WidRestApiDocumentator;

interface StrategyInterface {
    public function parse(ConfigInterface $config);
}