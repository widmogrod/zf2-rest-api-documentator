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
                                'id' => '[a-zA-Z][a-zA-Z0-9_-]+',
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
);