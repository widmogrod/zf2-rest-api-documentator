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
                        'route'    => 'rest docs test',
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