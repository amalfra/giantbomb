<?php

namespace Amalfra\GiantBomb\Tests;

use \PHPUnit\Framework\TestCase;
use Amalfra\GiantBomb\Exceptions\ConfigException;

class ConfigExceptionTest extends TestCase {
  // __construct() tests start

  /** @test */
  public function validate() {
    try {		
      $i = new ConfigException();
      throw $i;
    } catch (ConfigException $e) {
      $this->assertInstanceOf(ConfigException::class, $e);
    }
  }

  // __construct() tests end
}
