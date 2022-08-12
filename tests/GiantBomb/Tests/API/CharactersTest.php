<?php

namespace Amalfra\GiantBomb\Tests\API;

use Amalfra\GiantBomb\Client;
use \PHPUnit\Framework\TestCase;

class CharactersTest extends TestCase {
  // characters() tests start

  /** @test */
  public function charactersWithInvalidToken() {
    $config = array(
      'token' => 'invalid',
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->characters();

    $this->assertEquals('Invalid API Key', $resp['error']);
  }

  /** @test */
  public function charactersWithoutOptions() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->characters();

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function charactersWithfield_listOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->characters(
      field_list: 'name',
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(['name'], array_unique(array_keys($resp['results'][0])));
  }

  /** @test */
  public function charactersWithlimitOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->characters(
      limit: 10,
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(10, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(10, count($resp['results']));
  }

  /** @test */
  public function charactersWithoffsetOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->characters(
      offset: 20,
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(20, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function charactersWithsortOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->characters(
      sort: 'id:desc',
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function charactersWithfilterOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->characters(
      filter: 'id:2',
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(1, count($resp['results']));
    $this->assertEquals(2, $resp['results'][0]['id']);
  }

  // characters() tests end

  // character() tests start

  /** @test */
  public function characterWithInvalidToken() {
    $config = array(
      'token' => 'invalid',
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->character();

    $this->assertEquals('Invalid API Key', $resp['error']);
  }

  /** @test */
  public function characterWithoutId() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->character();

    $this->assertEquals('Object Not Found', $resp['error']);
  }

  /** @test */
  public function characterWithfield_list() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->character(2, 'id,name');

    $this->assertEquals('0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(1, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(['id', 'name'], array_unique(array_keys($resp['results'])));
  }

  /** @test */
  public function character() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->character(2);

    $this->assertEquals('0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(1, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(2, $resp['results']['id']);
  }

  // character() tests end
}
