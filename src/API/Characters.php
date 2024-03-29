<?php

namespace Amalfra\GiantBomb\API;

use Amalfra\GiantBomb\HTTP;

/**
 * Class Characters
 *
 * @package Amalfra\GiantBomb\API
 */
class Characters extends HTTP {
  public static function characters(...$options) {
    self::validate($options, array(
      'field_list',
      'limit',
      'offset',
      'sort',
      'filter',
    ));

    return self::process_request('characters', $options);
  }

  public static function character($id = 0, $field_list = null) {
    $options = array(
      'field_list' => $field_list,
    );

    return self::process_request('character/'. $id, $options);
  }
}
