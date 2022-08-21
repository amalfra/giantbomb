<?php

namespace Amalfra\GiantBomb\Tests;

use \PHPUnit\Framework\TestCase;
use Amalfra\GiantBomb\Exceptions\UnsupportedException;

class UnsupportedExceptionTest extends TestCase {
  // __construct() tests start

  /** @test */
  public function validate() {
    try {		
      $i = new UnsupportedException();
      throw $i;
    } catch (UnsupportedException $e) {
      $this->assertInstanceOf(UnsupportedException::class, $e);
    }
  }

  // __construct() tests end
}
