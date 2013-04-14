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
                'name' => 'api.example.com',
                'version' => '1.1',
                'baseUrl' => 'http://api.example.com/api',
                'strategy' => 'standard',
                'general' => array(
                    'params' => array(
                        'id' => array(
                            'type' => 'integer',
                            'required' => true,
                            'description' => 'Resource identificator'
                        ),
                        'limit' => array(
                            'type' => 'integer',
                            'description' => 'Limit API result to given value. Value must be between 1-100'
                        ),
                        'order' => array(
                            'type' => 'string',
                            'required' => false,
                            'description' => 'Retrieve API result ordered by given value'
                        ),
                    ),
                ),
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