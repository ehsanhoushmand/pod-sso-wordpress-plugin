<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Main Functions
 *
 * @author
 * @package WP Single Sign On Client
 */

/**
 * Function pod_sso_login_form_button
 *
 * Add login button for SSO on the login form.
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/login_form
 */
function pod_sso_login_form_button() {
	?>
    <a style="color: white; width:100%; text-align:center; margin-bottom:1em;" class="button button-primary button-large"
       href="<?php echo site_url( '?podSso' ); ?>">POD Sign in</a>
    <div style="clear:both;"></div>
	<?php
}

add_action( 'login_form', 'pod_sso_login_form_button' );

/**
 * Login Button Shortcode
 *
 * @param  [type] $atts [description]
 *
 * @return [type]       [description]
 */
function pod_single_sign_on_login_button_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'type'   => 'primary',
		'title'  => 'Login using Single Sign On',
		'class'  => 'podsso-button',
		'target' => '_blank',
		'text'   => 'Single Sign On'
	), $atts );

	return '<a class="' . $a['class'] . '" href="' . site_url( '?podSso' ) . '" title="' . $a['title'] . '" target="' . $a['target'] . '">' . $a['text'] . '</a>';
}

add_shortcode( 'podsso_button', 'pod_single_sign_on_login_button_shortcode' );

/**
 * Get user login redirect. Just in case the user wants to redirect the user to a new url.
 */
function podsso_get_user_redirect_url() {
	$options           = get_option( 'podsso_options' );
	$user_redirect_set = (isset($options['redirect_to_dashboard']) && $options['redirect_to_dashboard'] == '1' ) ? get_dashboard_url() : site_url();
	$user_redirect = apply_filters( 'podsso_user_redirect_url', $user_redirect_set );

	return $user_redirect;
}