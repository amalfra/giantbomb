Giantbomb API
==============================
[![GitHub release](https://img.shields.io/github/release/amalfra/GiantBomb.svg)](https://github.com/amalfra/GiantBomb/releases)
![Build Status](https://github.com/amalfra/giantbomb/actions/workflows/test.yml/badge.svg?branch=main)
[![Coverage Status](https://coveralls.io/repos/github/amalfra/giantbomb/badge.svg)](https://coveralls.io/github/amalfra/giantbomb)

A library for easy interaction with Giantbomb API. Features are:
* Caching support

> Get your API Key at https://www.giantbomb.com/api

## Requirements
* PHP Redis extension. It can be installed using following command:
```sh
pecl install redis
```

## Installation
```sh
composer require amalfra/giantbomb
```
This will create a vendor directory (if you dont already have one) and set up the autoloading classmap.

## Usage
Once everything is installed, you should be able to load the composer autoloader in your code.

You can load the wrapper classes using namespace as:

```php
require __DIR__ . '/vendor/autoload.php';

use \Amalfra\GiantBomb\Client as GiantBomb;
```

Now create a new object

```php
$config = array(
	'token' => 'YOUR_KEY',
);

$gb_obj = new GiantBomb($config);
```

Now the available API methods can be called using the instance. All the result from API will be returned as an object. If any status code other than 200 is returned an exception would be thrown.

### Currently Available Methods
| Method | Description |
| --- | --- |
| game(game_id, field_list) | field_list should be comma seperated values |
| games(<br>&nbsp;&nbsp;&nbsp;&nbsp;field_list: 'name,id',<br>&nbsp;&nbsp;&nbsp;&nbsp;limit: 10,<br>&nbsp;&nbsp;&nbsp;&nbsp;offset: 20,<br>&nbsp;&nbsp;&nbsp;&nbsp;platforms: 2,<br>&nbsp;&nbsp;&nbsp;&nbsp;sort: 'id:desc',<br>&nbsp;&nbsp;&nbsp;&nbsp;filter: 'aliases:Desert Strike'<br>) | |
| company(company_id, field_list) | field_list should be comma seperated values |
| companies(<br>&nbsp;&nbsp;&nbsp;&nbsp;field_list: 'name,id',<br>&nbsp;&nbsp;&nbsp;&nbsp;limit: 10,<br>&nbsp;&nbsp;&nbsp;&nbsp;offset: 20,<br>&nbsp;&nbsp;&nbsp;&nbsp;sort: 'id:desc',<br>&nbsp;&nbsp;&nbsp;&nbsp;filter: 'id:7'<br>) | |
| genre(genre_id, field_list) | field_list should be comma seperated values |
| genres(<br>&nbsp;&nbsp;&nbsp;&nbsp;field_list: 'name,id',<br>&nbsp;&nbsp;&nbsp;&nbsp;limit: 10,<br>&nbsp;&nbsp;&nbsp;&nbsp;offset: 20<br>) | |
| platform(platform_id, field_list) | field_list should be comma seperated values |
| platforms(<br>&nbsp;&nbsp;&nbsp;&nbsp;field_list: 'name,id',<br>&nbsp;&nbsp;&nbsp;&nbsp;limit: 10,<br>&nbsp;&nbsp;&nbsp;&nbsp;offset: 20,<br>&nbsp;&nbsp;&nbsp;&nbsp;sort: 'id:desc',<br>&nbsp;&nbsp;&nbsp;&nbsp;filter: 'id:3'<br>) | |
| review(review_id, field_list) | field_list should be comma seperated values |
| reviews(<br>&nbsp;&nbsp;&nbsp;&nbsp;field_list: 'name,id',<br>&nbsp;&nbsp;&nbsp;&nbsp;limit: 10,<br>&nbsp;&nbsp;&nbsp;&nbsp;offset: 20,<br>&nbsp;&nbsp;&nbsp;&nbsp;sort: 'id:desc',<br>&nbsp;&nbsp;&nbsp;&nbsp;filter: 'id:3'<br>) | |

* game_rating(rating_id, field_list)
* game_ratings(filter, limit, offset, sort, field_list)
* character(character_id, field_list)
* characters(filter, limit, offset, sort, field_list)
* search(query, field_list, limit, page, resources)

### Cache
You can configure caching to prevent hitting API if same queries are made again. Currently supported caching methods are:
* inmemory: cache will be stored in memory array. This won't be persisted after your script exits.
* redis: cache will be stored in redis store which can be configured.

Cache can be configured using ```setCacheProvider``` method of GiantBomb instance. If it's not configured caching will be disabled and API will always be hit each time a method is called. ```setCacheProvider``` method accepts two parameter: 
1. [required] cache type eg: inmemory, redis etc
2. [optional] an associative array in which additional configuration details required for setting up the cache method can be given eg: redis server host and port values

#### using inmemory cache method
This method does not need any additional configuration option than just activating by calling ```setCacheProvider``` method with ```inmemory``` as first parameter.
eg:
```php
$gb_obj->setCacheProvider('inmemory');
```
#### using redis cache method
This method can be activated by calling ```setCacheProvider``` method with ```redis``` as first parameter. You will also need to specify redis server host and port as second parameter.
eg:
```php
$gb_obj->setCacheProvider('redis', array('host' => 'localhost', 'port' => 6379));
```

## Development

Questions, problems or suggestions? Please post them on the [issue tracker](https://github.com/amalfra/giantbomb/issues).

You can contribute changes by forking the project and submitting a pull request. Feel free to contribute :heart_eyes:

UNDER MIT LICENSE
=================

The MIT License (MIT)

Copyright (c) 2013 Amal Francis

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
