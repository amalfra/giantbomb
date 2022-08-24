<?php

namespace Amalfra\GiantBomb\Tests;

use \PHPUnit\Framework\TestCase;
use \ReflectionMethod;
use \InvalidArgumentException;
use Amalfra\GiantBomb\HTTP;
use Amalfra\GiantBomb\Exceptions\HTTPException;

class HTTPTest extends TestCase {
  private function getProtectedProperty($object, $property, $args = []) {
    $r = new ReflectionMethod(get_class($object), $property);
    $r->setAccessible(true);
    return $r->invokeArgs($object, $args);
  }

  // validate() tests start

  /** @test */
  public function validateThrowExecptionNotArrayParamPassed() {
    $api = new HTTP();

    try {		
      $this->getProtectedProperty($api, 'validate', ['notArr']);
      $this->fail();
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    }
  }

  /** @test */
  public function validateThrowExecptionNoValidParam() {
    $api = new HTTP();

    try {		
      $this->getProtectedProperty($api, 'validate', [['p1' => '1', 'p2' => '2'], ['p1']]);
      $this->fail();
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    }
  }

  /** @test */
  public function validateThrowExecptionNoRequiredParam() {
    $api = new HTTP();

    try {		
      $this->getProtectedProperty($api, 'validate', [['p1' => '1'], ['p1', 'p2'], ['p2']]);
      $this->fail();
    } catch (InvalidArgumentException $e) {
      $this->assertTrue(true);
    }
  }

  // validate() tests end

  // tf_to_string() tests start

  /** @test */
  public function validatetf_to_string() {
    $api = new HTTP();
    $var = true;

    $this->getProtectedProperty($api, 'tf_to_string', [&$var, &$var]);
    
    if (gettype($var) === 'string') {
      $this->assertTrue(true);
    } else {
      $this->fail();
    }
  }

  /** @test */
  public function validatetf_to_stringValueFalse() {
    $api = new HTTP();
    $var = false;

    $this->getProtectedProperty($api, 'tf_to_string', [&$var, &$var]);
    
    if (gettype($var) === 'string') {
      $this->assertTrue(true);
    } else {
      $this->fail();
    }
  }

  // tf_to_string() tests end

  // inject() tests start

  /** @test */
  public function validateInject() {
    $api = new HTTP();
    $var = array('val1' => 'key1');

    $this->getProtectedProperty($api, 'inject', [&$var]);

    if (array_key_exists('api_key', $var) &&
      array_key_exists('format', $var)) {
      $this->assertTrue(true);
    } else {
      $this->fail();
    }
  }

  // inject() tests end

  // handle_response() tests start

  /** @test */
  public function validateHandle_response() {
    $api = new HTTP();
    $mock = $this->getMockBuilder('Response')
              ->disableOriginalConstructor()
              ->getMock();
    $mock->status_code = 204;

    $this->assertTrue($this->getProtectedProperty($api, 'handle_response', [&$mock]));
  }

  /** @test */
  public function validateHandle_responseInvalid() {
    $api = new HTTP();
    $mock = $this->getMockBuilder('Response')
              ->disableOriginalConstructor()
              ->getMock();
    $mock->status_code = 400;
    $mock->body = 'abcd';

    try {
      $this->assertTrue($this->getProtectedProperty($api, 'handle_response', [&$mock]));
    } catch (HTTPException $e) {
      $this->assertStringContainsStringIgnoringCase('An HTTP error with status code', $e->getMessage());
    }
  }

  // handle_response() tests end
}
