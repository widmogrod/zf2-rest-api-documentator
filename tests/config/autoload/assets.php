<?php
return array(
    'assetic_configuration' => array(
        // Use on development environment
        'debug' => true,
        'buildOnRequest' => true,

        'basePath' => 'public/assets',
        'webPath' => realpath('public/assets'),
    ),
);