<?php

namespace Amalfra\GiantBomb;

use \Redis;
use \Exception;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\RedisCache;

/**
 * GiantBomb PHP wrapper is a simple class written in PHP to
 * make interactions with GiantBomb API easier.
 *
 * @package    Amalfra\GiantBomb
 */
class GiantBomb {
  /**
   * The API key
   *
   * @type   string
   */
  private $api_key = '';
  
  /**
   * The api response type : json/xml
   *
   * @type   string
   */
  private $resp_type = 'json';
  
  /**
   * The CacheProvider instance configured
   *
   * @type   Doctrine\Common\Cache\CacheProvider
   */
  private $cache;

  /**
   * A variable to hold the formatted result of  
   * last api request
   *
   * @type   array
   */
  public $result = array();

  /**
   * Set type of cache to use
   *
   * @param $provider string supported values: inmemory, redis
   * @param $config   optional config required for cache type, eg: connection details for redis
   *
   * @return array response of API
   */
  public function setCacheProvider($provider = null, $config = array()) {
    if ($provider) {
      switch ($provider) {
        case 'inmemory':
          $this->cache = new ArrayCache();
          break;
        case 'redis':
          if (!isset($config['host'])) {
            throw new GiantBombException('host config not specified');
          }
          if (!isset($config['port'])) {
            throw new GiantBombException('port config not specified');
          }
          $redis = new Redis();
          $redis->connect($config['host'], $config['port']);

          $this->cache = new RedisCache();
          $this->cache->setRedis($redis);
          break;
        default:
          throw new GiantBombException('Unsupported cache type: ' . $provider);
      };
    }
  }

  /**
   * Send call to API
   *
   * @param $module string name of url suffix
   * @param $params array  get parameters to send to API
   *
   * @return array response of API
   */
  private function call($module, $params = array()) {
    // set api data
    $params['api_key'] = $this->api_key;
    $params['format']  = $this->resp_type;

    // build URL
    $url = $this->endpoint . $module . '?' . http_build_query($params);

    // Set URL 
    curl_setopt($this->ch, CURLOPT_URL, $url);

    // Send the request & save response to $resp
    $resp['data'] = curl_exec($this->ch);
    if (curl_errno($this->ch)) {
      throw new GiantBombException('API call failed: ' . curl_error($this->ch));
    }

    // save http response code
    $resp['httpCode'] = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

    if (!$resp || !$resp['data']) {
      throw new GiantBombException("Couldn't get information from API");
    }

    return $resp;
  }

  /**
   * Format filter array to string
   *
   * @param $filters array list of filters
   *
   * @return string combined filter string
   */
  private function format_filter($filters = array()) {
    $filters_merged = array();
    foreach ($filters as $ky => $vl) {
      $filters_merged[] = $ky . ':' . $vl;
    }

    return implode(',', $filters_merged);
  }

  /**
   * Get information about given object type
   *
   * @param  $id         string  ID to request
   * @param  $field_list array   list of fields to response
   *
   * @return array response
   */
  public function get_object($type, $id, $field_list = array()) {
    if ($this->cache) {
      $signature = $this->createSignature($type . '/' . $id, $field_list);
      if ($this->cache->contains($signature)) {
        return $this->cache->fetch($signature);
      }
    }

    $resp = $this->call($type . '/' . $id, array('field_list' => implode(',', $field_list)));

    // No game/review/company/character with given id found
    if ($resp['httpCode'] == 404) {
      throw new GiantBombException("Couldn't find " . $type . ' with ' . $type . ' id "' . $id . '"');
    }

    $result = $this->parse_result($resp['data']);

    if ($this->cache) {
      $this->cache->save($signature, $result);
    }

    return $result;
  }

  /**
   * Get list of objects by given filters
   *
   * @param $type           string  type of objects to fetch
   * @param $param_options  array   an associative array of query params
   *
   * @return array response
   */
  public function get_objects($type, $param_options = array()) {
    if ($this->cache) {
      $signature = $this->createSignature($type . '/', $param_options);
      if ($this->cache->contains($signature)) {
        return $this->cache->fetch($signature);
      }
    }

    $resp = $this->call($type, $param_options);
    $result = $this->parse_result($resp['data']);

    if ($this->cache) {    
      $this->cache->save($signature, $result);
    }

    return $result;
  }

  /**
   * Get character by id
   *
   * @param  $id         string  ID to request
   * @param  $field_list array   list of fields to response
   *
   * @return array response
   */
  public function character($character_id, $field_list = array()) {
    return $this->get_object('character', $character_id, $field_list);
  }

  /**
   * Perform a search with given keyword
   *
   * @param  $query      string  keyword to search
   * @param  $field_list array   list of fields to response
   * @param  $limit      integer limit result count by given limit
   * @param  $page       integer page number of search results
   * @param  $resources  array   list of resources to filter results
   *
   * @return array response
   */
  public function search($query, $field_list = array(), $limit = 100, $page = 0, $resources = array()) {
    $params = array(
      'field_list'  => implode(',', $field_list),
      'limit'       => $limit,
      'page'        => $page,
      'query'       => $query,
      'resources'   => implode(',', $resources)
    );

    return $this->get_objects('search', $params);
  }

  /**
   * List genres 
   *
   * @param $field_list array    list of field to result
   * @param $limit      integer  limit result count by given limit
   * @param $offset     integer  offset of results
   *
   * @return array list of games
   */
  public function genres($field_list = array(), $limit = 100, $offset = 0) {
    $params = array(
      'field_list'  => implode(',', $field_list),
      'limit'       => $limit,
      'offset'      => $offset
    );

    return $this->get_objects('genres', $params);
  }

  /**
   * List platforms by filter
   *
   * @param $field_list array    list of field to result
   * @param $limit      integer  limit result count by given limit
   * @param $offset     integer  offset of results
   * @param $filter     array    filter by given values - no "," accepted
   * @param $sort       array    list of keys to sort, format key => asc/desc,
   *
   * @return array list of games
   */
  public function platforms($field_list = array(), $limit = 100, $offset = 0, $filter = array(), $sort = array()) {
    $params = array(
      'field_list'  => implode(',', $field_list),
      'limit'       => $limit,
      'offset'      => $offset,
      'sort'        => $this->format_filter($sort),
      'filter'      => $this->format_filter($filter)
    );

    return $this->get_objects('platforms', $params);
  }

  /**
   * Return parsed result of api response
   *
   * @param $data string result of API
   *
   * @return mixed parsed version of input string(object)
   */
  private function parse_result($data) {
    try {
      if ($this->resp_type == 'json') {
        $result = @json_decode($data);
      } else {
        $result = @simplexml_load_string($data);
      }
    } catch (Exception $e) {
      throw new GiantBombException('Parse error occoured', null, $e);
    }

    if (empty($result)) {
      throw new GiantBombException('Unknown error occured');
    } else if (!empty($result->error) && strtoupper($result->error) != 'OK') {
      throw new GiantBombException('Following error encountered: ' . $result->error);
    }

    return $result;
  }

  /**
   * Creates a signature for the given request
   *
   * @param string $url     string name of url suffix
   * @param array  $params  array  get parameters to send to API
   *
   * @return string
   */
  private function createSignature($url, $params) {
    $url = $url . '?' . http_build_query($params);

    return 'giantbomb-'.substr(sha1($url), 0, 7);
  }
}
