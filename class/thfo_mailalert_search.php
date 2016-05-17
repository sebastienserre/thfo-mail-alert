<?php
	add_action( 'post_submitbox_misc_actions', 'thfo_search_subscriber'  );

	function thfo_search_subscriber() {
		global $post;
		$city = "";
		if ( $post->post_type === 'property' ) {

			/**
			 * get city location
			 **/

			$terms = wp_get_object_terms( $post->ID, 'location' );

			foreach ( $terms as $term ) {
				$city = $term->name;
			}
			global $wpdb;

			/**
			 * get subcriber list for this city
			 */

			$subscribers = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}thfo_mailalert WHERE city = '$city' " );

			/**
			 * get price from property
			 */
			$prices = get_post_meta( $post->ID, '_price' );

			foreach ($prices as $p){
				$price = (int)$p;
			}

			/**
			 * get bedrooms number from property
			 */

			$rooms = get_post_meta( $post->ID, '_details_1' );

			foreach ($rooms as $room){
				$nb_room = (int)$room;
			}
			//$rooms = get_post_meta( $post->ID, '_details_1' );

			/**
			 * Search is running!
			 */
				foreach ( $subscribers as $subscriber ) {
					if ( $price <= $subscriber->max_price && $price >= $subscriber->min_price ) {

						if ($nb_room >= $subscriber->room) {
							$mail = $subscriber->email;

							//var_dump( $mail );
							thfo_send_mail( $mail );
						}
					}
					}
				}


	}

	function thfo_send_mail($mail){
		global $post;
		$recipient = $mail;
		$sender_mail = get_option('thfo_newsletter_sender_mail');
		if ( empty($sender_mail)){
			$sender_mail = get_option('admin_email');
		}

		$sender = get_option('thfo_newsletter_sender');
		$content = "";
		$object = get_option('thfo_newsletter_object');
		$img= get_option('empathy-setting-logo');
		$content .= '<img src="' . $img . '" alt="logo" />';
		$content .= get_option('thfo_newsletter_content');
		$content .= '<br /><a href="'.get_permalink().'"></a><br />';
		$content .= $post->guid ."<br />";
		$content .= '<p>' . __('To unsubscribe to this mail please follow this link: ', 'thfo-mail-alert');
		$url = get_option('thfo_unsubscribe_page');
		$content .= esc_url(home_url($url.'?remove='.$recipient)) . '<p>';
		$content .= get_option('thfo_newsletter_footer');

		$headers[] = 'Content-Type: text/html; charset=UTF-8';

		$headers[] = 'From:'.$sender.'<'.$sender_mail.'>';

		$result = wp_mail($recipient, $object, $content, $headers);

		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

	}