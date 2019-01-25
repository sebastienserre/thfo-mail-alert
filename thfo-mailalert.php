<?php
/*
Plugin Name: WPCasa Mail Alert legacy
Plugin URI: https://www.thivinfo.com/downloads/wpcasa-mail-alert-pro/
Description: <strong>Do not Use me anymore -- This plugin is not maintened.</strong><br/> Please remove me and add the new one : <a href="https://wordpress.org/plugins/wpcasa-mail-alert/">WPCasa Mail Alert</a>
WARNING - This Plugins is working with old WPCasa Theme framework - A Premium version working with the WPCasa plugin exists.
Version: 1.5.0
Author: Sébastien Serre
Author URI: http://www.thivinfo.com
License: GPL2
Tested up to: 4.7.3
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

		add_action( 'admin_notices', array( $this, 'thfo_wpcasa_admin_notice_deprecated_notice' ) );

		register_activation_hook(__FILE__, array('thfo_mailalert', 'install'));
		register_uninstall_hook(__FILE__, array('thfo_mailalert', 'uninstall'));

		define( 'PLUGIN_VERSION','1.5.0' );

	}

	public function thfo_add_column() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'thfo_mailalert';
		$row = $wpdb->get_results( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME  = 'min_price' " );
		if (empty($row)) {
			$wpdb->query( "ALTER TABLE $table_name ADD min_price VARCHAR (10) " );
		}
		update_option( 'thfo_mailalert_version', PLUGIN_VERSION );
	}

	public function thfo_update_db() {
		$version = get_option( 'thfo_mailalert_version' );

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

	/**
	 * Admin Notice on Activation.
	 * @since 0.1.0
	 */
	public function thfo_wpcasa_admin_notice_deprecated_notice(){

		?>
			<div class="updated notice is-dismissible">
				<p><?php _e( 'Thank you for using WPCasa Mail Alert legacy! <strong>Unfortunately developpement is stopped </strong>', 'thfo-mail-alert'); ?>.</p>
				<p><?php _e( '<strong>This is the last update to ask you installing the new one</strong>', 'thfo-mail-alert'); ?>.</p>
				<a href="https://wordpress.org/plugins/wpcasa-mail-alert/" target="_blank" ><?php _e( 'Download the new version working with the WPCasa Plugin & (old) Framework', 'thfo-mail-alert' ); ?></a>
			</div>
			<?php
	}


}
new thfo_mail_alert();
