<?php 
namespace ShopGrowth\Store\TidyApis;
class SendGridApi {
    public static function init() {
        add_action( 'rest_api_init', [ __CLASS__, 'create_send_grid_rest_routes' ] );
    }

    public static function create_send_grid_rest_routes(){
         // sendGrid api
         register_rest_route( 'sendgrid/v1', '/settings', [
            'methods' => 'GET',
            'callback' => [ __CLASS__, 'get_sendgrid_settings' ],
            'permission_callback' => [ __CLASS__, 'get_sendgrid_settings_permission' ]
        ] );

        register_rest_route( 'sendgrid/v1', '/settings', array(
            'methods' => 'POST',
            'callback' => [__CLASS__,'save_sendgrid_email'],
            'permission_callback' => [ __CLASS__, 'save_sendgrid_settings_permission' ]
        ));

        register_rest_route( 'sendgrid/v1', '/send', array(
            'methods' => 'POST',
            'callback' => [__CLASS__,'send_email_to_sendgrid'],
            'permission_callback' => [ __CLASS__, 'save_sendgrid_settings_permission' ]
        ));
    }

     // sendgrid function
     public static function get_sendgrid_settings() {
        $sendgrid_api = get_option( 'sendgrid_api' );
        $from_name    = get_option( 'sendgrid_from_name' );
        $from_email   = get_option( 'sendgrid_from_email' );
        $response = [
            'sendgrid_api'        => $sendgrid_api,
            'sendgrid_from_name'  => $from_name,
            'sendgrid_from_email' => $from_email
        ];

        return rest_ensure_response( $response );
    }

    public static function get_sendgrid_settings_permission() {
        return true;
    }
    
    public static function save_sendgrid_email($req){
        $sendgrid_api   = sanitize_text_field( $req['sendgrid_api'] );
        $from_name      = sanitize_text_field( $req['sendgrid_from_name'] ) ;
        $from_email     = sanitize_text_field( $req['sendgrid_from_email'] );
        update_option( 'sendgrid_api', $sendgrid_api );
        update_option( 'sendgrid_from_name', $from_name );
        update_option( 'sendgrid_from_email', $from_email );
        
        return rest_ensure_response( 'success' );
    }

    public static function send_email_to_sendgrid($request){
        $to = $request->get_param('to');
        $message = $request->get_param('message');

        // Send the request to SendGrid API
        $response = self::send_email_using_sendgrid($to, $message);

        return rest_ensure_response($response, 200);

    }
    public static function send_email_using_sendgrid($to, $message) {
        $sendgrid_api = get_option( 'sendgrid_api' );
        $from_name    = get_option( 'sendgrid_from_name' );
        $from_email   = get_option( 'sendgrid_from_email' );
        
        $api_key = $sendgrid_api;
        $url = 'https://api.sendgrid.com/v3/mail/send';
    
        $body = json_encode(array(
            'personalizations' => array(array(
                'to' => array(array('email' => $to)),
                'subject' => $from_name,
            )),
            'from' => array('email' => $from_email), // Replace with your email
            'content' => array(array('type' => 'text/plain', 'value' => $message)),
        ));
    
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_key,
            ),
            'body' => $body,
            'method' => 'POST',
        );
    
        $response = wp_remote_post($url, $args);
    
        global $wpdb;
        //$table_name = $wpdb->prefix . 'tidy_email_logs';
        $table_name = 'SERVMASK_PREFIXe_tidy_email_logs';
        if (is_wp_error($response)) {
            $data = array(
                'to_email'   => $to,
                'subject'    => $from_name,
                'message'    => $message,
                'from_email' => $from_email,
                'status'     => 'failed',
            );
            $insert_result = $wpdb->insert($table_name, $data);
            
            if ($insert_result === false) {
                $error_message = $wpdb->last_error;
                error_log('Error inserting email log: ' . $error_message);
                return false;
            }
            return array('success' => false, 'error' => $response->get_error_message()); 
        } else {
            $data = array(
                'to_email'   => $to,
                'subject'    => $from_name,
                'message'    => $message,
                'from_email' => $from_email,
                'status'     => 'success',
            );
            
            $insert_result = $wpdb->insert($table_name, $data);
            
            if ($insert_result === false) {
                $error_message = $wpdb->last_error;
                error_log('Error inserting email log: ' . $error_message);
                return false;
            }
             return array('success' => true);
            
        }
    }

    public static function save_sendgrid_settings_permission() {
        return current_user_can( 'publish_posts' );
    }

}