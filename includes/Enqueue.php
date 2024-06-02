<?php
namespace ShopGrowth\Store;
use ShopGrowth\Store\Helpers\Singleton;

final class Enqueue extends Singleton {
	public function init() {
		$this->register_scripts();
		$this->register_styles();
		$this->localize();
		$this->load();
	}

	public static function register_scripts(){

		wp_register_script(
			'bootstrap',
			'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js',
			[],
			'5.2.3',
			true
		);
		wp_register_script(
			'shopgrowth-store-bundle',
			SHOPGROWTH_STORE_URL . 'build/index.js',
			[ 'jquery', 'wp-element' ],
			'1.0.0',
			true
		);
	}

	public static function register_styles(){
		wp_register_style(
			'bootstrap',
			'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css',
			[],
			'5.2.3',
			'all'
		);
		wp_register_style(
			'shopgrowth-style',
			SHOPGROWTH_STORE_URL . 'assets/css/style.css',
			[],
			'1.0.0',
			'all'
		);
	}

	public static function localize(){
		$shop_store = [
			'token'                  => wp_create_nonce( 'shopgrowth-nonce' ),
			'nonce'                  => wp_create_nonce( 'wp_rest' ),
			'admin_ajax'             => admin_url( 'admin-ajax.php' ),
			'home_url'               => home_url(),
			'apiUrl' 				 => home_url( '/wp-json' ),
		];

		wp_localize_script( 'shopgrowth-store-bundle', 'appLocalizer', $shop_store );
		
	}

	public function load(){
		$this->enqueue_media( [ 'bootstrap' ] );
		$this->enqueue_media( [ 'shopgrowth-store-bundle' ] );
		$this->enqueue_media( [ 'bootstrap' ], 'style' );
		$this->enqueue_media( [ 'shopgrowth-style' ], 'style' );
	}

	/**
	 * @param array $handles Array of media handles
	 * @param string $types Media types 'script' or 'style'
	 */
	public function enqueue_media( $handles, $type = 'script' ) {

			if ($type === 'script') {
				foreach ($handles as $h) {
					wp_enqueue_script($h);
				}
				return;
			}

			// enqueue styles
			foreach ($handles as $h) {
				wp_enqueue_style($h);
			}
		
	}
}