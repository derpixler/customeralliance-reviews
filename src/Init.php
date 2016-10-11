<?php

namespace wp_customer_alliance\Reviews;

class Init {

	public function __construct(){

		if( is_admin() ) {

			new Pages\Admin\Settings();

		}

	}

}