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

	try {
		$options = get_option( 'podsso_options' );
		$server_url = $options['api_url'] . '/nzh/biz/businessDealingList';
		$requestArray = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(
				'_token_' => $options['api_token'],
				'_token_issuer_' => '1'
			),
			'body'        => array(
				'enable' => 'true'
			),
			'cookies'     => array(),
			'sslverify'   => false
		);

		$response   = wp_remote_post( $server_url, $requestArray );
		$res_info = json_decode( $response['body'], true);
		$options['business_count'] = $res_info['count'];
		update_option('podsso_options', $options);
	} catch (Exception $exception) {
		error_log($exception->getMessage());
	}

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

	$select_option = podsso_guilds_options();

	add_settings_field(
		'guild_code',
		'Guild Code',
		'podsso_callback_field_select',
		'podsso',
		'podsso_section_api',
		[ 'id' => 'guild_code', 'label' => 'Pod Guild Code', 'option' => $select_option ]
	);

	if($res_info['count'] > 0)
	{
		$count = -1;
		foreach ($res_info['result'] as $result)
		{
			++$count;
			add_settings_section(
				'podsso_section_business_' . $result['business']['id'],
				esc_html__('اطلاعات شرکت ذینفع: ' . $result['business']['name'], 'pod-sso-plugin'),
				'podsso_callback_section_business',
				'podsso'
			);

			add_settings_field(
				'guild_code_' . $count,
				'Guild Code',
				'podsso_callback_field_select',
				'podsso',
				'podsso_section_business_' . $result['business']['id'],
				[ 'id' => 'guild_code_' . $count, 'label' => 'Pod Guild Code', 'option' => $select_option ]
			);

			add_settings_field(
				'business_share_' . $count,
				'Business share',
				'podsso_callback_field_text',
				'podsso',
				'podsso_section_business_' . $result['business']['id'],
				[ 'id' => 'business_share_' . $count, 'label' => esc_html__('fixed share or percentage share (ex. 1000 or 30%)', 'pod-sso-plugin') ]
			);
			$options['business_id_' . $count] = $result['business']['id'];
		}
		update_option('podsso_options', $options);
	}

}
add_action( 'admin_init', 'pod_sso_register_settings' );

function podsso_guilds_options() {
	$options = get_option( 'podsso_options' );
	$server_url = $options['api_url'] . '/nzh/guildList';
	$requestArray = array(
		'method'      => 'GET',
		'timeout'     => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => array(
			'_token_' => $options['api_token'],
			'_token_issuer_' => '1'
		),
		'cookies'     => array(),
		'sslverify'   => false
	);

	$response   = wp_remote_get( $server_url, $requestArray );
	$res_info = json_decode( $response['body'], true);

	return $res_info['result'];
}


