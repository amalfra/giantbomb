<?php

namespace Amalfra\GiantBomb;

use \InvalidArgumentException;
use WpOrg\Requests\Requests;
use Amalfra\GiantBomb\Exceptions\HTTPException as HTTPException;

/**
 * Class HTTP
 *
 * @package Amalfra\GiantBomb
 */
class HTTP {
  protected static $http_error_msgs = array(
    401 => 'There was an error authenticating with the token.',
    403 => 'There was an error authenticating with the token.',
    500 => '500: Some error occured at GiantBomb servers.'
  );

  protected static function validate($options = array(), $valid = array(), $required = array()) {
    if (!is_array($options)) {
      throw new InvalidArgumentException('Parameters need to be passed as array');
    }

    $options = array_keys($options);

    foreach ($options as $k => $v) {
      if (!in_array($v, $valid)) {
        throw new InvalidArgumentException('Not a valid parameter passed');
      }
    }

    if (count($required) && !array_intersect($options, $required)) {
      throw new InvalidArgumentException('Required parameter not passed');
    }
  }

  protected static function tf_to_string(&$value, $key) {
    if ($value === true) {
      $value = 'true';
    } else if ($value === false) {
      $value = 'false';
    }
  }

  protected static function inject(&$options) {
    $options = array_merge($options, array(
      'api_key' => Client::$auth_token,
      'format' => 'json',
    ));
  }

  private static function request($path, $options, $method = 'GET') {
    if (strtoupper($method) == 'GET') {
      return Requests::get(Client::$base_url.$path.'?'.http_build_query($options), Client::$headers, $options);
    } else if (strtoupper($method) == 'POST') {
      return Requests::post(Client::$base_url.$path, Client::$headers, json_encode($options));
    } else if (strtoupper($method) == 'DELETE') {
      return Requests::delete(Client::$base_url.$path.'?'.http_build_query($options), Client::$headers, $options);
    } else {
      throw new HTTPException('Unknown HTTP request method');
    }
  }

  protected static function handle_response($resp) {
    if ($resp->status_code == 204) {
      return true;
    }

    $decoded = @json_decode($resp->body, true);
    if (!$decoded) {
      throw new HTTPException((isset(self::$http_error_msgs[$resp->status_code])) ?
        self::$http_error_msgs[$resp->status_code] : 'An HTTP error with status code '. $resp->status_code .' occured');
    } else {
      return $decoded;
    }
  }

  protected static function process_request($path, $options = array(), $method = 'GET') {
    self::inject($options);
    array_walk($options, 'self::tf_to_string');

    $resp = self::request($path, $options, $method);
    return self::handle_response($resp);
  }
}
