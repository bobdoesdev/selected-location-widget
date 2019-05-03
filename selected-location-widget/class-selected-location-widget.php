<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Widget API: WP_Widget_Search class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * Core class used to implement a Search widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class Selected_Location_Widget extends WP_Widget {

	/**
	 * Sets up a new Search widget instance.
	 *
	 * @since 2.8.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'location-widget',
			'description' => __( 'A widget for displaying pickup location selected with WPSL.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'search', _x( 'Selected Location', 'Selected Location Widget' ), $widget_ops );

		add_action( 'widgets_init', array($this, 'register_new_widget') );
	}

	public function register_new_widget() {
		register_widget( 'Selected_Location_Widget' );
	}

	/**
	 * Outputs the content for the current Search widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Search widget instance.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		if (isset($_COOKIE['dei_drop_off_point'])) {
			$pickup = $_COOKIE['dei_drop_off_point'];
		}

			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			if (isset($pickup)) {

				$city = get_post_meta( $pickup, 'wpsl_city', true);
				$state = get_post_meta( $pickup, 'wpsl_state', true);
				$address = get_post_meta( $pickup, 'wpsl_address', true);
				$zip = get_post_meta( $pickup, 'wpsl_zip', true);
				$image =  get_the_post_thumbnail( $pickup, 'medium' );

				echo '<div class="location-outer">';

				

				if ($image) {
					echo '<div class="location-inner">';

					echo $image;

					echo '<div class="location-city">';

					echo $city.', '.$state;

					echo '</div>';

					echo '</div>';

				}

				echo '<div class="location-details">';

				echo '<p><b>Dropoff Location:</b></p>';

				echo '<p>'.$address.'</p>';

				echo '<p>'.$city.', '.$state.'</p>';

				echo '<a href="/choose-a-location" class="change-location">Change<i class="fa fa-chevron-right"></i></a>';

				echo '</div>';

				echo '</div>';

			} else{

				echo '<h5>You have not yet chosen a location.</h5>';

				echo '<a href="/choose-a-location" class="choose-location">Choose a Location<i class="fa fa-chevron-right"></i></a>';


			}

			echo $args['after_widget'];

		
	}

	/**
	 * Outputs the settings form for the Search widget.
	 *
	 * @since 2.8.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		<?php
	}

	/**
	 * Handles updating settings for the current Search widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}

}


$selected_location_widget = new Selected_Location_Widget();