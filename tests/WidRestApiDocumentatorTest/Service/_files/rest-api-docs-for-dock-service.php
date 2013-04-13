<?php
return array(
    'simple' => array(
        'name' => 'api.saturnanalitic.com',
        'version' => '1.1',
        'baseUrl' => 'http://127.0.0.1:8080/api',
        'strategy' => 'standard',
        'resources' => array(
            'GET: /keywords' => 'Fetch list of keywords',
        ),
    ),
    'simple2' => array(
        'name' => 'api.saturnanalitic.com',
        'version' => '1.2',
        'baseUrl' => 'http://127.0.0.1:8080/api',
        'strategy' => 'standard',
        'resources' => array(
            'GET: /keywords/{<id>[\d]+}/search_engines?limit={[\d+]}&order={(asc|desc)}',
        ),
    ),
    'simple3' => array(
        'name' => 'api.saturnanalitic.com',
        'version' => '1.3',
        'baseUrl' => 'http://127.0.0.1:8080/api',
        'strategy' => 'standard',
        'resources' => array(
            'GET: /keywords/{<id>[\d]+}/domains_positions_in_search_engine',
        ),
    ),
);