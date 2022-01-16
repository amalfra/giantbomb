<?php

namespace Amalfra\GiantBomb;

use \Exception;

/**
 * Define a custom exception class for api wrapper
 */
class GiantBombException extends Exception {
  /**
   * Redefine the exception so message isn't optional
   *
   * @param $message  string    message to set in exception
   * @param $code     integer   error code
   * @param $previous Exception previous Exception to save
   *
   * @return void
   */
  public function __construct($message, $code = 0, Exception $previous = null) {
    // make sure everything is assigned properly
    parent::__construct($message, $code, $previous);
  }

  /**
   * convert exception to string
   *
   * @return string
   */
  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
