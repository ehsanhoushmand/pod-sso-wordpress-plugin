<?php

// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// register plugin settings
function pod_sso_register_settings() {

	/*

	register_setting(
		string   $option_group,
		string   $option_name,
		callable $sanitize_callback = ''
	);

	*/

	register_setting(
		'podsso_options',
		'podsso_options',
		'pod_sso_callback_validate_options'
	);

	/*

	add_settings_section(
		string   $id,
		string   $title,
		callable $callback,
		string   $page
	);

	*/

	add_settings_section(
		'podsso_section_login',
		esc_html__('Login Setting', 'pod-sso-plugin'),
		'podsso_callback_section_login',
		'podsso'
	);

	add_settings_section(
		'podsso_section_api',
		esc_html__('Api Setting', 'pod-sso-plugin'),
		'podsso_callback_section_api',
		'podsso'
	);

	/*

	add_settings_field(
    string   $id,
		string   $title,
		callable $callback,
		string   $page,
		string   $section = 'default',
		array    $args = []
	);

	*/

	//login section
	add_settings_field(
		'client_id',
		'Client id',
		'podsso_callback_field_text',
		'podsso',
		'podsso_section_login',
		[ 'id' => 'client_id', 'label' => 'Pod Client Id' ]
	);

	add_settings_field(
		'client_secret',
		'Client Secret',
		'podsso_callback_field_text',
		'podsso',
		'podsso_section_login',
		[ 'id' => 'client_secret', 'label' => 'Pod Client Secret' ]
	);

	add_settings_field(
		'server_url',
		'OAuth Server URL',
		'podsso_callback_field_text',
		'podsso',
		'podsso_section_login',
		[ 'id' => 'server_url', 'label' => 'https://accounts.pod.land/oauth2' ]
	);

	// api section
	add_settings_field(
		'api_url',
		'API URL',
		'podsso_callback_field_text',
		'podsso',
		'podsso_section_api',
		[ 'id' => 'api_url', 'label' => 'https://api.pod.land/srv/core' ]
	);

	add_settings_field(
		'api_token',
		'API Token',
		'podsso_callback_field_text',
		'podsso',
		'podsso_section_api',
		[ 'id' => 'api_token', 'label' => 'Pod API Token' ]
	);

	add_settings_field(
		'pay_invoice_url',
		'Pay Invoice URL',
		'podsso_callback_field_text',
		'podsso',
		'podsso_section_api',
		[ 'id' => 'pay_invoice_url', 'label' => 'https://pay.pod.land/v1/pbc/payinvoice' ]
	);

	add_settings_field(
		'guild_code',
		'Guild Code',
		'podsso_callback_field_text',
		'podsso',
		'podsso_section_api',
		[ 'id' => 'guild_code', 'label' => 'Pod Guild Code' ]
	);

}
add_action( 'admin_init', 'pod_sso_register_settings' );


