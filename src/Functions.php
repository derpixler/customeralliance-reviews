<?php

namespace wp_customer_alliance\Reviews\Functions;

use wp_customer_alliance\Reviews;

/**
 * Return the textdomain for this Plugin
 *
 * @return string
 */
function get_textdomain(){

	$textdomain = new Reviews\Init();

	return $textdomain->get_textdomain();

}
