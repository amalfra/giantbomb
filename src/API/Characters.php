<?php

namespace Amalfra\GiantBomb\API;

use Amalfra\GiantBomb\HTTP;

/**
 * Class Characters
 *
 * @package Amalfra\GiantBomb\API
 */
class Characters extends HTTP {
  public static function characters($options = array()) {
    self::validate($options, array(
      'field_list',
      'limit',
      'offset',
      'sort',
      'filter',
    ));

    return self::process_request('characters', $options);
  }

  public static function get_character($id = 0) {
    return self::process_request('character/'. $id);
  }
}
