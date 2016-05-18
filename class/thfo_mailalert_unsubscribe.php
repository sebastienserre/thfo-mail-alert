<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 07/02/16
 * Time: 00:04
 */
class thfo_mailalert_unsubscribe {
	public function __construct()
	{
		add_shortcode('thfo_mailalert_unsubscribe',array($this,'unsubscribe_html'));

	}

	public function unsubscribe_html()
	{ ?>
		<form class="thfo_unsubscribe" method="post" action="">
			<label><?php _e('Please add your mail', 'thfo_mailalert'); ?></label>
			<input type="email" name="email" <?php
			if ( isset($_GET['remove']) && ! empty($_GET['remove'])){ ?>
				value="<?php echo $_GET['remove']; ?>"
			<?php }

			?>/>
			<input type="submit" name="delete" value="<?php _e('unsubscribe','thfo-mail-alert'); ?>" />
		</form>
		<?php

		/**
		 * fires before deleting a subscriber
		 * @since 1.4.0
		 */
		do_action('thfo_before_deleting_subscriber');

		if ( isset( $_POST['delete']) && ! empty( $_POST['delete']) )
		{
			if ( is_email($_POST['email'])){
				$mail = sanitize_email($_POST['email']);
			}

			global $wpdb;
			$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}thfo_mailalert WHERE email = '$mail'");

			if (!is_null($row)) {
				$wpdb->delete("{$wpdb->prefix}thfo_mailalert", array('email' => $mail)); ?>
				<div class="thfo-mailalert-del"> <?php _e("Your mail address has been successfully deleted from our database","thfo-mail-alert"); ?> </div>
			<?php }
		}

		/**
		 * fires after deleting a subscriber
		 * @since 1.4.0
		 */
		do_action('thfo_after_deleting_subscriber');

	}

}