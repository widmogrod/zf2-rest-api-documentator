<?php
namespace WidRestApiDocumentator;

use WidRestApiDocumentator\Exception\RuntimeException;
use WidRestApiDocumentator\StrategyInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface as ZendConfigInterface;

class StrategyManager extends AbstractPluginManager
{
    /**
     * Default set of strategies
     *
     * @var array
     */
    protected $invokableClasses = array(
        'standard' => 'WidRestApiDocumentator\Strategy\Standard',
    );

    /**
     * Whether or not to share by default; default to true
     *
     * @var bool
     */
    protected $shareByDefault = true;

    /**
     * Constructor
     *
     * After invoking parent constructor, add an initializer to inject the
     * attached translator, if any, to the currently requested helper.
     *
     * @param  null|ZendConfigInterface $configuration
     */
    public function __construct(ZendConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
    }

    /**
     * Validate the plugin
     *
     * Checks that the strategy loaded is an instance of StrategyInterface.
     *
     * @param  mixed $plugin
     * @throws \WidRestApiDocumentator\Exception\RuntimeException if invalid
     * @return void
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof StrategyInterface) {
            // we're okay
            return;
        }

        throw new RuntimeException(sprintf(
            'Strategy of type %s is invalid; must implement %s\StrategyInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }
}
