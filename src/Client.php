<?php

namespace Amalfra\GiantBomb;

use Amalfra\GiantBomb\Exceptions\ConfigException;
use Amalfra\GiantBomb\API\Games;
use Amalfra\GiantBomb\API\Reviews;
use Amalfra\GiantBomb\API\GameRatings;
use Amalfra\GiantBomb\API\Companies;

/**
 * Class Client
 *
 * @package Amalfra\GiantBomb
 */
class Client {
  public static $base_url   = 'http://www.giantbomb.com/api/';
  public static $auth_token = null;
  public static $headers    = array();

  public function __construct($config = array()) {
    $this->validate($config);

    self::$auth_token = $config['token'];

    self::$headers = array(
      'Content-Type' => 'application/json',
      'Accept'       => 'application/json'
    );
  }

  /**
   * Validates the configuration options
   *
   * @params  array       $config
   *
   * @throws  Amalfra\GiantBomb\Exceptions\ConfigException    When a config value does not meet its validation criteria
   */
  private function validate($config) {
    if (!isset($config['token'])) {
      throw new ConfigException('Token is required.');
    }
  }

  public function games($options = array()) {
    return Games::games($options);
  }

  public function game($id = 0) {
    return Games::get_game($id);
  }

  public function reviews($options = array()) {
    return Reviews::reviews($options);
  }

  public function review($id = 0) {
    return Reviews::get_review($id);
  }

  public function game_ratings($options = array()) {
    return GameRatings::game_ratings($options);
  }

  public function game_rating($id = 0) {
    return GameRatings::get_game_rating($id);
  }

  public function companies($options = array()) {
    return Companies::companies($options);
  }

  public function company($id = 0) {
    return Companies::get_company($id);
  }
}
