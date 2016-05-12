<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 28/01/16
 * Time: 22:30
 */
class thfo_mailalert_admin_menu {
	function __construct() {

		add_action('admin_menu', array($this, 'thfo_admin_menu'));
		add_action('admin_menu', array($this, 'thfo_delete_subscriber'));
		add_action('admin_init', array($this, 'register_settings'));
	}

	public function thfo_admin_menu(){
		add_menu_page(__('Mail Alert', 'thfo-mail-alert'),__('Mail Alert', 'thfo-mail-alert'),'manage_options','thfo-mail-alert', array($this, 'thfo_menu_html'),plugin_dir_url( __DIR__ ) . 'assets/img/icon.png');
		add_submenu_page('thfo-mail-alert',__('Mail Settings', 'thfo-mail-alert'),__('Mail Settings', 'thfo-mail-alert'),'manage_options', 'thfo-mailalert-mail-settings', array($this,'menu_html'));
		add_submenu_page('thfo-mail-alert',__('General Options', 'thfo-mail-alert'),__('General Options', 'thfo-mail-alert'),'manage_options', 'thfo_mailalert_options', array($this,'general_html'));
	}

	public function general_html(){
		echo '<h1>'.get_admin_page_title().'</h1>'; ?>
		<form method="post" action="options.php">
			<?php settings_fields('thfo_newsletter_options') ?>
			<?php do_settings_sections('thfo_general_options') ?>
			<?php submit_button(__('Save')); ?>


		</form>
	<?php }


	public function menu_html()
	{
		echo '<h1>'.get_admin_page_title().'</h1>'; ?>

		<form method="post" action="options.php">
			<?php settings_fields('thfo_newsletter_settings') ?>
			<?php do_settings_sections('thfo_newsletter_settings') ?>
			<?php submit_button(__('Save')); ?>


		</form>

		<?php
	}

