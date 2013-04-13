# Module generating REST API documentation [![Build Status](https://travis-ci.org/widmogrod/zf2-rest-api-documentator.png?branch=master)](https://travis-ci.org/widmogrod/zf2-rest-api-documentator)
## Exclamation

This module is still in development phase.

## Introduction

This module allow to create quick documentation of your REST API.

Main features that I want to implement:

- Generate REST API documentation
- Unlimited number of documented APIs
- Posibility to test & play with API from docs page
- Elastic & simple to use.

## Installation
TBD

## Usage

This is my wish...

```php
<?php
return array(
	'zf2-rest-api-documentator' => array(
	    'strategies' => array(
            'invokable' => array(
                'myStrategy' => 'WidRestApiDocumentator\Strategy\Standard',
            ),
        ),
        'docs' => array(
            'simple' => array(
                'name' => 'api.example.com',
                'version' => '1.1',
                'baseUrl' => 'http://127.0.0.1:8080/api',
                'strategy' => 'myStrategy',
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
                            'type' => 'enum',
                            'required' => false,
                            'default' => 'asc',
                            'enum' => array('asc', 'desc'),
                            'description' => 'Retrieve API result ordered by given value'
                        ),
                    ),
                ),
                'resources' => array(
                    'GET: /keywords' => 'Fetch list of keywords',
                    'GET: /keywords/{<id>[\d]+}' => 'Fetch specific keyword <id>',
                    'GET: /keywords/{<id>[\d]+}/search_engines?limit={[\d+]}&order={(asc|desc)}',
                    'GET: /keywords/{<id>[\d]+}/domains_positions_in_search_engine',
                ),
            ),
        ),
	),
);

```
