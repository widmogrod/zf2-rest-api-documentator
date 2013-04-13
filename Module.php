<?php
namespace WidRestApiDocumentator;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class Module implements ConfigProviderInterface, AutoloaderProviderInterface, ServiceProviderInterface
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'RestApiDocs' => 'WidRestApiDocumentator\Service\Docs',
            ),
            'factories' => array(
                'WidRestApiDocumentator\Service\Docs' => function (ServiceLocatorInterface $service) {
                    $config = (array) $service->get('Config');
                    $options = (array) isset($config, $config['zf2-rest-api-documentator'], $config['zf2-rest-api-documentator']['docs']) ? $config['zf2-rest-api-documentator']['docs'] : array();
                    /** @var $strategyManager \WidRestApiDocumentator\StrategyManager */
                    $strategyManager = $service->get('WidRestApiDocumentator\StrategyManager');
                    return new Service\Docs($options, $strategyManager);
                },
                'WidRestApiDocumentator\StrategyManager' => function (ServiceLocatorInterface $service) {
                    $config = (array) $service->get('Config');
                    $options = (array)isset($config, $config['zf2-rest-api-documentator'], $config['zf2-rest-api-documentator']['strategies']) ? $config['zf2-rest-api-documentator']['strategies'] : array();
                    $config = new Config($options);
                    return new StrategyManager($config);
                },
            ),
        );
    }
}