<?php
namespace WidRestApiDocumentator\Service;

use WidRestApiDocumentator\Config\StandardConfig;
use WidRestApiDocumentator\Data\GenericData;
use WidRestApiDocumentator\DataSet\StandardSet;
use WidRestApiDocumentator\Exception\DomainException;
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
        if (null !== $limit) {
            $limit = ($limit > 0) ? (int)$limit : 10;
            $page = ($page > 1) ? ((int)$page - 1) : 0;
            $data = array_slice($data, $page * $limit, $limit);
        }

        foreach ($data as $id => $options) {
            $result->append($this->build($id, $options));
        }
        return $result;
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
}