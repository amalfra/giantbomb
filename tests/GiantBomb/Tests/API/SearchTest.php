<?php

namespace Amalfra\GiantBomb\Tests\API;

use Exception;
use \InvalidArgumentException;
use \PHPUnit\Framework\TestCase;
use Amalfra\GiantBomb\Client;

class SearchTest extends TestCase {
  // search() tests start

  /** @test */
  public function searchWithoutresourcesOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);

    try {
      $resp = $giantbomb->search();
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    }
  }

  /** @test */
  public function searchWithInvalidToken() {
    $config = array(
      'token' => 'invalid',
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->search(
      query: 'halo',
    );

    $this->assertEquals('Invalid API Key', $resp['error']);
  }

  /** @test */
  public function searchWithoutOptionalOptions() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->search(
      query: 'halo',
    );

    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(10, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(9, count($resp['results']));
  }

  /** @test */
  public function searchWithfield_listOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->search(
      field_list: 'name',
      query: 'halo',
    );

    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(10, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(['name', 'resource_type'], array_unique(array_keys($resp['results'][0])));
  }

  /** @test */
  public function searchWithlimitOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->search(
      limit: 20,
      query: 'halo',
    );

    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(20, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(19, count($resp['results']));
  }

  /** @test */
  public function searchWithresourcesOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->search(
      resources: 'game',
      query: 'halo',
    );

    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(10, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(10, count($resp['results']));
    array_map(function($val) {
      if ($val['resource_type'] != 'game') {
        throw new Exception('Got non game result');
      }
      return $val;
    }, $resp['results']);
  }

  // search() tests end
}
