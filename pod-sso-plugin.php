<?php

/**
 * Plugin Name: Pod SSO Plugin
 * Plugin URI:  http://docs.pod.land
 * Description: Login with pod account
 * Version:     1.0.0
 * Author:      Ehsan Houshmand
 * Author URI:  http://ehsanhoushmand.ir
 * Text Domain: pod-sso-plugin
 * Domain Path: /languages
 * License:     GPL2
 */

// If this file is called directly, abort.
if( ! defined('WPINC')) {
	die;
}

if ( ! defined( 'PODSSO_FILE' ) ) {
	define( 'PODSSO_FILE', plugin_dir_path( __FILE__ ) );
}

// load text domain
function podsso_load_textdomain() {

	load_plugin_textdomain( 'pod-sso-plugin', false, PODSSO_FILE . 'languages/' );

}
add_action( 'plugins_loaded', 'podsso_load_textdomain' );


// if admin area
if ( is_admin() ) {

	// include plugin dependencies
	require_once PODSSO_FILE . 'admin/admin-menu.php';
	require_once PODSSO_FILE . 'admin/settings-page.php';
	require_once PODSSO_FILE . 'admin/settings-register.php';
	require_once PODSSO_FILE . 'admin/settings-callbacks.php';
	require_once PODSSO_FILE . 'admin/settings-validate.php';

}

require_once( PODSSO_FILE . 'includes/class-podsso-client.php');


// default plugin options
function podsso_options_default() {

	return array(
		'server_url' => 'https://accounts.pod.land/oauth2',
		'api_url' => 'https://api.pod.land/srv/core',
		'pay_invoice_url' => 'https://gw.pod.land/v1/pbc/payinvoice',
	);

}