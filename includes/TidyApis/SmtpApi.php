<?php 
namespace ShopGrowth\Store\TidyApis;

class SmtpApi {
    public static function init() {
        add_action( 'phpmailer_init', [ __CLASS__,'send_smtp_send_email' ]);
        add_action( 'rest_api_init', [ __CLASS__, 'create_smtp_rest_routes' ] );
    }
    
    public static function create_smtp_rest_routes(){
        // smtp rest api
        register_rest_route( 'smtp-api/v1', '/settings', array(
            'methods' => 'POST',
            'callback' => [__CLASS__,'save_smtp_send_email'],
            'permission_callback' => [ __CLASS__, 'save_smtp_settings_permission' ]
        ));

        register_rest_route( 'smtp-api/v1', '/settings', array(
            'methods' => 'GET',
            'callback' => [__CLASS__,'get_smtp_send_email'],
            'permission_callback' => [ __CLASS__, 'get_smtp_settings_permission' ]
        ));

        register_rest_route( 'smtp-api/v1', '/send', array(
            'methods' => 'POST',
            'callback' => [__CLASS__,'send_smtp_send_email'],
            'permission_callback' => [ __CLASS__, 'get_smtp_settings_permission' ]
        ));
    }

    // smtp function
    public static function get_smtp_send_email($req) {
        $smpt_from_name     = get_option( 'smpt_from_name' );
        $smtp_from_email    = get_option( 'smtp_from_email' );
        $smtp_host          = get_option( 'smtp_host' );
        $smtp_port          = get_option( 'smtp_port' );
        $enc_value          = get_option( 'enc_value' );
        $smtp_user          = get_option( 'smtp_user' );
        $smtp_pass          = get_option( 'smtp_pass' );
        $response = [
            'smpt_from_name'        => $smpt_from_name,
            'smtp_from_email'       => $smtp_from_email,
            'smtp_host'             => $smtp_host,
            'smtp_port'             => $smtp_port,
            'enc_value'             => $enc_value,
            'smtp_user'             => $smtp_user,
            'smtp_pass'             => $smtp_pass,
        ];

        return rest_ensure_response( $response );
    }
    public static function send_smtp_send_email($req) {
        $smpt_from_name     = get_option( 'smpt_from_name' );
        $smtp_from_email    = get_option( 'smtp_from_email' );
        $smtp_host          = get_option( 'smtp_host' );
        $smtp_port          = get_option( 'smtp_port' );
        $enc_value          = get_option( 'enc_value' );
        $smtp_user          = get_option( 'smtp_user' );
        $smtp_pass          = get_option( 'smtp_pass' );
        
        global $phpmailer;
        try {
            //Server settings
            $phpmailer->isSMTP();
            $phpmailer->Host       = $smtp_host;  // SMTP server
            $phpmailer->SMTPAuth   = true;
            $phpmailer->Username   =  $smtp_user;     // SMTP username
            $phpmailer->Password   = $smtp_pass;     // SMTP password
            $phpmailer->SMTPSecure = $enc_value;         // Enable TLS/SSL encryption
            $phpmailer->Port       =  $smtp_port;    // TCP port to connect to
    
            // Recipients
            $phpmailer->setFrom($smtp_from_email, $smpt_from_name);
            $phpmailer->addAddress($req['to']);  // Add a recipient
    
            // Content
            $phpmailer->isHTML(true);
            $phpmailer->Subject = $req['subject'];
            $phpmailer->Body    = $req['message'];
    
            $phpmailer->send();
            
            return array('message' => 'Email sent successfully.');
        } catch (Exception $e) {
            return array('message' => 'Error sending email.'. $phpmailer->ErrorInfo, array('status' => 500));
        }
    }

    public static function get_smtp_settings_permission() {
        return true;
    }

    public static function save_smtp_send_email( $req ) {
        $smpt_from_name      = sanitize_text_field( $req['smpt_from_name'] );
        $smtp_from_email     = sanitize_text_field( $req['smtp_from_email'] );
        $smtp_host           = sanitize_text_field( $req['smtp_host'] );
        $smtp_port           = sanitize_text_field( $req['smtp_port'] );
        $enc_value           = sanitize_text_field( $req['enc_value'] );
        $smtp_user           = sanitize_text_field( $req['smtp_user'] );
        $smtp_pass           = sanitize_text_field( $req['smtp_pass'] );
       
        update_option( 'smpt_from_name', $smpt_from_name );
        update_option( 'smtp_from_email', $smtp_from_email );
        update_option( 'smtp_host', $smtp_host );
        update_option( 'smtp_port', $smtp_port );
        update_option( 'enc_value', $enc_value );
        update_option( 'smtp_user', $smtp_user );
        update_option( 'smtp_pass', $smtp_pass );

        return rest_ensure_response( 'success' );
    }

    public static function save_smtp_settings_permission() {
        return current_user_can( 'publish_posts' );
    }
}