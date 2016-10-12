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
	 * @return  object of this class
	 */
	public function get_instance() {

		NULL === self::$instance and self::$instance = new self;

		return self::$instance;
	}

	/**
	 * @param $setting_arguments a array of settings
	 */
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

	/**
	 * Set the textdomain of this plugin
	 *
	 * @param $textdomain
	 */
	private function set_textdomain( $textdomain ){

		self::$instance->textdomain = $textdomain;

	}

	/**
	 * Returns the textdomain of this plugin
	 *
	 * @return string
	 */
	public function get_textdomain(){

		return self::$instance->textdomain;

	}


}