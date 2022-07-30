<?php

namespace Amalfra\GiantBomb\API;

use Amalfra\GiantBomb\HTTP;

/**
 * Class Companies
 *
 * @package Amalfra\GiantBomb\API
 */
class Companies extends HTTP {
  public static function companies($options = array()) {
    self::validate($options, array(
      'field_list',
      'limit',
      'offset',
      'sort',
      'filter',
    ));

    return self::process_request('companies', $options);
  }

  public static function company($id = 0) {
    return self::process_request('company/'. $id);
  }
}
