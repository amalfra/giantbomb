<?php

namespace Amalfra\GiantBomb\API;

use Amalfra\GiantBomb\HTTP;

/**
 * Class Search
 *
 * @package Amalfra\GiantBomb\API
 */
class Search extends HTTP {
  public static function search(...$options) {
    self::validate($options, array(
      'field_list',
      'limit',
      'page',
      'query',
      'resources',
    ), array('query'));

    return self::process_request('search', $options);
  }
}
