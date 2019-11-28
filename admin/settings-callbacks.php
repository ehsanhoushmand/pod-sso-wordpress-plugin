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
// callback: avand section
function podsso_callback_section_avand() {

	echo '<p>' . esc_html__('These settings enable you to customize the Avand Api.', 'pod-sso-plugin') . '</p>';

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



function podsso_callback_field_select($args)
{
    $options = get_option( 'podsso_options', podsso_options_default() );
    $id    = $args['id'] ?? '';
    $selected_option = sanitize_text_field( $options[$id] ?? '');
    $select_options = $args['option'] ?? [];

    echo '<select id="podsso_options_'. $id .'" name="podsso_options['. $id .']">';

    foreach ( $select_options as $option ) {

        $selected = selected( $selected_option === $option['code'], true, false );

        echo '<option value="'. $option['code'] .'"'. $selected .'>'. $option['name'] .'</option>';

    }

    echo '</select>';
}





