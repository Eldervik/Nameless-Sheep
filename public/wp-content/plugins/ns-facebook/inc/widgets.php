<?php

require("class.nsfacebook.php");

function wrp_widgets_init() {
	register_widget('nsfacebook');
}
add_action('widgets_init', 'wrp_widgets_init');
