<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'WidRestApiDocumentator\Controller\Console' => 'WidRestApiDocumentator\Controller\ConsoleController',
            'WidRestApiDocumentator\Controller\Docs' => 'WidRestApiDocumentator\Controller\DocsController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'api-docs' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/api-docs',
                    'defaults' => array(
                        'controller' => 'WidRestApiDocumentator\Controller\Docs',
                        'action' => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'show' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:id]',
                            'constraints' => array(
                                'id' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'action' => 'show',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'rest-docs-test' => array(
                    'options' => array(
                        'route' => 'apidoc test',
                        'defaults' => array(
                            'controller' => 'WidRestApiDocumentator\Controller\Console',
                            'action' => 'test'
                        )
                    )
                )
            )
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'wid-rest-api-documentator/docs/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'wid-rest-api-documentator/docs/list' => __DIR__ . '/../view/docs/list.phtml',
            'wid-rest-api-documentator/docs/show' => __DIR__ . '/../view/docs/show.phtml',
        ),
    ),

    'zf2-rest-api-documentator' => array(
        'strategies' => array(
            'invokable' => array(
                'standard' => 'WidRestApiDocumentator\Strategy\Standard',
            ),
        ),
        'docs' => array(
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
    ),


);