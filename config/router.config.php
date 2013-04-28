<?php
return array(
    'router' => array(
        'routes' => array(
            'rest-api-docs' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/rest-api-docs',
                    'defaults' => array(
                        'controller' => 'WidRestApiDocumentator\Controller\Docs',
                        'action' => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'show' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:id',
                            'constraints' => array(
                                'id' => '[^/ ]+',
                            ),
                            'defaults' => array(
                                'action' => 'show',
                            ),
                        ),
                    ),
                ),
            ),
            'rest-api' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/rest-api',
                    'defaults' => array(
                        'controller' => 'WidRestApiDocumentator\Controller\Api',
                        'action' => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'api' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:id',
                            'constraints' => array(
                                'id' => '[^/ ]+',
                            ),
                            'defaults' => array(
                                'controller' => 'WidRestApiDocumentator\Controller\Api',
                            ),
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'get' => array(
                                'type' => 'Method',
                                'options' => array(
                                    'verb' => 'get',
                                    'defaults' => array(
                                        'action' => 'show',
                                    ),
                                ),
                            ),
                            'post' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/:endpoint',
                                    'constraints' => array(
                                        'endpoint' => '[\d]+',
                                    ),
                                    'defaults' => array(
                                        'action' => 'do',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);