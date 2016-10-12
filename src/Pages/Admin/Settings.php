<?php

namespace wp_customer_alliance\Reviews\Pages\Admin;

use wp_customer_alliance\Reviews\Functions as Functions;


class Settings {

	public function __construct(){

		print_r( Functions\get_textdomain() );
		die();

	}

}