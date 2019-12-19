<?php
require("class.ContactFormWidget.php");
function wrp_widgets_init() {
	register_widget('ContactFormWidget');
}
add_action('widgets', 'controller');