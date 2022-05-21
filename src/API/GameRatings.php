<?php

namespace Amalfra\GiantBomb\API;

use Amalfra\GiantBomb\HTTP;

/**
 * Class GameRatings
 *
 * @package Amalfra\GiantBomb\API
 */
class GameRatings extends HTTP {
  public static function game_ratings($options = array()) {
    self::validate($options, array(
      'field_list',
      'limit',
      'offset',
      'sort',
      'filter',
    ));

    return self::process_request('game_ratings', $options);
  }

  public static function get_game_rating($id = 0) {
    return self::process_request('game_rating/'. $id);
  }
}
