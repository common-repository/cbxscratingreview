<?php

	/**
	 * Define the internationalization functionality
	 *
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 *
	 * @link       http://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    CBXSCRatingReview
	 * @subpackage CBXSCRatingReview/includes
	 */

	/**
	 * Define the internationalization functionality.
	 *
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 *
	 * @since      1.0.0
	 * @package    CBXSCRatingReview
	 * @subpackage CBXSCRatingReview/includes
	 * @author     Sabuj Kundu <sabuj@codeboxr.com>
	 */
	class CBXSCRatingReview_i18n {


		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain() {

			load_plugin_textdomain(
				'cbxscratingreview',
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);

		}


	}