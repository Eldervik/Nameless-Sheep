<?php
/**
 * Adds Nameless Sheep Facebook widget.
 */
class nsfacebook extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'nsfacebook', // Base ID
			'Nameless Sheep Facebook', // Name
			[
				'description' => __('A Widget for displaying posts from facebook.', 'nsfacebook'),
			] // Args
		);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance) {
        if (!is_front_page()) {
			return;
		}
		extract($args);
        $title = get_option('nsfb_title');
		// start widget
		echo $before_widget;
		// title
		if (! empty($title)) {
			echo $before_title . $title . $after_title;
		}
		// content
        nsfb_get_facebook();
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form($instance) { 
        ?>
		<p>
			<?php _e('Settings page:')?> <a href="https://nameless-sheep.test/wp-admin/options-general.php?page=nsfacebook"><?php _e('Settings')?></a>
        </p>
	<?php
	}
}
