<?php

require("class.nsfacebook.php");

function nsfb_widgets_init() {
	register_widget('nsfacebook');
}
add_action('widgets_init', 'nsfb_widgets_init');
