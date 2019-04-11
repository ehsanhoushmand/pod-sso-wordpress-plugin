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



// if admin area
if ( is_admin() ) {

	// include plugin dependencies
	require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';
	require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
	require_once plugin_dir_path( __FILE__ ) . 'admin/settings-register.php';
	require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callbacks.php';
	require_once plugin_dir_path( __FILE__ ) . 'admin/settings-validate.php';

}


// default plugin options
function podsso_options_default() {

	return array(
		'server_url' => 'https://accounts.pod.land/oauth2',
		'api_url' => 'https://api.pod.land/srv/core',
		'pay_invoice_url' => 'https://gw.pod.land/v1/pbc/payinvoice',
	);

}