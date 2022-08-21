<?php

namespace Amalfra\GiantBomb;

use Redis;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Amalfra\GiantBomb\Exceptions\ConfigException;
use Amalfra\GiantBomb\Exceptions\UnsupportedException;
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
  'search' => Search::class,
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
  public static $debug = false;
  /**
   * The CacheProvider instance configured
   *
   * @type   Symfony\Component\Cache\Adapter;
   */
  private $cache;

  public function __construct($config = array()) {
    $this->validate($config);

    self::$auth_token = $config['token'];
    if (array_key_exists('debug', $config)) {
      self::$debug = $config['debug'];
    }

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
   * Creates a signature for the given request
   *
   * @param string $url     string name of url suffix
   * @param array  $params  array  parameters to send to API
   *
   * @return string
   */
  private function create_signature($url, $params) {
    $url = $url . '?' . http_build_query($params);

    return 'giantbomb-'.substr(sha1($url), 0, 7);
  }

  /**
   * Calls underlying API implementation method
   *
   * @params  string       $method
   * @params  array        $args
   *
   * @throws  Amalfra\GiantBomb\Exceptions\UnsupportedException    When not defined method is called
   */
  public function __call($method, $args) {
    // directly call methods defined in this class
    if (method_exists($this, $method)) {
      return call_user_func_array(array($this, $method), $args);
    }

    $class_name = METHOD_CLASS_MAP[$method];
    if (!isset($class_name)) {
      throw new UnsupportedException('This method is not supported.');
    }

    if ($this->cache) {
      $signature = $this->create_signature($method, $args);
      echo '[DEBUG] Checking for cache key: ' . $signature . "\n";
      $cache_item = $this->cache->getItem($signature);
      if ($cache_item->isHit()) {
        echo '[DEBUG] + Cache hit for cache key: ' . $signature . "\n";
        return $this->cache->getItem($signature)->get();
      }
    }

    $result = call_user_func_array(array($class_name, $method), $args);

    if ($this->cache) {
      $cache_item->set($result);
      $this->cache->save($cache_item);
    }

    return $result;
  }

  /**
   * Set type of cache to use
   *
   * @param $provider string supported values: inmemory, redis
   * @param $config   optional config required for cache type, eg: connection details for redis
   *
   * @return array response of API
   */
  public function set_cache_provider($provider = null, $config = array()) {
    echo '[DEBUG] Setting cache provider: ' . $provider . "\n";
    if ($provider) {
      switch ($provider) {
        case 'inmemory':
          $this->cache = new ArrayAdapter();
          break;
        case 'redis':
          if (!isset($config['host'])) {
            throw new ConfigException('host config not specified');
          }
          if (!isset($config['port'])) {
            throw new ConfigException('port config not specified');
          }
          $redis = new Redis();
          $redis->connect($config['host'], $config['port']);

          $this->cache = new RedisAdapter($redis);
          break;
        default:
          throw new ConfigException('Unsupported cache type: ' . $provider);
      };
    }
  }
}
