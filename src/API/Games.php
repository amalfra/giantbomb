<?php

namespace Amalfra\GiantBomb\API;

use Amalfra\GiantBomb\HTTP;

/**
 * Class Games
 *
 * @package Amalfra\GiantBomb\API
 */
class Games extends HTTP {
  public static function games(...$options) {
    self::validate($options, array(
      'field_list',
      'limit',
      'offset',
      'platforms',
      'sort',
      'filter',
    ));

    return self::process_request('games', $options);
  }

  public static function game($id = 0) {
    return self::process_request('game/'. $id);
  }
}
