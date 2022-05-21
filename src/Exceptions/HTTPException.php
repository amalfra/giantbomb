<?php

namespace Amalfra\GiantBomb\Exceptions;

use \Exception;

/**
 * Class HTTPException
 *
 * @package Amalfra\GiantBomb\Exceptions
 */
class HTTPException extends Exception {
  /**
   * convert exception to string
   *
   * @return string
   */
  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
