<?php

namespace Amalfra\GiantBomb\API;

use Amalfra\GiantBomb\HTTP;

/**
 * Class Platforms
 *
 * @package Amalfra\GiantBomb\API
 */
class Platforms extends HTTP {
  public static function platforms($options = array()) {
    self::validate($options, array(
      'field_list',
      'limit',
      'offset',
      'filter',
      'sort',
    ));

    return self::process_request('platforms', $options);
  }

  public static function platform($id = 0) {
    return self::process_request('platform/'. $id);
  }
}
