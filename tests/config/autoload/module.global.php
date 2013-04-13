<?php
return array(
    'zf2-rest-api-documentator' => array(
        'strategies' => array(
            'invokable' => array(
                'standard' => 'WidRestApiDocumentator\Strategy\Standard',
            ),
        ),
        'docs' => array(
            'simple' => array(
                'name' => 'api.saturnanalitic.com',
                'version' => '1.1',
                'baseUrl' => 'http://127.0.0.1:8080/api',
                'strategy' => 'standard',
                'resources' => array(
                    'GET: /keywords' => 'Fetch list of keywords',
                    'GET: /keywords/{<id>[\d]+}' => 'Fetch specific keyword',
                    'GET: /keywords/{<id>[\d]+}/search_engines?limit={[\d+]}&order={(asc|desc)}',
                    'GET: /keywords/{<id>[\d]+}/domains_positions_in_search_engine',
                ),
            ),
        ),
    ),
);