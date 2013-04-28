<?php
namespace WidRestApiDocumentator\Service;

use WidRestApiDocumentator\Config\StandardConfig;
use WidRestApiDocumentator\ConfigInterface;
use WidRestApiDocumentator\Data\GenericData;
use WidRestApiDocumentator\DataInterface;
use WidRestApiDocumentator\DataSet\StandardSet;
use WidRestApiDocumentator\DataSetInterface;
use WidRestApiDocumentator\Exception\DomainException;
use WidRestApiDocumentator\ParamInterface;
use WidRestApiDocumentator\ParamSetInterface;
use WidRestApiDocumentator\ResourceInterface;
use WidRestApiDocumentator\ResourceSetInterface;
use WidRestApiDocumentator\Strategy\Standard;
use WidRestApiDocumentator\StrategyInterface;
use WidRestApiDocumentator\StrategyManager;

class Docs
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var StrategyManager
     */
    protected $strategyManager;

    public function __construct(array $options, StrategyManager $strategyManager)
    {
        $this->options = $options;
        $this->strategyManager = $strategyManager;
    }

    public function build($id, array $options)
    {
        $config = new StandardConfig();
        $config->setOptions($options);
        /** @var $strategy StrategyInterface */
        $strategy = $this->strategyManager->get($config->getStrategy());
        $resourceSet = $strategy->parse($config);
        return new GenericData($id, $config, $resourceSet);
    }

    public function getList($page = null, $limit = null)
    {
        $result = new StandardSet();

        $data = $this->options;
        if ($limit > 0) {
            $limit = ($limit > 0) ? (int)$limit : 10;
            $page = ($page > 1) ? ((int)$page - 1) : 0;
            $data = array_slice($data, $page * $limit, $limit);
        }

        foreach ($data as $id => $options) {
            $result->append($this->build($id, $options));
        }
        return $result;
    }

    public function count()
    {
        return count($this->options);
    }

    public function getOne($id)
    {
        if (!array_key_exists($id, $this->options)) {
            $message = 'Such id "%s" does not exists';
            $message = sprintf($message, $id);
            throw new DomainException($message);
        }

        return $this->build($id, $this->options[$id]);
    }

    public function toArrayDataSet(DataSetInterface $dataSet) {
        $result = array();
        foreach ($dataSet as $data) {
            $result[] = $this->toArrayData($data);
        }
        return $result;
    }

    public function toArrayData(DataInterface $data) {
        return array(
            'id' => $data->getId(),
            'config' => $this->toArrayConfig($data->getConfig()),
            'resources' => $this->toArrayResourceSet($data->getResourceSet()),
        );
    }

    public function toArrayConfig(ConfigInterface $config) {
        // TODO add general params
        return array(
            'name' => $config->getName(),
            'baseUrl' => $config->getBaseUrl(),
            'version' => $config->getVersion(),
            'general' => $this->toArrayGeneral($config->getGeneral()),
        );
    }

    public function toArrayGeneral($general) {
        return $general;
    }

    public function toArrayResourceSet(ResourceSetInterface $resourceSet) {
        $result = array();
        foreach ($resourceSet as $resource) {
            $result[] = $this->toArrayResource($resource);
        }
        return $result;
    }

    public function toArrayResource(ResourceInterface $resource) {
        return array(
            'method' => $resource->getMethod(),
            'url' => $resource->getUrl(),
            'description' => $resource->getDescription(),
            'urlParams' => $this->toArrayParamSet($resource->getUrlParams()),
            'queryParams' => $this->toArrayParamSet($resource->getQueryParams()),
        );
    }

    public function toArrayParamSet(ParamSetInterface $paramSet) {
        $result = array();
        foreach ($paramSet as $param) {
            $result[] = $this->toArrayParam($param);
        }
        return $result;
    }

    public function toArrayParam(ParamInterface $param) {
        return array(
            'name' => $param->getName(),
            'type' => $param->getType(),
            'required' => $param->isRequired(),
            'description' => $param->getDescription(),
        );
    }
}