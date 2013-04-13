<?php
return array(
    'modules' => array(
        'WidRestApiDocumentator',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'tests/config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            'WidRestApiDocumentator' => __DIR__
        ),
    ),
);
