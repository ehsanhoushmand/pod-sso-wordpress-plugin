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





