<?php 
namespace ShopGrowth\Store\TidyApis;
class PostMarkApi {
    public static function init() 
    {
        add_action( 'rest_api_init', [ __CLASS__, 'create_post_mark_rest_routes' ] );
    }

    public static function create_post_mark_rest_routes(){
         // postmark setting api 
         register_rest_route( 'postmark/v1', '/settings', [
            'methods' => 'GET',
            'callback' => [ __CLASS__, 'get_postmark_settings' ],
            'permission_callback' => [ __CLASS__, 'get_postmark_settings_permission' ]
        ] );
        
        
        register_rest_route( 'postmark/v1', '/settings', [
            'methods' => 'POST',
            'callback' => [ __CLASS__, 'save_postmark_settings' ],
            'permission_callback' => [ __CLASS__, 'save_postmark_settings_permission' ]
        ] );
    }

    public static function get_postmark_settings() {
        $postmark_api = get_option( 'postmark_api' );
        $from_name    = get_option( 'postmark_from_name' );
        $from_email   = get_option( 'postmark_from_email' );
        $response = [
            'postmark_api'        => $postmark_api,
            'postmark_from_name'  => $from_name,
            'postmark_from_email' => $from_email
        ];

        return rest_ensure_response( $response );
    }

    public static function get_postmark_settings_permission() {
        return true;
    }

    public static function save_postmark_settings( $req ) {
        $postmark_api   = sanitize_text_field( $req['postmark_api'] );
        $from_name      = sanitize_text_field( $req['postmark_from_name'] ) ;
        $from_email     = sanitize_text_field( $req['postmark_from_email'] );
        update_option( 'postmark_api', $postmark_api );
        update_option( 'postmark_from_name', $from_name );
        update_option( 'postmark_from_email', $from_email );
        return rest_ensure_response( 'success' );
    }

    public static function save_postmark_settings_permission() {
        return current_user_can( 'publish_posts' );
    }
}