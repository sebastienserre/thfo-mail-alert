<?php
/*
Plugin Name: Thfo Mail Alert
Plugin URI: http://www.thivinfo.com
Description: Allow Visitor to subscribe to a mail alert to receive a mail when a new property is added.
Version: 1.3.0
Author: Sébastien Serre
Author URI: http://www.thivinfo.com
License: GPL2
Text Domain: thfo-mail-alert
Domain Path: /languages
*/

class thfo_mail_alert {
	function __construct() {

		include_once plugin_dir_path( __FILE__ ).'/class/thfo_mailalert_load.php';
		include_once plugin_dir_path( __FILE__ ).'/class/thfo_mailalert_widget.php';
		include_once plugin_dir_path( __FILE__ ).'/class/thfo_mailalert_search.php';
		include_once plugin_dir_path( __FILE__ ).'/class/thfo_mailalert_admin_menu.php';
		include_once plugin_dir_path( __FILE__ ).'/class/thfo_mailalert_unsubscribe.php';

		new thfo_mailalert();
		new thfo_mailalert_widget();
		new thfo_mailalert_admin_menu();
		new thfo_mailalert_unsubscribe();

		add_action( 'plugins_loaded', array( $this, 'thfo_load_textdomain' ) );
		add_action( 'admin_init', array($this, 'thfo_register_admin_style') );
		add_action( 'admin_init', array($this, 'thfo_check_theme') );
		add_action( 'admin_init', array($this, 'thfo_update_db') );
		add_action('admin_notice', array($this,'thfo_wpcasa_missing_notice' ));
		add_action( 'wp_enqueue_scripts', array($this, 'thfo_register_style') );

		register_activation_hook(__FILE__, array('thfo_mailalert', 'install'));
		register_uninstall_hook(__FILE__, array('thfo_mailalert', 'uninstall'));

		define( 'PLUGIN_VERSION','1.3.0' );

	}

	public function thfo_add_column() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'thfo_mailalert';
		$row = $wpdb->get_results( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME  = 'min_price' " );
		if (empty($row)) {
			$wpdb->query( "ALTER TABLE $table_name ADD min_price VARCHAR (10) " );
		}
		update_option( 'version', PLUGIN_VERSION );
	}

	public function thfo_update_db() {
		$version = get_option( 'version' );
//var_dump( $version ); die;
		if ( $version != PLUGIN_VERSION ) {

			$this->thfo_add_column();
		}
	}

	public function thfo_load_textdomain() {
		load_plugin_textdomain( 'thfo-mail-alert', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function thfo_register_admin_style(){
		wp_enqueue_style('thfo_mailalert_admin_style', plugins_url( 'assets/css/admin-styles.css',__FILE__ ));
	}

	public function thfo_register_style(){
		wp_enqueue_style('thfo_mailalert_style', plugins_url( 'assets/css/styles.css', __FILE__ ));
	}

	public function thfo_check_theme(){
		$themes = wp_get_theme('wpcasa');

		if ( ! $themes->exists() ){
			$wpcasa_exists = 0;
			update_option('wp_casa_exists', $wpcasa_exists);
		}
	}

	public function thfo_wpcasa_missing_notice(){
		$wpcasa = get_option('wp_casa_exists');

		if ($wpcasa === '0') {
			$class   = 'notice notice-error';
			$message = __( 'WPCasa Framework isn\'t available in your installation! <br />', 'thfo-mail-alert' );
			$message .= __( 'This plugin needs it to properly work', 'thfo-mail-alert' );
			echo '<div class=" ' .$class. ' "><p> '. $message.' </p></div>';
		}
	}


}
new thfo_mail_alert();