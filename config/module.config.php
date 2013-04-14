<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'WidRestApiDocumentator\Controller\Docs' => 'WidRestApiDocumentator\Controller\DocsController',
        ),
    ),

    'view_manager' => array(
        'template_map' => array(
            'wid-rest-api-documentator/docs/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'wid-rest-api-documentator/docs/list' => __DIR__ . '/../view/docs/list.phtml',
            'wid-rest-api-documentator/docs/show' => __DIR__ . '/../view/docs/show.phtml',
            'wid-rest-api-documentator/partials/params' => __DIR__ . '/../view/partials/params.phtml',
        ),
    ),
);