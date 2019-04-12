<?php
/**
 * File callback.php
 *
 * @author
 * @package WP Single Sign On Client
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Redirect the user back to the home page if logged in.
if ( is_user_logged_in() ) {
	wp_redirect( home_url() );
	exit;
}

function dd($txt,$die = true){
    echo "<pre>";
    print_r($txt);
    echo "</pre>";
    if($die){
        die();
    }

}

if( !session_id() )
    session_start();

add_action('init','register_session');

// Grab a copy of the options and set the redirect location.
$options       = get_option( 'podsso_options' );
$user_redirect = podsso_get_user_redirect_url();

// Authenticate Check and Redirect
if ( ! isset( $_GET['code'] ) ) {
	$params = array(
		'oauth'         => 'authorize',
		'response_type' => 'code',
		'client_id'     => $options['client_id'],
		'redirect_uri'  => site_url( '?podSso' ),
        'scope'         => 'profile email'
	);
	$params = http_build_query( $params );
	wp_redirect( $options['server_url'] . "/authorize/" . '?' . $params );
	exit;
}

// Handle the callback from the server is there is one.
if ( isset( $_GET['code'] ) && ! empty( $_GET['code'] ) ) {

	$code       = sanitize_text_field( $_GET['code'] );
	$server_url = $options['server_url'] . '/token/';
	$requestArray = array(
        'method'      => 'POST',
        'timeout'     => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking'    => true,
        'headers'     => array(),
        'body'        => array(
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'client_id'     => $options['client_id'],
            'client_secret' => $options['client_secret'],
            'redirect_uri'  => site_url( '?podSso' )
        ),
        'cookies'     => array(),
        'sslverify'   => false
    );

	$response   = wp_remote_post( $server_url, $requestArray );
    $tokens = json_decode( $response['body'] );
    if ( isset( $tokens->error ) ) {
		wp_die( $tokens->error_description );
	}

    update_user_meta(get_current_user_id(), 'pod_user_access_token', $tokens->access_token);

    $user_info_url = $options['api_url'] . '/nzh/getUserProfile/';
	$response   = wp_remote_post( $user_info_url, array(
        'method'      => 'POST',
		'timeout'     => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(
            '_token_' => $tokens->access_token,
            '_token_issuer_' => '1'
        ),
		'sslverify'   => false
	) );
    $res_info = json_decode( $response['body'] );

    if ( isset( $res_info->error ) ) {
        wp_die( $res_info->error_description );
    }

    $user_info = $res_info->result;

    if ( email_exists( $user_info->email ) == false ) {

		// Does not have an account... Register and then log the user in
		$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
		$user_id         = wp_create_user( $user_info->email, $random_password, $user_info->email );

		$firstName = "";
        if(isset($user_info->firstName))
            $firstName = $user_info->firstName;

        $lastName = "";
        if(isset($user_info->lastName))
            $lastName = $user_info->lastName;

        $nickName = "";
        if(isset($user_info->nickName))
            $nickName = $user_info->nickName;


        $updateUserArray = array(
            'ID'            => $user_id,
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'nickname'      => $nickName,
            'display_name'  => $firstName
        );
        wp_update_user( $updateUserArray );

        wp_clear_auth_cookie();
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );

        update_user_meta($user_id, 'pod_user_id', $user_info->userId);

        if ( is_user_logged_in() ) {
			wp_redirect( $user_redirect );
			exit;
		}

	} else {

		// Already Registered... Log the User In
		$random_password = __( 'User already exists.  Password inherited.' );
		$user            = get_user_by( 'email', $user_info->email );

        update_user_meta($user->ID, 'pod_user_id', $user_info->userId);

		// User ID 1 is not allowed
		if ( $user->ID == '1' ) {
			wp_die( 'For security reasons, this user can not use Single Sign On' );
		}

		wp_clear_auth_cookie();
		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID );

		if ( is_user_logged_in() ) {
			wp_redirect( $user_redirect );
			exit;
		}

	}

	exit( 'Single Sign On Failed.' );
}