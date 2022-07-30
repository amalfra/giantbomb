<?php

namespace Amalfra\GiantBomb\Tests\API;

use Amalfra\GiantBomb\Client;
use \PHPUnit\Framework\TestCase;

class PlatformsTest extends TestCase {
  // platforms() tests start

  /** @test */
  public function platformsWithInvalidToken() {
    $config = array(
      'token' => 'invalid',
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platforms();

    $this->assertEquals('Invalid API Key', $resp['error']);
  }

  /** @test */
  public function platformsWithoutOptions() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platforms();

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function platformsWithfield_listOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platforms(
      field_list: 'name',
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(['name'], array_unique(array_keys($resp['results'][0])));
  }

  /** @test */
  public function platformsWithlimitOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platforms(
      limit: 10,
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(10, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(10, count($resp['results']));
  }

  /** @test */
  public function platformsWithoffsetOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platforms(
      offset: 20,
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(20, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function platformsWithfilterOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platforms(
      filter: 'id:3',
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(1, count($resp['results']));
    $this->assertEquals(3, $resp['results'][0]['id']);
  }

  /** @test */
  public function platformsWithsortOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platforms(
      sort: 'id:desc',
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  // platforms() tests end

  // platform() tests start

  /** @test */
  public function platformWithInvalidToken() {
    $config = array(
      'token' => 'invalid',
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platform();

    $this->assertEquals('Invalid API Key', $resp['error']);
  }

  /** @test */
  public function platformWithoutId() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platform();

    $this->assertEquals('Object Not Found', $resp['error']);
  }

  /** @test */
  public function platformWithfield_list() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platform(1, 'id,name');

    $this->assertEquals('0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(1, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(['id', 'name'], array_unique(array_keys($resp['results'])));
  }

  /** @test */
  public function platform() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->platform(3);

    $this->assertEquals('0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(1, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(3, $resp['results']['id']);
  }

  // platform() tests end
}
