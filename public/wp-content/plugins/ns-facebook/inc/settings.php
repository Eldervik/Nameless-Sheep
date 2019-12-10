<?php

// Add our Settings page to the WP Admin Menu
function nsfb_add_settings_page_to_menu() {
	add_submenu_page(
		'options-general.php', // parent page
		_e('Nameless Sheep Facebook Settings'), // page title
		_e('Facebook'), // menu title
		'manage_options', // minimum capability
		'nsfacebook', // slug for our page
		'nsfb_settings_page' // callback to render page
	);
}
add_action('admin_menu', 'nsfb_add_settings_page_to_menu');

// Render Settings page
function nsfb_settings_page() {
	?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

			<form method="post" action="options.php">
				<?php
					// output security fields for the setting section 'nsfb_general_options'
					settings_fields("nsfb_general_options");

					// output setting sections and their fields for page with slug 'relatedposts'
					do_settings_sections("nsfacebook");

					// output save settings button
					submit_button();
				?>
			</form>
		</div>
	<?php
}

// Register all options for our settings page
function nsfb_settings_init() {
	/**
	 * Add Settings Section 'General Options'
	 */
	add_settings_section(
		'nsfb_general_options', // id
		_e('General Options'), // section title
		'nsfb_general_options_section', // callback for rendering content below title and above settings fields
		'nsfacebook' // page to add this settings section to
	);

	/**
	 * Add Settings Fields to Settings Section 'General Options'
	 */

	//Set the title.
	add_settings_field(
		'nsfb_title', // id
		_e('Title:'), // label
		'nsfb_title_cb', // callback for rendering form field
		'nsfacebook', // page to add settings field to
		'nsfb_general_options' // section to add settings field to
	);
	register_setting('nsfb_general_options', 'nsfb_title');

    // Change number of posts to display.
	add_settings_field(
		'nsfb_number_fbposts', // id
		_e('Number of posts shown:'), // label
		'nsfb_number_fbposts_cb', // callback for rendering form field
		'nsfacebook', // page to add settings field to
		'nsfb_general_options' // section to add settings field to
	);
    register_setting('nsfb_general_options', 'nsfb_number_fbposts');

    // Add field for the token.
	add_settings_field(
		'nsfb_token', // id
		_e('Facebook api access token:'), // label
		'nsfb_token_cb', // callback for rendering form field
		'nsfacebook', // page to add settings field to
		'nsfb_general_options' // section to add settings field to
	);
    register_setting('nsfb_general_options', 'nsfb_token');
    
	 // Checkbox if you want to show author.
	add_settings_field(
		'nsfb_show_author', // id
		_e('Show author:'), // label
		'nsfb_show_author_cb', // callback for rendering form field
		'nsfacebook', // page to add settings field to
		'nsfb_general_options' // section to add settings field to
	);
    register_setting('nsfb_general_options', 'nsfb_show_author');

     // Checkbox if you want to show date.
	add_settings_field(
		'nsfb_show_date', // id
		_e('Show date:'), // label
		'nsfb_show_date_cb', // callback for rendering form field
		'nsfacebook', // page to add settings field to
		'nsfb_general_options' // section to add settings field to
	);
	register_setting('nsfb_general_options', 'nsfb_show_date');

     // Checkbox if you want to show pictures.
	add_settings_field(
		'nsfb_show_picture', // id
		_e('Show picture:'), // label
		'nsfb_show_picture_cb', // callback for rendering form field
		'nsfacebook', // page to add settings field to
		'nsfb_general_options' // section to add settings field to
	);
	register_setting('nsfb_general_options', 'nsfb_show_picture');

     // Checkbox if you want to show messages.
	add_settings_field(
		'nsfb_show_message', // id
		_e('Show message:'), // label
		'nsfb_show_message_cb', // callback for rendering form field
		'nsfacebook', // page to add settings field to
		'nsfb_general_options' // section to add settings field to
	);
	register_setting('nsfb_general_options', 'nsfb_show_message');
}
add_action('admin_init', 'nsfb_settings_init');

function nsfb_general_options_section() {
	?>	
		<p><?php _e('Section for settings for the Facebook plugin.')?></p>
	<?php
}

function nsfb_title_cb() {
	?>
		<input
			type="text"
			name="nsfb_title"
			id="nsfb_title"
			value="<?php echo get_option('nsfb_title', _e('Facebook')); ?>"
		>
	<?php
}

function nsfb_token_cb() {
	?>
		<input
			type="text"
			name="nsfb_token"
			id="nsfb_token"
			value="<?php echo get_option('nsfb_token', ''); ?>"
		>
	<?php
}

function nsfb_number_fbposts_cb() {
	?>
		<input
			type="text"
			name="nsfb_number_fbposts"
			id="nsfb_number_fbposts"
			value="<?php echo get_option('nsfb_number_fbposts', '5'); ?>"
		>
	<?php
}

function nsfb_show_author_cb() {
	?>
		<input
			type="checkbox"
			name="nsfb_show_author"
			id="nsfb_show_author"
			value="1"
			<?php
				checked(1, get_option('nsfb_show_author'));
			?>
		>
	<?php
}

function nsfb_show_date_cb() {
	?>
		<input
			type="checkbox"
			name="nsfb_show_date"
			id="nsfb_show_date"
			value="1"
			<?php
				checked(1, get_option('nsfb_show_date'));
			?>
		>
	<?php
}

function nsfb_show_picture_cb() {
	?>
		<input
			type="checkbox"
			name="nsfb_show_picture"
			id="nsfb_show_picture"
			value="1"
			<?php
				checked(1, get_option('nsfb_show_picture'));
			?>
		>
	<?php
}

function nsfb_show_message_cb() {
	?>
		<input
			type="checkbox"
			name="nsfb_show_message"
			id="nsfb_show_message"
			value="1"
			<?php
				checked(1, get_option('nsfb_show_message'));
			?>
		>
	<?php
}