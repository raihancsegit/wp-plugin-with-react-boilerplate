<?php

namespace ShopGrowth\Store;
use ShopGrowth\Store\Helpers\Singleton;
use ShopGrowth\Store\Helpers\Traits;

final class Plugin extends Singleton {
	use Traits;

	public static function init() {
		// on plugin activation and deactivation
		register_activation_hook( SHOPGROWTH_STORE_FILE, [ __CLASS__, 'on_activation' ] );
		register_deactivation_hook( SHOPGROWTH_STORE_FILE, [ __CLASS__, 'on_deactivation' ] );
		
		add_action( 'plugins_loaded', [ __CLASS__, 'check_update_database' ]);
		
		// enqueue scripts, styles and localize
		$enqueue = Enqueue::get_instance();
		add_action( 'wp_enqueue_scripts', [ $enqueue, 'init' ] );
		add_action( 'admin_enqueue_scripts', [ $enqueue, 'init' ] );

		Settings::init();
		
	}

	/**
	 * Stuffs to do on plugin activation
	 *
	 * @return void
	 */
	public static function on_activation() {
		flush_rewrite_rules( true );
	}

	/**
	 * Stuffs to do on plugin deactivation
	 *
	 * @return void
	 */
	public static function on_deactivation() {
		flush_rewrite_rules( true );
		
	}

	// Function to check and update the database schema
	public static function check_update_database() {
		$installed_version = get_option('tidy_smtp_version');

		if ($installed_version !== SHOPGROWTH_STORE_VERSION) {
			// Update the database version
			update_option('tidy_smtp_version', SHOPGROWTH_STORE_VERSION);
		}
	}

	
}