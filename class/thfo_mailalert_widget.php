<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 27/01/16
 * Time: 17:57
 */
class thfo_mailalert_widget extends WP_Widget {

	function __construct() {
		parent::__construct( 'thfo_mailalert', __('Mail Alert','thfo-mail-alert'), array( 'description' => __('Form to add a property search mail alert','thfo-mail-alert') ) );

	}

	public function multiexplode ($delimiters,$string) {

		$ready = str_replace($delimiters, $delimiters[0], $string);
		$launch = explode($delimiters[0], $ready);
		return  $launch;
	}


	/**
	 * Create a front office widget
	 * @param array $args
	 * @param array $instance
	 */

	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		echo $args['before_title'];

		echo apply_filters( 'widget_title', $instance['title'] );

		echo $args['after_title'];

		$prices = get_option('thfo_max_price');
		$prices = $this->multiexplode(array(',',', '), $prices);
		//$prices = explode(', ', $prices);
		//$prices = preg_split("/ (,|, ) /", $prices);
		//$prices = explode(',', $prices);
		//var_dump($prices);
		?>

		<form action="" method="post">
			<p>
				<label for="thfo_mailalert_name"> <?php _e('Your name', 'thfo-mail-alert') ?>*</label>
				<input id="thfo_mailalert_name" name="thfo_mailalert_name" required/>
				<label for="thfo_mailalert_email"> <?php _e('Your Email', 'thfo-mail-alert') ?>*</label>
				<input id="thfo_mailalert_email" name="thfo_mailalert_email" type="email" required/>
				<label for="thfo_mailalert_phone"> <?php _e('Your Phone number', 'thfo-mail-alert') ?></label>
				<input id="thfo_mailalert_phone" name="thfo_mailalert_phone" />
				<label for="thfo_mailalert_city"> <?php _e('City', 'thfo-mail-alert') ?></label>
				<select name="thfo_mailalert_city">
					<?php
					$city = get_terms( 'location' );
					foreach ($city as $c){
						$cities = $c->name; ?>
						<option name="thfo_mailalert_city" value="<?php echo $cities ?>"><?php echo $cities ?></option>
					<?php }
					?>
				</select>
				<label for="thfo_mailalert_price"> <?php _e('Maximum Price', 'thfo-mail-alert') ?></label>
				<select name="thfo_mailalert_price">
					<?php
					foreach ($prices as $price){ ?>
						<option name="thfo_mailalert_price" value="<?php echo $price  ?>"><?php echo $price  ?>€</option>
					<?php }
					?>
					<option name="thfo_mailalert_price" value="more"><?php _e('more', 'thfo-mail-alert') ?></option>
				</select>
				<label for="thfo_mailalert_room"> <?php _e('Room', 'thfo-mail-alert') ?></label>
				<select name="thfo_mailalert_room">
					<option name="thfo_mailalert_room" value="1">1</option>
					<option name="thfo_mailalert_room" value="2">2</option>
					<option name="thfo_mailalert_room" value="3">3</option>
					<option name="thfo_mailalert_room" value="4">4</option>
					<option name="thfo_mailalert_room" value="5">5</option>
					<option name="thfo_mailalert_room" value="6">6</option>
					<option name="thfo_mailalert_room" value="7">7</option>
					<option name="thfo_mailalert_room" value="8">8</option>
					<option name="thfo_mailalert_room" value="9">9</option>
					<option name="thfo_mailalert_room" value="10">10</option>
				</select>
			</p>
			<input name="thfo_mailalert" class="moretag btn btn-primary" type="submit" />
		</form>
<?php
		echo $args['after_widget'];
	}

	/**
	 * Affichage du Widget en BO
	 * @param array $instance
	 */

	public function form($instance)
	{
		$title = isset($instance['title']) ? $instance['title'] : ''; ?>
		<p>
			<label for="<?php echo $this->get_field_name('title'); ?>"> <?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
			       name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>

		</p>

		<?php
	}


}