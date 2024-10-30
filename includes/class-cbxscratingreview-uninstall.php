<?php

	/**
	 * Fired during plugin uninstall
	 *
	 * @link       codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    CBXSCRatingReview
	 * @subpackage CBXSCRatingReview/includes
	 */

	/**
	 * Fired during plugin deactivation.
	 *
	 * This class defines all code necessary to run during the plugin's uninstallation.
	 *
	 * @since      1.0.0
	 * @package    CBXSCRatingReview
	 * @subpackage CBXSCRatingReview/includes
	 * @author     CBX Team  <info@codeboxr.com>
	 */
	class CBXSCRatingReview_Uninstall {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function uninstall() {

			global $wpdb;

			$settings = new CBXSCRatingReviewSettings();

			$delete_global_config = $settings->get_option( 'delete_global_config', 'cbxscratingreview_tools', 'no' );
			if ( $delete_global_config == 'yes' ) {
				$option_prefix = 'cbxscratingreview_';

				//delete plugin global options
				$option_values = CBXSCRatingReviewHelper::getAllOptionNames();

				foreach ( $option_values as $option_value ) {
					delete_option( $option_value['option_name'] );
				}

				//delete tables created by this plugin
				$table_names  = CBXSCRatingReviewHelper::getAllDBTablesList();
				$sql          = "DROP TABLE IF EXISTS " . implode( ', ', array_values( $table_names ) );
				$query_result = $wpdb->query( $sql );

				//delete meta values by keys
				$meta_keys = CBXSCRatingReviewHelper::getMetaKeys();

				foreach ( $meta_keys as $key => $value ) {
					delete_post_meta_by_key( $key );
				}

				//hooks to do more after uninstall
				do_action( 'cbxscratingreview_plugin_uninstall' );

			}
		}//end uninstall

	}//end class CBXSCRatingReview_Uninstall