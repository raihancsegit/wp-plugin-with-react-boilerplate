<?php 
namespace ShopGrowth\Store;
class Settings {
    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'create_admin_menu' ] );
    }

    public static function create_admin_menu(){
        $capability = 'manage_options';
        $slug = 'shopgrowth-settings';

        add_menu_page(
            __( 'ShopGrowth Store', 'shopgrowth-store' ),
            __( 'ShopGrowth Store', 'shopgrowth-store' ),
            $capability,
            $slug,
            [ __CLASS__, 'menu_page_template' ],
            'dashicons-buddicons-replies'
        );
    }
    public static function menu_page_template(){
        echo '<div class="wrap"><div id="shopgrowth-admin-app"></div></div>';
    }
}