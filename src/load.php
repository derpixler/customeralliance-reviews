<?php

namespace wp_customer_alliance\Reviews;

use Requisite\Requisite;
use Requisite\Rule\Psr4;
use Requisite\SPLAutoLoader;

/**
 * Load the plugin initializes the autoloader
 *
 * @package wp_customer_alliance\reviews
 */
class Load {

	/**
	 * the constructor call init
	 */
	public function __construct(){
		$this->init();
	}

	/**
	 * Check if Requisite class declared
	 * and set autoload rules
	 */
	private function init() {

		/**
		 * Load the Requisite library. Alternatively you can use composer's
		 */
		$declared_classes = array_flip( get_declared_classes() );

		if( ! $declared_classes[ 'Requisite\Requisite' ] ){

			require_once __DIR__ . '/requisite/src/Requisite/Requisite.php';

			Requisite::init();

		}

		$autoloader = new SPLAutoLoader;

		$autoloader->addRule(
			new Psr4(
				__DIR__,    // base directory
				__NAMESPACE__ // base namespace
			)
		);

		new Init();

	}

}