	public function register_settings()
	{
		/* Mail Settings */
		add_settings_section('thfo_newsletter_section', __('Outgoing parameters','thfo-mail-alert'), array($this, 'section_html'), 'thfo_newsletter_settings');

		register_setting('thfo_newsletter_settings', 'thfo_newsletter_sender');
		register_setting('thfo_newsletter_settings', 'thfo_newsletter_sender_mail');
		register_setting('thfo_newsletter_settings', 'thfo_newsletter_object');
		register_setting('thfo_newsletter_settings', 'thfo_newsletter_content');
		register_setting('thfo_newsletter_settings', 'thfo_newsletter_footer');
		register_setting('thfo_newsletter_settings', 'empathy-setting-logo');

		add_settings_field('thfo_newsletter_sender', __('Sender','thfo-mail-alert'), array($this, 'sender_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
		add_settings_field('empathy-setting-logo', __('Header picture','thfo-mail-alert'), array($this, 'media_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
		add_settings_field('thfo_newsletter_footer', __('footer','thfo-mail-alert'), array($this, 'footer_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
		add_settings_field('thfo_newsletter_sender_mail', __('email','thfo-mail-alert'), array($this, 'sender_mail_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
		add_settings_field('thfo_newsletter_object', __('Object','thfo-mail-alert'), array($this, 'object_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');
		add_settings_field('thfo_newsletter_content', __('Content','thfo-mail-alert'), array($this, 'content_html'), 'thfo_newsletter_settings', 'thfo_newsletter_section');

		/* General options*/
		add_settings_section('thfo_newsletter_option_section', __('General Options','thfo-mail-alert'), array($this, 'general_section_html'), 'thfo_general_options');

		register_setting('thfo_newsletter_options', 'thfo_unsubscribe_page');
		register_setting('thfo_newsletter_options', 'thfo_thanks_page');
		register_setting('thfo_newsletter_options', 'thfo_max_price');


		add_settings_field('thfo_unsubscribe_page', __('Unsubscribe Page','thfo-mail-alert'), array($this, 'option_html'), 'thfo_general_options', 'thfo_newsletter_option_section');
		add_settings_field('thfo_max_price', __('Maximum Price','thfo-mail-alert'), array($this, 'thfo_max_price'), 'thfo_general_options', 'thfo_newsletter_option_section');


	}

	public function thfo_max_price(){
		$max_price = get_option('thfo_max_price')?>
		<input name="thfo_max_price" id="thfo_max_price" type="text" value="<?php if ( !empty($max_price)) : echo $max_price; endif ?>">
		<p><?php _e('Please enter maximum price separated by a comma','thfo_mail_alert') ?></p>

	<?php }

	public function general_section_html(){
		echo '<p>'.__('Select your options','thfo-mail-alert').'</p>';
	}

	public function option_html(){
		$pages = get_pages(array( 'post_publish' => 'publish'));
		//var_dump($pages);
		?>
		<select name='thfo_unsubscribe_page' id='thfo_unsubscribe_page'>
			<?php foreach ( $pages as $page ) { ?>

				<option name='thfo_unsubscribe_page' id='thfo_unsubscribe_page' value='<?php echo $page->post_name; ?>'
				<?php
				$unsubscribe = get_option('thfo_unsubscribe_page');
				if ( ! empty ($unsubscribe) && $unsubscribe === $page->post_name ){
					echo "selected";
				}
				?> > <?php echo $page->post_title; ?> </option>

			<?php } ?>
		</select>
	<?php }


	public function media_html(){ ?>
		<input type="text" name="empathy-setting-logo" id="empathy-setting-logo" value="<?php echo  esc_attr(get_option( 'empathy-setting-logo' )) ; ?>">
		<a class="button" onclick="upload_image('empathy-setting-logo');"><?php _e('Upload', 'thfo-mail-alert') ?></a>
		<script>
			var uploader;
			function upload_image(id) {
				console.log(id);

				//Extend the wp.media object
				uploader = wp.media.frames.file_frame = wp.media({
					title: 'Choose Image',
					button: {
						text: 'Choose Image'
					},
					multiple: false
				});

				//When a file is selected, grab the URL and set it as the text field's value
				uploader.on('select', function() {
					attachment = uploader.state().get('selection').first().toJSON();
					var url = attachment['url'];
					jQuery('#'+id).val(url);
				});

				//Open the uploader dialog
				uploader.open();
			}
		</script>
	<?php }

	public function section_html()

	{

		echo '<p>'.__('Advise about outgoing parameters.','thfo-mail-alert').'</p>';

	}

	public function footer_html(){
		?>
		<textarea name="thfo_newsletter_footer"><?php echo get_option('thfo_newsletter_footer')?></textarea>

		<?php
	}

	public function sender_html()
	{?>
		<input type="text" name="thfo_newsletter_sender" value="<?php echo get_option('thfo_newsletter_sender')?>"/>
		<?php
	}

	public function sender_mail_html()
	{?>
		<input type="email" name="thfo_newsletter_sender_mail" value="<?php echo get_option('thfo_newsletter_sender_mail')?>"/>
		<?php
	}


	public function object_html()

	{?>

		<input type="text" name="thfo_newsletter_object" value="<?php echo get_option('thfo_newsletter_object')?>"/>
		<?php


	}


	public function content_html()

	{
		wp_editor(get_option('thfo_newsletter_content'),'thfo_newsletter_content' );
	}

	public function process_action()
	{

		if (isset($_POST['send_newsletter'])) {

			$this->send_newsletter();

		}

	}

	public function  thfo_menu_html(){

		global $wpdb;
		$subscribers = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}thfo_mailalert ");
		$count = count($subscribers);


		echo '<h2>' . get_admin_page_title() . '</h2>';
		if ($count == 0){
			echo '<p>';
			_e('0 subscriber', 'thfo-mail-alert');
			echo '</p>';
		} else {

			echo '<p>';
			printf( _n( '1 subscriber:', '%s subscribers:', $count, 'thfo-mail-alert' ), number_format_i18n( $count ) );
			echo '</p>';

		}?>

		<table class="thfo_subscriber" >
			<tr>
				<th><?php _e('Date', 'thfo-mail-alert') ?></th>
				<th><?php _e('Name', 'thfo-mail-alert') ?></th>
				<th><?php _e('Email', 'thfo-mail-alert') ?></th>
				<th><?php _e('Phone', 'thfo-mail-alert') ?></th>
				<th><?php _e('City Searched', 'thfo-mail-alert') ?></th>
				<th><?php _e('Minimum price', 'thfo-mail-alert') ?></th>
				<th><?php _e('Maximum price', 'thfo-mail-alert') ?></th>
				<th><?php _e('Room', 'thfo-mail-alert') ?></th>
				<th><?php _e('Delete', 'thfo-mail-alert') ?></th>
			</tr>
			<?php
			foreach ($subscribers as $subscriber){
				$id = $subscriber->id;
				$date = mysql2date('G', $subscriber->subscription, true) ?>
				<tr>
					<td><?php echo date_i18n('d/m/Y', $date ); ?></td>
					<td><?php echo $subscriber->name ?></td>
					<td><?php echo $subscriber->email ?></td>
					<td><?php echo $subscriber->tel ?></td>
					<td><?php echo $subscriber->city ?></td>
					<td><?php echo $subscriber->min_price ?>€</td>
					<td><?php echo $subscriber->max_price ?>€</td>
					<td><?php echo $subscriber->room ?></td>

					<td>
						<?php

						$url = admin_url( 'admin.php?page=' );
						$url .= basename(dirname( __DIR__));
						$url .= '&id='. $id .'&delete=yes';
						?>
						<a href="<?php echo esc_url($url); ?>" title="<?php _e('Delete', 'thfo-mail-alert') ?>"><span class="dashicons dashicons-trash"></span> </a> </td>

				</tr>

			<?php }

			?>
		</table>

	<?php }

	public function thfo_delete_subscriber(){
		if (isset($_GET['delete']) && $_GET['delete'] == 'yes'){
			$id = $_GET['id'];
			global $wpdb;
			$wpdb->delete("{$wpdb->prefix}thfo_mailalert",array('id' => $id));
		}
	}

}