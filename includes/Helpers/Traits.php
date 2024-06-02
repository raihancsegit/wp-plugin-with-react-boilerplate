<?php

namespace ShopGrowth\Store\Helpers;

trait Traits {
	/**
	 * Nonce verification
	 *
	 * @param string|int $action Should give context to what is taking place and be the same when nonce was created.
	 * @param string $key nonce key in the request
	 *
	 * @return void
	 */
	public static function check_nonce( $action, $key ) {
		if (
			! isset( $_REQUEST[ $key ] ) ||
			empty( $token = $_REQUEST[ $key ] ) ||
			! wp_verify_nonce( $token, $action )
		) {
			return false;
		}

		return true;
	}

	
	/**
	 * Recursively remove all the empty values from an array
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	public static function recursive_array_filter( $array ) {
		foreach ( $array as &$value ) {
				if ( is_array( $value ) ) $value = self::recursive_array_filter( $value );
		}

		return array_filter( $array, function ( $item ) {
				return $item !== '';
		} );
	}
}