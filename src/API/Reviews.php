<?php

namespace Amalfra\GiantBomb\API;

use Amalfra\GiantBomb\HTTP;

/**
 * Class Reviews
 *
 * @package Amalfra\GiantBomb\API
 */
class Reviews extends HTTP {
  public static function reviews($options = array()) {
    self::validate($options, array(
      'field_list',
      'limit',
      'offset',
      'sort',
      'filter',
    ));

    return self::process_request('reviews', $options);
  }

  public static function review($id = 0) {
    return self::process_request('review/'. $id);
  }
}
