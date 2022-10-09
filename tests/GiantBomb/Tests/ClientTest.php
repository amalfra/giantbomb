<?php

namespace Amalfra\GiantBomb\Tests;

use \ReflectionClass;
use \PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Amalfra\GiantBomb\Client;
use Amalfra\GiantBomb\Exceptions\ConfigException;
use Amalfra\GiantBomb\Exceptions\UnsupportedException;

class ClientTest extends TestCase {
  private static function getProperty($object, $property) {
    $reflectedClass = new ReflectionClass($object);
    $reflection = $reflectedClass->getProperty($property);
    $reflection->setAccessible(true);
    return $reflection->getValue($object);
  }

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

  /** @test */
  public function validateObjectCreationWithDebug() {
    $config = array(
      'token' => 'abcd',
      'debug' => true,
    );
    $giantbomb = new Client($config);
    $this->assertEquals(true, Client::$debug);
  }

  // __construct() tests end

  // create_signature() tests start

  public function validateSignatureReturned() {
    $config = array(
      'token' => 'abcd',
    );
    $giantbomb = new Client($config);
    $this->assertEquals('giantbomb-9c894e8', $giantbomb->create_signature('/api/games', array(
      'page' => 10,
    )));
  }

  // create_signature() tests end

  // set_cache_provider() tests start

  /** @test */
  public function validateSettingCacheProviderWithInvalidProvider() {
    try {
      $config = array(
        'token' => 'abcd',
      );
      $giantbomb = new Client($config );
      $giantbomb->set_cache_provider('blah');
    } catch (ConfigException $e) {
      $this->assertEquals('Unsupported cache type: blah', $e->getMessage());
    }
  }

  /** @test */
  public function validateSettingCacheProviderWithInmemoryProvider() {
    $config = array(
      'token' => 'abcd',
    );
    $giantbomb = new Client($config );
    $giantbomb->set_cache_provider('inmemory');
    $value = self::getProperty($giantbomb, 'cache');
    $this->assertInstanceOf(ArrayAdapter::class, $value);
  }

  /** @test */
  public function validateSettingCacheProviderWithRedisProviderNoConfig() {
    $config = array(
      'token' => 'abcd',
    );
    $giantbomb = new Client($config );
    try {
      $giantbomb->set_cache_provider('redis', array('host' => 'localhost'));
      $value = self::getProperty($giantbomb, 'cache');
      $this->assertInstanceOf(RedisAdapter::class, $value);
    } catch (ConfigException $e) {
      $this->assertEquals('port config not specified', $e->getMessage());
    }

    try {
      $giantbomb->set_cache_provider('redis');
      $value = self::getProperty($giantbomb, 'cache');
      $this->assertInstanceOf(RedisAdapter::class, $value);
    } catch (ConfigException $e) {
      $this->assertEquals('host config not specified', $e->getMessage());
    }
  }

  /** @test */
  public function validateSettingCacheProviderWithRedisProviderConfig() {
    $config = array(
      'token' => 'abcd',
    );
    $giantbomb = new Client($config );
    $giantbomb->set_cache_provider('redis', array('host' => 'localhost', 'port' => 6379));
    $value = self::getProperty($giantbomb, 'cache');
    $this->assertInstanceOf(RedisAdapter::class, $value);
  }

   /** @test */
   public function validateSettingCacheProviderWithRedisProviderCachehit() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $giantbomb->set_cache_provider('redis', array('host' => 'localhost', 'port' => 6379));
    $giantbomb->companies();

    // try 1
    $start_time =  microtime(true);
    $giantbomb->companies();
    $time_consumed = (round(microtime(true) - $start_time, 3) * 1000) * 0.001;
    $this->assertLessThanOrEqual(1, $time_consumed);
    $this->assertGreaterThanOrEqual(0, $time_consumed);
    // try 2
    $start_time =  microtime(true);
    $giantbomb->companies();
    $time_consumed = (round(microtime(true) - $start_time, 3) * 1000) * 0.001;
    $this->assertLessThanOrEqual(1, $time_consumed);
    $this->assertGreaterThanOrEqual(0, $time_consumed);
  }

  // set_cache_provider() tests end

  // __call() tests start

  /** @test */
  public function validateCallNotexistingMethod() {
    try {
      $config = array(
        'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
      );
      $giantbomb = new Client($config);
      $giantbomb->abcd();
    } catch (UnsupportedException $e) {
      $this->assertTrue(true);
    }
  }

  /** @test */
  public function validateCallExistingMethod() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $result = $giantbomb->__call('set_cache_provider', ['inmemory']);
    $this->assertNotTrue($result);
    $this->assertNull(error_get_last());
  }

  // __call() tests end
}
