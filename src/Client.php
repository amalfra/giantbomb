<?php

namespace Amalfra\GiantBomb;

use Amalfra\GiantBomb\Exceptions\ConfigException;
use Amalfra\GiantBomb\API\Games;
use Amalfra\GiantBomb\API\Reviews;
use Amalfra\GiantBomb\API\GameRatings;
use Amalfra\GiantBomb\API\Companies;
use Amalfra\GiantBomb\API\Characters;
use Amalfra\GiantBomb\API\Genres;
use Amalfra\GiantBomb\API\Platforms;
use Amalfra\GiantBomb\API\Search;

const METHOD_CLASS_MAP = array(
  'game' => Games::class,
  'games' => Games::class,
  'reviews' => Reviews::class,
  'review' => Reviews::class,
  'game_ratings' => GameRatings::class,
  'game_rating' => GameRatings::class,
  'companies' => Companies::class,
  'company' => Companies::class,
  'characters' => Characters::class,
  'character' => Characters::class,
  'genres' => Genres::class,
  'genre' => Genres::class,
  'platforms' => Platforms::class,
  'platform' => Platforms::class,
  'get' => Search::class,
);

/**
 * Class Client
 *
 * @package Amalfra\GiantBomb
 */
class Client {
  public static $base_url   = 'https://www.giantbomb.com/api/';
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

  /**
   * Calls underlying API implementation method
   *
   * @params  string       $method
   * @params  array        $args
   *
   */
  public function __call($method, $args) {
    $class_name = METHOD_CLASS_MAP[$method];
    return call_user_func_array(array($class_name, $method), $args);
  }
}
