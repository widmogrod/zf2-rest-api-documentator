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
);