<?php
/**
 * File: rewrites.php
 *
 * @author
 * @package WP Single Sign On Client
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Class PODSSO_Rewrites
 *
 */
class PODSSO_Rewrites {

	function create_rewrite_rules( $rules ) {
		global $wp_rewrite;
		$newRule  = array( 'auth/(.+)' => 'index.php?auth=' . $wp_rewrite->preg_index( 1 ) );
		$newRules = $newRule + $rules;

		return $newRules;
	}

	function add_query_vars( $qvars ) {
		$qvars[] = 'auth';

		return $qvars;
	}

	function flush_rewrite_rules() {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}

	function template_redirect_intercept() {
        if ( isset($_GET['podSso']) ) {
			require_once( dirname( dirname( __FILE__ ) ) . '/includes/callback.php' );
			exit;
		}
	}
}

$PODSSO_Rewrites = new PODSSO_Rewrites();
add_filter( 'rewrite_rules_array', array( $PODSSO_Rewrites, 'create_rewrite_rules' ) );
add_filter( 'query_vars', array( $PODSSO_Rewrites, 'add_query_vars' ) );
add_filter( 'wp_loaded', array( $PODSSO_Rewrites, 'flush_rewrite_rules' ) );
add_action( 'template_redirect', array( $PODSSO_Rewrites, 'template_redirect_intercept' ) );