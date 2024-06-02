<?php

namespace ShopGrowth\Store\Helpers;

/**
 * Original Singleton class
 */
class Singleton {

  /**
   * Any Singleton class.
   *
   * @var Singleton[] $instances
   */
  private static $instances = [];

  /**
   * Constructor
   * Protected to avoid "new"
	 * Only the child class could have constructor of its own
   */
  protected function __construct() {}

  /**
   * Get Instance
   *
   * @return instanceof instance of the called class
   */
  final public static function get_instance() {
    $class = get_called_class();

    if ( ! isset( $instances[ $class ] ) ) {
      self::$instances[ $class ] = new $class();
    }

    return self::$instances[ $class ];
  }

  /**
   * Avoid clone instance
   */
  private function __clone() {}

  /**
   * Avoid serialize instance
   */
  public function __sleep() {}

  /**
   * Avoid unserialize instance
   */
  public function __wakeup() {}
}