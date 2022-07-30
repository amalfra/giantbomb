<?php

namespace Amalfra\GiantBomb\Tests\API;

use Amalfra\GiantBomb\Client;
use \PHPUnit\Framework\TestCase;

class GamesTest extends TestCase {
  // games() tests start

  /** @test */
  public function gamesWithInvalidToken() {
    $config = array(
      'token' => 'invalid',
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->games();

    $this->assertEquals('Invalid API Key', $resp['error']);
  }

  /** @test */
  public function gamesWithoutOptions() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->games();

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function gamesWithfield_listOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->games(array(
      'field_list' => 'name',
    ));

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(['name'], array_unique(array_keys($resp['results'][0])));
  }

  /** @test */
  public function gamesWithlimitOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->games(array(
      'limit' => 10,
    ));

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(10, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(10, count($resp['results']));
  }

  /** @test */
  public function gamesWithoffsetOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->games(array(
      'offset' => 20,
    ));

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(20, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function gamesWithplatformsOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->games(array(
      'platforms' => 90,
    ));

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(13, count($resp['results']));
  }

  /** @test */
  public function gamesWithsortOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->games(array(
      'sort' => 'id:desc',
    ));

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function gamesWithfilterOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->games(array(
      'filter' => 'aliases:Desert Strike',
    ));

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(1, count($resp['results']));
    $this->assertEquals('Desert Strike: Return to the Gulf', $resp['results'][0]['name']);
  }

  // games() tests end

  // game() tests start

  /** @test */
  public function gameWithInvalidToken() {
    $config = array(
      'token' => 'invalid',
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->game();

    $this->assertEquals('Invalid API Key', $resp['error']);
  }

  /** @test */
  public function gameWithoutId() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->game();

    $this->assertEquals('Object Not Found', $resp['error']);
  }

  /** @test */
  public function game() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->game(121);

    $this->assertEquals('0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(1, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(121, $resp['results']['id']);
  }

  // game() tests end
}
