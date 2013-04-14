<?php
return array(
    'assetic_configuration' => array(
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
                        'filters' => array(
                            '?CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            )
                        ),
                    ),
                    'zf2_rest_api_img' => array(
                        'assets' => array(
                            'img/*.png',
                        ),
                        'options' => array(
                            'move_raw' => true,
                        ),
                    ),
                ),
            ),
        ),
    ),
);