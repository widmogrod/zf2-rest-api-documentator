# Module generating REST API documentation v1.0.0 [![Build Status](https://travis-ci.org/widmogrod/zf2-rest-api-documentator.png?branch=master)](https://travis-ci.org/widmogrod/zf2-rest-api-documentator)
## Introduction

This module allow to create quick documentation of your REST API.

Main features that I want to implement:

- [x] Generate REST API documentation HTML page.
- [x] Unlimited number of documented APIs
- [x] Elastic & simple to use.
- [ ] Well documented
- [ ] Posibility to test & play with API from docs page

## Installation

  1. `cd my/project/directory`
  2. Create a `composer.json` file with following content:

``` json
{
    "require": {
        "widmogrod/zf2-rest-api-documentator": "1.*"
    }
}
```

  3. Run `php composer.phar install`
  4. Open ``my/project/folder/configs/application.config.php`` and:
    - add ``'WidRestApiDocumentator'`` to your ``'modules'`` parameter.
    - add ``'AsseticBundle'`` to your ``'modules'`` parameter (optional if you want to include CSS)


## Usage

Bellow is php configuration file that show haw to implement simple REST API documentation in your module.
This is a minimal configuration required to achive result shown futher dawn.

```php
<?php
return array(
    // Configuration namespace within this module looking for data
	'zf2-rest-api-documentator' => array(
	    // Contains collection of documentation descriptions.
        'docs' => array(
            // Namespace of module within REST API description resides. Must be unique per module.
            'my_module_name' => array(
                'name' => 'api.example.com',
                'version' => '1.1',
                'baseUrl' => 'http://127.0.0.1:8080/api',
                // Strategy is way, in which this configuration will be interpreted.
                'strategy' => 'standard',
                // General description for common thing in module, to skip redundancy
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
                            'description' => 'Retrieve API result ordered by given value. Acceptable values: asc, desc.'
                        ),
                    ),
                ),
                // REST API Endpoings, here you describing your API
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

Below is example showing how this configuration will look like.

To see this result, enter in browser your application addres and go to route `/rest-api-docs`.

![Example API](https://raw.github.com/widmogrod/zf2-rest-api-documentator/master/assets/generated-api.png)

## Tips & treaks
### Setup you own route name to to documentation.


