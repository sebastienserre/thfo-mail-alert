<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 27/01/16
 * Time: 17:14
 */
class thfo_mailalert {
	function __construct() {
		add_action( 'widgets_init', function () {
			register_widget( 'thfo_mailalert_widget' );
		} );

		add_action( 'wp_loaded', array( $this, 'save_results' ) );
		add_action( 'wp_loaded', array( $this, 'thfo_delete_subscriber' ) );

	}

	public static function install() {
		global $wpdb;
		$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}thfo_mailalert(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR (255), email VARCHAR (255) NOT NULL, tel VARCHAR (20), city VARCHAR (255), max_price VARCHAR (10), min_price VARCHAR (10), room VARCHAR (2));" );
	}

	public static function uninstall() {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}thfo_mailalert;" );
	}


	public function save_results() {

		if ( isset( $_POST['thfo_mailalert'] ) ) {

			//var_dump($_POST);
			global $wpdb;
			$name  = sanitize_text_field($_POST['thfo_mailalert_name']);
			if (is_email($_POST['thfo_mailalert_email'])) {
				$email = sanitize_email( $_POST['thfo_mailalert_email'] );
			}
			$phone = sanitize_text_field($_POST['thfo_mailalert_phone']);
			$city  = $_POST['thfo_mailalert_city'];
			$price = $_POST['thfo_mailalert_price'];
			$minprice = $_POST['thfo_mailalert_min_price'];
			$room  = $_POST['thfo_mailalert_room'];
			$date = current_time('mysql');

			$wpdb->replace( "{$wpdb->prefix}thfo_mailalert", array(
				'name'      => $name,
				'email'     => $email,
				'tel'       => $phone,
				'city'      => $city,
				'max_price' => $price,
				'room'      => $room,
				'subscription' => $date,
				'min_price' => $minprice,
			) );

		}
	}

	public function thfo_delete_subscriber(){
		if (isset($_GET['delete']) && ! empty($_GET['delete'])){
			global $wpdb;
			$wpdb->delete("{$wpdb->prefix}thfo_mailalert", array('email' => $_GET['delete']));
		}
	}


}