<?php
	/**
	 * The file that defines the core plugin class
	 *
	 * A class definition that includes attributes and functions used across both the
	 * public-facing side of the site and the admin area.
	 *
	 * @link       http://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    CBXSCRatingReview
	 * @subpackage CBXSCRatingReview/includes
	 */

	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 * @package    CBXSCRatingReview
	 * @subpackage CBXSCRatingReview/includes
	 * @author     Sabuj Kundu <sabuj@codeboxr.com>
	 */
	class CBXSCRatingReview {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      CBXSCRatingReview_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $plugin_name The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $version The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

			$this->version     = CBXSCRATINGREVIEW_PLUGIN_VERSION;
			$this->plugin_name = CBXSCRATINGREVIEW_PLUGIN_NAME;

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - CBXSCRatingReview_Loader. Orchestrates the hooks of the plugin.
		 * - CBXSCRatingReview_i18n. Defines internationalization functionality.
		 * - CBXSCRatingReview_Admin. Defines all hooks for the admin area.
		 * - CBXSCRatingReview_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxscratingreview-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxscratingreview-i18n.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxscratingreview-tpl-loader.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxscratingreview-setting.php';


			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Html2Text.php';
			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Html2TextException.php';
			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/emogrifier.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxscratingreview-emailtemplate.php';

			//mail sending helper
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxscratingreview-mailhelper.php';
			//all email alert static method
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxscratingreview-emailalert.php';


			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxscratingreview-helper.php';


			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxscratingreview-review-logs.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxscratingreview-rating-avg-logs.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cbxscratingreview-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the frontend site area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cbxscratingreview-public.php';


			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cbxscratingreview-functions.php';

			$this->loader = new CBXSCRatingReview_Loader();
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the CBXSCRatingReview_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {
			$plugin_i18n = new CBXSCRatingReview_i18n();
			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {
			$plugin_admin = new CBXSCRatingReview_Admin( $this->get_plugin_name(), $this->get_version() );

			//admin init functionality
			$this->loader->add_action( 'admin_init', $plugin_admin, 'plugin_fullreset', 0 );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'setting_init' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'review_delete_after_delete_post_init' );

			//$this->loader->add_action( 'admin_notices', $plugin_admin, 'fullreset_message_display' );

			$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_pages' );


			//screen options for admin item listing
			$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'cbxscratingreview_review_listing_per_page', 10, 3 );
			$this->loader->add_filter( 'set-screen-option', $plugin_admin, 'cbxscratingreview_rating_avg_listing_per_page', 10, 3 );


			//setting init and add  setting sub menu in setting menu
			$this->loader->add_action( 'wp_ajax_cbxscratingreview_review_rating_admin_edit', $plugin_admin, 'review_rating_admin_edit' );

			//add all css and js in backend
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			//on review publish
			$this->loader->add_action( 'cbxscratingreview_review_publish', $plugin_admin, 'review_publish_adjust_avg' );
			$this->loader->add_action( 'cbxscratingreview_review_unpublish', $plugin_admin, 'review_unpublish_adjust_avg' );


			//on review delete extra process
			$this->loader->add_action( 'cbxscratingreview_review_delete_after', $plugin_admin, 'review_delete_after' );

			//on user delete
			$this->loader->add_action( 'delete_user', $plugin_admin, 'review_delete_after_delete_user' );

			//upgrade and admin notice
			$this->loader->add_filter( 'plugin_action_links_' . CBXSCRATINGREVIEW_BASE_NAME, $plugin_admin, 'plugin_action_links' );
			$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'plugin_row_meta', 10, 4 );
			$this->loader->add_action( 'upgrader_process_complete', $plugin_admin, 'plugin_upgrader_process_complete', 10, 2 );
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'plugin_activate_upgrade_notices' );

			//update manager
			$this->loader->add_filter('pre_set_site_transient_update_plugins', $plugin_admin, 'pre_set_site_transient_update_plugins_pro_addon');
			$this->loader->add_filter('pre_set_site_transient_update_plugins', $plugin_admin, 'pre_set_site_transient_update_plugins_comment_addon');
			$this->loader->add_filter('pre_set_site_transient_update_plugins', $plugin_admin, 'pre_set_site_transient_update_plugins_mycred_addon');

			$this->loader->add_action( 'in_plugin_update_message-' . 'cbxscratingreviewpro/cbxscratingreviewpro.php', $plugin_admin, 'plugin_update_message_pro_addons' );
			$this->loader->add_action( 'in_plugin_update_message-' . 'cbxscratingreviewcomment/cbxscratingreviewcomment.php', $plugin_admin, 'plugin_update_message_pro_addons' );
			$this->loader->add_action( 'in_plugin_update_message-' . 'cbxscratingreviewmycred/cbxscratingreviewmycred.php', $plugin_admin, 'plugin_update_message_pro_addons' );

			//for listing screens
			$this->loader->add_filter( 'manage_toplevel_page_cbxscratingreviewreviewlist_columns', $plugin_admin, 'log_listing_screen_cols' );
			$this->loader->add_filter( 'manage_5-star-reviews_page_cbxscratingreviewratingavglist_columns', $plugin_admin, 'avglog_listing_screen_cols' );
		}//end define_admin_hooks

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$plugin_public = new CBXSCRatingReview_Public( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'init', $plugin_public, 'init_shortcodes' );

			//review rating entry via ajax
			$this->loader->add_action( 'wp_ajax_cbxscratingreview_review_rating_frontend_submit', $plugin_public, 'review_rating_frontend_submit' );

			//ajax post reviews load more
			$this->loader->add_action( 'wp_ajax_cbxscratingreview_post_more_reviews', $plugin_public, 'post_more_reviews_ajax_load' );
			$this->loader->add_action( 'wp_ajax_nopriv_cbxscratingreview_post_more_reviews', $plugin_public, 'post_more_reviews_ajax_load' );


			//ajax review filter for post reviews
			$this->loader->add_action( 'wp_ajax_cbxscratingreview_post_filter_reviews', $plugin_public, 'post_filter_reviews_ajax_load' );
			$this->loader->add_action( 'wp_ajax_nopriv_cbxscratingreview_post_filter_reviews', $plugin_public, 'post_filter_reviews_ajax_load' );

			//ajax review filter for all post reviews
			$this->loader->add_action( 'wp_ajax_cbxscratingreview_all_filter_reviews', $plugin_public, 'all_filter_reviews_ajax_load' );
			$this->loader->add_action( 'wp_ajax_nopriv_cbxscratingreview_all_filter_reviews', $plugin_public, 'all_filter_reviews_ajax_load' );

			//widget
			$this->loader->add_action( 'widgets_init', $plugin_public, 'init_register_widget' );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

			$is_yootheme = false;
			if ( get_template_directory() !== get_stylesheet_directory() ) {
				//child theme
				$active_theme = wp_get_theme();
				$theme_name   = trim( $active_theme->get( 'Template' ) );
				if ( $theme_name === 'yootheme' ) {
					$is_yootheme = true;
				}
			} else {
				//parent theme
				$active_theme = wp_get_theme();
				$theme_name   = ( $active_theme->get( 'Name' ) );
				if ( $theme_name === 'YOOtheme' ) {
					$is_yootheme = true;
				}
			}

			if ( $is_yootheme ) {
				$this->loader->add_action( 'wp_loaded', $plugin_public, 'enqueue_styles' );
				$this->loader->add_action( 'wp_loaded', $plugin_public, 'enqueue_scripts' );
			}

			$this->loader->add_action( 'wp_ajax_cbxscratingreview_review_rating_front_edit', $plugin_public, 'review_rating_front_edit' );


			//ajax review delete from frontend
			$this->loader->add_action( 'wp_ajax_cbxscratingreview_review_delete', $plugin_public, 'review_delete_ajax' );


			//special care of review edit for adjustment
			$this->loader->add_action( 'cbxscratingreview_review_update_without_status', $plugin_public, 'cbxscratingreview_review_update_without_status_adjust_postavg', 10, 3 );

			$this->loader->add_action( 'cbxscratingreview_review_list_item_after', $plugin_public, 'cbxscratingreview_single_review_toolbar', 8, 1 );
			$this->loader->add_action( 'cbxscratingreview_review_list_item_toolbar_end', $plugin_public, 'cbxscratingreview_single_review_delete_button' );

			//$this->loader->add_filter( 'the_content', $plugin_public, 'the_content_auto_integration' );
			$this->loader->add_filter( 'the_content', $plugin_public, 'the_content_auto_integration' );
			$this->loader->add_filter( 'the_excerpt', $plugin_public, 'the_excerpt_auto_integration' );

			//elementor Widget
			$this->loader->add_action( 'elementor/widgets/widgets_registered', $plugin_public, 'init_elementor_widgets' );
			$this->loader->add_action( 'elementor/elements/categories_registered', $plugin_public, 'add_elementor_widget_categories' );
			$this->loader->add_action( 'elementor/editor/before_enqueue_scripts', $plugin_public, 'elementor_icon_loader', 99999 );

			//visual composer widget
			$this->loader->add_action( 'vc_before_init', $plugin_public, 'vc_before_init_actions', 12 );//priority 12 works for both old and new version of vc
		}//end define_public_hooks

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @return    string    The name of the plugin.
		 * @since     1.0.0
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @return    CBXSCRatingReview_Loader    Orchestrates the hooks of the plugin.
		 * @since     1.0.0
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @return    string    The version number of the plugin.
		 * @since     1.0.0
		 */
		public function get_version() {
			return $this->version;
		}
	}//end class CBXSCRatingReview