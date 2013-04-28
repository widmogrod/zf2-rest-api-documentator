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
                'baseUrl' => 'http://127.0.0.1:8080/api',
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
                    'POST: /keywords' => array(
                        'body' => array(
                            'type' => 'WidRestApiDocumentator\Body\JsonBody',
                            'params' => array(
                                'name' => array(
                                    'type' => 'string',
                                    'required' => true,
                                    'description' => 'Keyword name',
                                ),
                            ),
                        ),
                    ),
                    'GET: /keywords/<id>' => 'Fetch specific keyword',
                    'PUT: /keywords/<id>' => array(
                        'description' => 'Update specific keyword information',
                        'body' => array(
                            'params' => array(
                                'name' => array(
                                    'type' => 'string',
                                    'required' => true,
                                    'description' => 'Keyword name',
                                ),
                            ),
                        ),
                    ),
                    'DELETE: /keywords/<id>' => 'Delete specific keyword',
                    'GET: /keywords/<id>/search_engines?limit=&order=',
                    'GET: /keywords/<id>/domains_positions_in_search_engine',
                    'PUT: /users/me' => array(
                        'description' => 'Demonstration endpoint that use headers & body params',
                        'headers' => array(
                            'X-Login' => array(
                                'type' => 'string',
                                'required' => true,
                                'description' => 'Header is test header. Can contains i.e. OAuth token.'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);