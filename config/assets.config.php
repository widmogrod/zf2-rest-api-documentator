<?php
return array(
    'assetic_configuration' => array(
        'basePath' => 'public/assets',
        'controllers' => array(
            'WidRestApiDocumentator\Controller\Docs' => array(
                '@zf2_rest_api_css',
            ),
        ),

        'modules' => array(
            'WidRestApiDocumentator' => array(
                'root_path' => __DIR__ . '/../assets',
                'collections' => array(
                    'zf2_rest_api_css' => array(
                        'assets' => array(
                            'css/bootstrap.css',
                            'css/main.css',
                        ),
                    ),
                ),
            ),
        ),
    ),
);