<?php

// disable direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// callback: validate options
function podsso_callback_validate_options( $input ) {

	if ( isset( $input['server_url'] ) ) {
		
		$input['server_url'] = esc_url( $input['server_url'] );
		
	}

	if ( isset( $input['api_url'] ) ) {

		$input['api_url'] = esc_url( $input['api_url'] );

	}

	if ( isset( $input['pay_invoice_url'] ) ) {

		$input['pay_invoice_url'] = esc_url( $input['pay_invoice_url'] );

	}

	return $input;
	
}


