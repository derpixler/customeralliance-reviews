<?php

namespace wp_customer_alliance\Reviews;

class Init {

	/**
	 * Plugin instance.
	 *
	 * @see   get_instance()
	 * @type  object
	 */
	protected static $instance = NULL;

	/**
	 * Access this plugin’s working instance
	 *
	 * @wp-hook plugins_loaded
	 * @since   02/12/1974
	 * @return  object of this class
	 */
	public function get_instance() {

		NULL === self::$instance and self::$instance = new self;

		return self::$instance;
	}

	public function setup( $setting_arguments ){

		if( array_key_exists( 'textdomain', $setting_arguments ) )
			$this->set_textdomain( $setting_arguments[ 'textdomain' ] );

		/*
		 * Implement plugin admin settings page
		 */
		if( is_admin() ) {

			new Pages\Admin\Settings();

		}

	}

	private function set_textdomain( $textdomain ){

		self::$instance->textdomain = $textdomain;

	}

	public function get_textdomain(){

		return self::$instance->textdomain;

	}


}