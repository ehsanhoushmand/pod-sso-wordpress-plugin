<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Main Class
 *
 * @author
 * @package WP Single Sign On Client
 */
class PODSSO_Client {

	/** Version */
	public $version = '1.0.0';

	/** Server Instance */
	public static $_instance = null;

	function __construct() {

		add_action( 'init', array( __CLASS__, 'includes' ) );
	}

	/**
	 * populate the instance if the plugin for extendability
	 * @return object plugin instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * plugin includes called during load of plugin
	 * @return void
	 */
	public static function includes() {
		require_once( PODSSO_FILE . 'includes/functions.php' );
		require_once( PODSSO_FILE . 'includes/rewrites.php' );
		require_once( PODSSO_FILE . 'includes/filters.php' );
	}

}

function _PODSSO() {
	return PODSSO_Client::instance();
}

$GLOBAL['PODSSO'] = _PODSSO();