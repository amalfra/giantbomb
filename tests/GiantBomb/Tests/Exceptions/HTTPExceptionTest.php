<?php

namespace Amalfra\GiantBomb\Tests;

use \PHPUnit\Framework\TestCase;
use Amalfra\GiantBomb\Exceptions\HTTPException;

class HTTPExceptionTest extends TestCase {
  // __construct() tests start

  /** @test */
  public function validate() {
    try {		
      $i = new HTTPException();
      throw $i;
    } catch (HTTPException $e) {
      $this->assertInstanceOf(HTTPException::class, $e);
    }
  }

  // __construct() tests end

  // __toString() tests start

  /** @test */
  public function validate__toString() {
    try {		
      $i = new HTTPException('Exception message', 123);
      throw $i;
    } catch (HTTPException $e) {
      $this->assertEquals('Amalfra\GiantBomb\Exceptions\HTTPException' . ': [123]: Exception message',
      trim($e->__toString()));
    }
  }

  // __toString() tests end
}
