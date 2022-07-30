<?php

namespace Amalfra\GiantBomb\Tests;

use Amalfra\GiantBomb\Client;
use Amalfra\GiantBomb\Exceptions\ConfigException;
use \PHPUnit\Framework\TestCase;

class ClientTest extends TestCase {
  // __construct() tests start

  /** @test */
  public function validateObjectCreationWithoutToken() {
    try {		
      $giantbomb = new Client();
    } catch (ConfigException $e) {
      $this->assertTrue(true);
    }
  }

  /** @test */
  public function validateObjectCreationWithValidToken() {	
    $config = array(
      'token' => 'abcd',
    );
    $giantbomb = new Client($config);
    $this->assertEquals('https://www.giantbomb.com/api/', Client::$base_url);
    $this->assertEquals(array(
      'Content-Type' => 'application/json',
      'Accept'       => 'application/json'
    ), Client::$headers);
  }

  // __construct() tests end
}
