<?php

// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// callback: login section
function podsso_callback_section_login() {

	echo '<p>' . esc_html__('These settings enable you to customize the Pod SSO.', 'pod-sso-plugin') . '</p>';

}



// callback: admin section
function podsso_callback_section_api() {

	echo '<p>' . esc_html__('These settings enable you to customize the Pod Api.', 'pod-sso-plugin') . '</p>';

}

// callback: admin section
function podsso_callback_section_business( $args ) {

	echo '<p>' . '</p>';

}


// callback: text field
function podsso_callback_field_text( $args ) {

	$options = get_option( 'podsso_options', podsso_options_default() );

	$id    = $args['id'] ?? '';
	$label = $args['label'] ?? '';

	$value = sanitize_text_field( $options[$id] ?? '');

	echo '<input id="podsso_options_'. $id .'" name="podsso_options['. $id .']" type="text" size="40" value="'. $value .'"><br />';
	echo '<label for="podsso_options_'. $id .'">'. $label .'</label>';

}


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

function podsso_callback_field_select($args)
{
    $options = get_option( 'podsso_options', podsso_options_default() );
    $id    = $args['id'] ?? '';
    $selected_option = sanitize_text_field( $options[$id] ?? '');
    $select_options = podsso_guilds_options();

    echo '<select id="podsso_options_'. $id .'" name="podsso_options['. $id .']">';

    foreach ( $select_options as $option ) {

        $selected = selected( $selected_option === $option['code'], true, false );

        echo '<option value="'. $option['code'] .'"'. $selected .'>'. $option['name'] .'</option>';

    }

    echo '</select>';
}





