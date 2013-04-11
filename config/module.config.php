<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'WidRestApiDocumentator\Controller\Console' => 'WidRestApiDocumentator\Controller\ConsoleController',
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'rest-docs-test' => array(
                    'options' => array(
                        'route'    => 'apidoc test',
                        'defaults' => array(
                            'controller' => 'WidRestApiDocumentator\Controller\Console',
                            'action'     => 'test'
                        )
                    )
                )
            )
        )
    ),

    'zf2-api-documentator' => array(
        'simple' => array(
            'name' => 'api.saturnanalitic.com',
            'version' => '1.1',
            'baseUrl' => 'http://127.0.0.1:8080/api',
            'strategy' => 'simple',
            'resources' => array(
                'GET: /keywords' => 'Fetch list of keywords',
                'GET: /keywords/{<id>[\d]+}' => 'Fetch specific keyword',
                'GET: /keywords/{<id>[\d]+}/search_engines?limit={[\d+]}&order={(asc|desc)}',
                'GET: /keywords/{<id>[\d]+}/domains_positions_in_search_engine',
            ),
        ),
    ),
);