<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// add sub-level administrative menu
function pod_sso_add_menu() {

	/*add_submenu_page(
		string   $parent_slug,
		string   $page_title,
		string   $menu_title,
		string   $capability,
		string   $menu_slug,
		callable $function = ''
	);*/
	
	add_submenu_page(
		'options-general.php',
		'Pod SSO Setting',
		'Pod SSO',
		'manage_options',
		'podsso',
		'pod_sso_display_settings_page'
	);
	
}
add_action( 'admin_menu', 'pod_sso_add_menu' );



