<?php

	/**
	 * The plugin bootstrap file
	 *
	 * This file is read by WordPress to generate the plugin information in the plugin
	 * admin area. This file also includes all of the dependencies used by the plugin,
	 * registers the activation and deactivation functions, and defines a function
	 * that starts the plugin.
	 *
	 * @link              http://codeboxr.com
	 * @since             1.0.0
	 * @package           CBXSCRatingReview
	 *
	 * @wordpress-plugin
	 * Plugin Name:       CBX 5 Star Rating & Review
	 * Plugin URI:        https://codeboxr.com/product/cbx-single-criteria-rating-review-for-wordpress/
	 * Description:       Quick 5 star single Criteria Rating & Review for WordPress
	 * Version:           1.0.7
	 * Author:            Codeboxr
	 * Author URI:        http://codeboxr.com
	 * License:           GPL-2.0+
	 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:       cbxscratingreview
	 * Domain Path:       /languages
	 */

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}


	defined( 'CBXSCRATINGREVIEW_PLUGIN_NAME' ) or define( 'CBXSCRATINGREVIEW_PLUGIN_NAME', 'cbxscratingreview' );
	defined( 'CBXSCRATINGREVIEW_PLUGIN_VERSION' ) or define( 'CBXSCRATINGREVIEW_PLUGIN_VERSION', '1.0.7' );
	defined( 'CBXSCRATINGREVIEW_BASE_NAME' ) or define( 'CBXSCRATINGREVIEW_BASE_NAME', plugin_basename( __FILE__ ) );
	defined( 'CBXSCRATINGREVIEW_ROOT_PATH' ) or define( 'CBXSCRATINGREVIEW_ROOT_PATH', plugin_dir_path( __FILE__ ) );
	defined( 'CBXSCRATINGREVIEW_ROOT_URL' ) or define( 'CBXSCRATINGREVIEW_ROOT_URL', plugin_dir_url( __FILE__ ) );


	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-cbxscratingreview-activator.php
	 */
	function activate_cbxscratingreview() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxscratingreview-activator.php';
		CBXSCRatingReview_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-cbxscratingreview-deactivator.php
	 */
	function deactivate_cbxscratingreview() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxscratingreview-deactivator.php';
		CBXSCRatingReview_Deactivator::deactivate();
	}

	/**
	 * The code that runs during plugin uninstall.
	 * This action is documented in includes/class-cbxscratingreview-uninstall.php
	 */
	function uninstall_cbxscratingreview() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxscratingreview-uninstall.php';
		CBXSCRatingReview_Uninstall::uninstall();
	}

	register_activation_hook( __FILE__, 'activate_cbxscratingreview' );
	register_deactivation_hook( __FILE__, 'deactivate_cbxscratingreview' );
	register_uninstall_hook( __FILE__, 'uninstall_cbxscratingreview' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-cbxscratingreview.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_cbxscratingreview() {

		$plugin = new CBXSCRatingReview();
		$plugin->run();

	}

	run_cbxscratingreview();