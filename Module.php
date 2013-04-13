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

class Module implements ConfigProviderInterface, AutoloaderProviderInterface, ServiceProviderInterface, ConsoleUsageProviderInterface
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
                    $config = $service->get('Config');
                    $options = (array)isset($config, $config['zf2-rest-api-documentator'], $config['zf2-rest-api-documentator']['docs']) ? $config['zf2-rest-api-documentator']['docs'] : array();
                    /** @var $strategyManager \WidRestApiDocumentator\StrategyManager */
                    $strategyManager = $service->get('WidRestApiDocumentator\StrategyManager');
                    return new Service\Docs($options, $strategyManager);
                },
                'WidRestApiDocumentator\StrategyManager' => function (ServiceLocatorInterface $service) {
                    $config = $service->get('Config');
                    $options = (array)isset($config, $config['zf2-rest-api-documentator']) ? $config['zf2-rest-api-documentator'] : array();
                    $config = new Config($options['strategies']);
                    return new StrategyManager($config);
                },
            ),
        );
    }


    /**
     * Returns an array or a string containing usage information for this module's Console commands.
     * The method is called with active Zend\Console\Adapter\AdapterInterface that can be used to directly access
     * Console and send output.
     *
     * If the result is a string it will be shown directly in the console window.
     * If the result is an array, its contents will be formatted to console window width. The array must
     * have the following format:
     *
     *     return array(
     *                'Usage information line that should be shown as-is',
     *                'Another line of usage info',
     *
     *                '--parameter'        =>   'A short description of that parameter',
     *                '-another-parameter' =>   'A short description of another parameter',
     *                ...
     *            )
     *
     * @param AdapterInterface $console
     * @return array|string|null
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return array(
            'apidoc test' => 'Test action'
        );
    }
}