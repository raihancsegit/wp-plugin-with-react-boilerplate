<?php
/**
 * Plugin Name: ShopGrowth Store
 * Author: raihan
 * Author URI: https://raihan.com
 * Version: 1.0.0
 * Description: ShopGrowth
 * Text-Domain: shopgrowth-store
 */

if( ! defined( 'ABSPATH' ) ) : exit(); endif; // No direct access allowed.

require_once __DIR__ . '/vendor/autoload.php';

/**
* Define Plugins Contants
*/
define ( 'SHOPGROWTH_STORE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define ( 'SHOPGROWTH_STORE_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
if ( ! defined( 'SHOPGROWTH_STORE_VERSION' ) ) define( 'SHOPGROWTH_STORE_VERSION', '1.0.0' );
if ( ! defined( 'SHOPGROWTH_STORE_FILE' ) ) define( 'SHOPGROWTH_STORE_FILE', __FILE__ );
if ( ! define( 'SHOPGROWTH_STORE_DIR_URL', plugin_dir_url( __FILE__ )) ) define( 'SHOPGROWTH_STORE_DIR_URL', plugin_dir_url( __FILE__ ));

use ShopGrowth\Store\Plugin;
Plugin::init();