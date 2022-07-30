<?php

namespace Amalfra\GiantBomb\API;

use Amalfra\GiantBomb\HTTP;

/**
 * Class Genres
 *
 * @package Amalfra\GiantBomb\API
 */
class Genres extends HTTP {
  public static function genres(...$options) {
    self::validate($options, array(
      'field_list',
      'limit',
      'offset',
    ));

    return self::process_request('genres', $options);
  }

  public static function genre($id = 0, $field_list = null) {
    $options = array(
      'field_list' => $field_list,
    );

    return self::process_request('genre/'. $id, $options);
  }
}
