<?php

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       http://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    CBXSCRatingReview
	 * @subpackage CBXSCRatingReview/admin
	 */

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    CBXSCRatingReview
	 * @subpackage CBXSCRatingReview/admin
	 * @author     Sabuj Kundu <sabuj@codeboxr.com>
	 */
	class CBXSCRatingReview_Admin {

		/**
		 * The setting of this plugin.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string $version The current version of this plugin.
		 */
		public $setting;
		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;
		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @param string $plugin_name The name of this plugin.
		 * @param string $version     The version of this plugin.
		 *
		 * @since    1.0.0
		 *
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

			if (defined('WP_DEBUG')) {
				$this->version = current_time('timestamp'); //for development time only
			}

			$this->setting = new CBXSCRatingReviewSettings();
		}

		public function setting_init() {
			//set the settings
			$this->setting->set_sections( $this->get_settings_sections());
			$this->setting->set_fields( $this->get_settings_fields());

			//initialize settings
			$this->setting->admin_init();
		}


		/**
		 * Full reset
		 *
		 */
		public function plugin_fullreset() {
			if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'cbxscratingreviewsettings' && isset( $_REQUEST['cbxscratingreview_fullreset'] ) && $_REQUEST['cbxscratingreview_fullreset'] == 1 ) {


				global $wpdb;
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
				do_action( 'cbxscratingreview_plugin_reset' );

				require_once plugin_dir_path( __FILE__ ) . '../includes/class-cbxscratingreview-activator.php';
				//create tables
				CBXSCRatingReview_Activator::createTables();
				CBXSCRatingReview_Activator::createPages();


				$cbxscratingreview_setting = $this->setting;


				$cbxscratingreview_setting->set_sections( $this->get_settings_sections() );
				$cbxscratingreview_setting->set_fields( $this->get_settings_fields() );
				$cbxscratingreview_setting->admin_init();

				//$_SESSION['cbxscratingreview_fullreset_message'] = esc_html__( 'CBX 5 Star Rating & Review plugin data has been reset which all setting, database table, meta keys related with this plugin are deleted, setting and database table recreated. ', 'cbxscratingreview' );

				wp_safe_redirect( admin_url( 'admin.php?page=cbxscratingreviewsettings#cbxscratingreview_tools' ) );
				exit();
			}
		}//end plugin_fullreset

		/**
		 * Global Setting Sections and titles
		 *
		 * @return type
		 */
		public function get_settings_sections() {
			$settings_sections = array(
				array(
					'id'    => 'cbxscratingreview_common_config',
					'title' => esc_html__( 'General', 'cbxscratingreview' )
				),
				array(
					'id'    => 'cbxscratingreview_global_email',
					'title' => esc_html__( 'Email Template', 'cbxscratingreview' )
				),
				array(
					'id'    => 'cbxscratingreview_email_alert',
					'title' => esc_html__( 'Review Alerts', 'cbxscratingreview' )
				),
				array(
					'id'    => 'cbxscratingreview_tools',
					'title' => esc_html__( 'Pages & Tools', 'cbxscratingreview' )
				)
			);

			return apply_filters( 'cbxscratingreview_setting_sections', $settings_sections );
		}

		/**
		 * Global Setting Fields
		 *
		 * @return array
		 */
		public function get_settings_fields() {

			$cbxscratingreview_setting = $this->setting;

			$reviews_status_options  = CBXSCRatingReviewHelper::ReviewStatusOptions();
			$reviews_positive_scores = CBXSCRatingReviewHelper::ReviewPositiveScores();


			$user_roles_no_guest   = CBXSCRatingReviewHelper::user_roles( false, false );
			$user_roles_with_guest = CBXSCRatingReviewHelper::user_roles( false, true );

			$post_types = CBXSCRatingReviewHelper::post_types( false );

			$post_types_auto = CBXSCRatingReviewHelper::post_types_filtered( $cbxscratingreview_setting->get_option( 'post_types', 'cbxscratingreview_common_config', array() ) );


			$reset_data_link = add_query_arg( 'cbxscratingreview_fullreset', 1, admin_url( 'admin.php?page=cbxscratingreviewsettings' ) );

			$table_names = CBXSCRatingReviewHelper::getAllDBTablesList();
			$table_keys  = CBXSCRatingReviewHelper::getAllDBTablesKeyList();

			$table_html = '<p><a class="button button-primary" id="cbxscratingreview_info_trig" href="#">' . esc_html__( 'Show/hide details', 'cbxscratingreview' ) . '</a></p>';
			$table_html .= '<div id="cbxscratingreview_resetinfo" style="display: none;">';

			$table_html .= '<p id="cbxscratingreview_info"><strong>' . esc_html__( 'Following database tables will be reset/deleted and then re-created.', 'cbxscratingreview' ) . '</strong></p>';

			$table_counter = 1;
			foreach ( $table_names as $key => $value ) {
				$key        = isset( $table_keys[ $key ] ) ? esc_html( $table_keys[ $key ] ) : $key;
				$table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $key . ' - (<code>' . $value . '</code>)</p>';
				$table_counter ++;
			}

			$table_html .= '<p><strong>' . esc_html__( 'Following option values created by this plugin(including addon) from wordpress core option table', 'cbxscratingreview' ) . '</strong></p>';


			$option_values = CBXSCRatingReviewHelper::getAllOptionNames();
			$table_counter = 1;
			foreach ( $option_values as $key => $value ) {
				$table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $value['option_name'] . ' - ' . $value['option_id'] . ' - (<code style="overflow-wrap: break-word; word-break: break-all;">' . $value['option_value'] . '</code>)</p>';

				$table_counter ++;
			}

			$table_html .= '<p><strong>' . esc_html__( 'Following meta key created by this plugin(including addon) from wordpress core post meta table', 'cbxscratingreview' ) . '</strong></p>';
			$meta_keys  = CBXSCRatingReviewHelper::getMetaKeys();

			$table_counter = 1;
			foreach ( $meta_keys as $key => $value ) {
				$table_html .= '<p>' . str_pad( $table_counter, 2, '0', STR_PAD_LEFT ) . '. ' . $key . ' - (<code style="overflow-wrap: break-word; word-break: break-all;">' . $value . '</code>)</p>';
				$table_counter ++;
			}

			$table_html .= '</div>';


			$gust_login_forms = CBXSCRatingReviewHelper::guest_login_forms();
			$layout_options          = CBXSCRatingReviewHelper::get_layouts();

			$cbxscratingreview_common_config_fields = array(
				'common_config_heading' => array(
					'name'    => 'common_config_heading',
					'label'   => esc_html__( 'General Settings', 'cbxscratingreview' ),
					'type'    => 'heading',
					'default' => '',
				),
				'post_types'              => array(
					'name'        => 'post_types',
					'label'       => esc_html__( 'Post Type Support', 'cbxscratingreview' ),
					'desc'        => esc_html__( 'Which post types can have the rating & review features', 'cbxscratingreview' ),
					'type'        => 'multiselect',
					'default'     => array( 'post' ),
					'options'     => $post_types,
					'optgroup'    => 1,
					'placeholder' => esc_html__( 'Select Post Type', 'cbxscratingreview' )
				),
				'user_roles_rate'         => array(
					'name'        => 'user_roles_rate',
					'label'       => esc_html__( 'Who Can give Rate & Review', 'cbxscratingreview' ),
					'desc'        => esc_html__( 'Which user role will have vote capability', 'cbxscratingreview' ),
					'type'        => 'multiselect',
					'default'     => array(
						'administrator',
						'editor',
						'author',
						'contributor',
						'subscriber'
					),
					'options'     => $user_roles_no_guest,
					'optgroup'    => 1,
					'placeholder' => esc_html__( 'Select user roles', 'cbxscratingreview' )
				),
				'user_roles_view'         => array(
					'name'        => 'user_roles_view',
					'label'       => esc_html__( 'Who Can View Rating & Review', 'cbxscratingreview' ),
					'desc'        => esc_html__( 'Which user role will have view capability', 'cbxscratingreview' ),
					'type'        => 'multiselect',
					'default'     => array(
						'administrator',
						'editor',
						'author',
						'contributor',
						'subscriber',
						'guest'
					),
					'options'     => $user_roles_with_guest,
					'optgroup'    => 1,
					'placeholder' => esc_html__( 'Select user roles', 'cbxscratingreview' )
				),
				'allow_review_delete'     => array(
					'name'    => 'allow_review_delete',
					'label'   => esc_html__( 'Allow Review Delete', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Allow user delete review from frontend', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' ),
					)
				),
				'half_rating'             => array(
					'name'    => 'half_rating',
					'label'   => esc_html__( 'Allow Half Rating', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'If half rating enabled, user can rate .5, 1.5, 2.5, 3.5, 4.5 with regular 1, 2,3,4,5 values.', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => 0,
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' ),
					)
				),
				'default_status'          => array(
					'name'    => 'default_status',
					'label'   => esc_html__( 'Default Review Status', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'What will be status when a new review is written?', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => 1,
					'options' => $reviews_status_options
				),
				'enable_auto_integration' => array(
					'name'    => 'enable_auto_integration',
					'label'   => esc_html__( 'Enable Auto Integration', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Enable/disable auto integration, ie, add average rating before post content in archive, in details article mode add average rating information before content, rating form & review listing after content', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => 1,
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' ),
					)
				),
				'post_types_auto'         => array(
					'name'        => 'post_types_auto',
					'label'       => esc_html__( 'Auto Integration for Post Type', 'cbxscratingreview' ),
					'desc'        => __( 'Enable which post types will have auto integration features. Please note that selected post types should be within the post types selected for <strong>Post Type Support</strong>', 'cbxscratingreview' ),
					'type'        => 'multiselect',
					'default'     => array(),
					'options'     => $post_types_auto,
					'placeholder' => esc_html__( 'Select Post Type', 'cbxscratingreview' )
				),
				'show_on_single'          => array(
					'name'       => 'show_on_single',
					'label'      => esc_html__( 'Show on Single(Auto Integration)', 'cbxscratingreview' ),
					'desc'       => esc_html__( 'Enable disable for single article(post, page or any custom post type), related with auto integration.', 'cbxscratingreview' ),
					'type'       => 'select',
					'default'    => 1,
					'required'   => false,
					'options'    => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' )
					),
					'extrafield' => true,
				),
				'show_on_home'            => array(
					'name'     => 'show_on_home',
					'label'    => esc_html__( 'Show on Home/Frontpage(Auto Integration)', 'cbxscratingreview' ),
					'desc'     => esc_html__( 'Enable disable for home/frontpage, related with auto integration.', 'cbxscratingreview' ),
					'type'     => 'select',
					'default'  => 1,
					'required' => false,
					'options'  => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' )
					),
				),
				'show_on_arcv'            => array(
					'name'     => 'show_on_arcv',
					'label'    => esc_html__( 'Show on Archives(Auto Integration)', 'cbxscratingreview' ),
					'desc'     => esc_html__( 'Enable disable for archive pages, related with auto integration.', 'cbxscratingreview' ),
					'type'     => 'select',
					'default'  => 1,
					'required' => false,
					'options'  => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' )
					)
				),
				'show_headline'           => array(
					'name'    => 'show_headline',
					'label'   => esc_html__( 'Show Headline', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Show/hide review headline in rating form', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' ),
					)
				),
				'require_headline'        => array(
					'name'    => 'require_headline',
					'label'   => esc_html__( 'Headline Required', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Is headline mandatory to write a review?', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' ),
					)
				),
				'show_comment'            => array(
					'name'    => 'show_comment',
					'label'   => esc_html__( 'Show Comment', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Show/hide comment in rating form', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' ),
					)
				),
				'require_comment'         => array(
					'name'    => 'require_comment',
					'label'   => esc_html__( 'Comment Required', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Is comment mandatory to write a review?', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' ),
					)
				),
				'enable_positive_critical' => array(
					'name'    => 'enable_positive_critical',
					'label'   => esc_html__( 'Enable Positive/Critical Score', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Enable positivive or critial score functionality', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' ),
					)
				),
				'positive_score'           => array(
					'name'    => 'positive_score',
					'label'   => esc_html__( 'Positve Review Score value', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Select minimum score value for a positive review', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => 4,
					'options' => $reviews_positive_scores
				),
				'default_per_page'         => array(
					'name'    => 'default_per_page',
					'label'   => esc_html__( 'Reviews Per Page', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Default number of reviews per page in pagination', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => '10'
				),
				'show_review_filter'       => array(
					'name'    => 'show_review_filter',
					'label'   => esc_html__( 'Show Review Filter', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Show filter box in review listing', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => '1',
					'options' => array(
						'1' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'0' => esc_html__( 'No', 'cbxscratingreview' ),
					)
				),
				'guest_login_form'       => array(
					'name'    => 'guest_login_form',
					'label'   => esc_html__( 'Guest User Login Form', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Default guest user is shown wordpress core login form. Pro addon helps to integrate 3rd party plugins like woocommerce, restrict content pro etc.', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => 'wordpress',
					'options' => $gust_login_forms
				),
				'guest_show_register'       => array(
					'name'    => 'guest_show_register',
					'label'   => esc_html__( 'Show Register link to guest', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Show register link to guest, depends on if registration is enabled in wordpress core', 'cbxscratingreview' ),
					'type'    => 'radio',
					'default' => 1,
					'options'  => array(
						1 => esc_html__( 'Yes', 'cbxscratingreview' ),
						0  => esc_html__( 'No', 'cbxscratingreview' ),
					),
				),
				'layout' => array(
					'name'    => 'layout',
					'label'   => esc_html__( 'Choose layout', 'cbxscratingreview' ),
					'desc'    => esc_html__( '', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => 'layout_default',
					'options' => $layout_options
				),

			);

			$cbxscratingreview_global_email_fields = array(
				'global_email_heading' => array(
					'name'    => 'global_email_heading',
					'label'   => esc_html__( 'Default Email Template', 'cbxscratingreview' ),
					'type'    => 'heading',
					'default' => '',
				),
				'headerimage'         => array(
					'name'    => 'headerimage',
					'label'   => esc_html__( 'Header Image', 'cbxscratingreview' ),
					//'desc'    => esc_html__( 'Url To email you want to show as email header.Upload Image by media uploader.', 'cbxscratingreview' ),
					'type'    => 'file',
					'default' => ''
				),
				'footertext'          => array(
					'name'    => 'footertext',
					'label'   => esc_html__( 'Footer Text', 'cbxscratingreview' ),
					'desc'    => __( 'The text to appear at the email footer. Syntax available - <code>{sitename}</code>', 'cbxscratingreview' ),
					'type'    => 'wysiwyg',
					'default' => '{sitename}'
				),
				'basecolor'           => array(
					'name'    => 'basecolor',
					'label'   => esc_html__( 'Base Color', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'The base color of the email.', 'cbxscratingreview' ),
					'type'    => 'color',
					'default' => '#557da1'
				),
				'backgroundcolor'     => array(
					'name'    => 'backgroundcolor',
					'label'   => esc_html__( 'Background Color', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'The background color of the email.', 'cbxscratingreview' ),
					'type'    => 'color',
					'default' => '#f5f5f5'
				),
				'bodybackgroundcolor' => array(
					'name'    => 'bodybackgroundcolor',
					'label'   => esc_html__( 'Body Background Color', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'The background colour of the main body of email.', 'cbxscratingreview' ),
					'type'    => 'color',
					'default' => '#fdfdfd'
				),
				'bodytextcolor'       => array(
					'name'    => 'bodytextcolor',
					'label'   => esc_html__( 'Body Text Color', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'The body text colour of the main body of email.', 'cbxscratingreview' ),
					'type'    => 'color',
					'default' => '#505050'
				),
			);

			$cbxscratingreview_email_alert_fields = array(
				'nr_admin_status_heading' => array(
					'name'    => 'nr_admin_status_heading',
					'label'   => esc_html__( 'New Review Admin Email Alert', 'cbxscratingreview-comment' ),
					'desc'    => esc_html__( 'New review admin email alert configuration', 'cbxscratingreview-comment' ),
					'type'    => 'heading',
					'default' => ''
				),
				'nr_admin_status'         => array(
					'name'    => 'nr_admin_status',
					'label'   => esc_html__( 'On/Off', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Alert Status', 'cbxscratingreview' ),
					'type'    => 'checkbox',
					'default' => 'on'
				),
				'nr_admin_format'         => array(
					'name'    => 'nr_admin_format',
					'label'   => esc_html__( 'E-mail Format', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Select the format of the E-mail.', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => 'html',
					'options' => array(
						'html'      => esc_html__( 'HTML', 'cbxscratingreview' ),
						'plain'     => esc_html__( 'Plain', 'cbxscratingreview' ),
						'multipart' => esc_html__( 'Multipart/mixed(attachment)', 'cbxscratingreview' ),
					)
				),
				'nr_admin_name'           => array(
					'name'    => 'nr_admin_name',
					'label'   => esc_html__( 'From Name', 'cbxscratingreview' ),
					'desc'    => __( 'Name of sender. Syntax available - <code>{sitename}</code>', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => '{sitename}'
				),
				'nr_admin_from'           => array(
					'name'    => 'nr_admin_from',
					'label'   => esc_html__( 'From Email', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'From Email Address.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )
				),
				'nr_admin_to'             => array(
					'name'    => 'nr_admin_to',
					'label'   => esc_html__( 'To Email', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'To Email Address.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )

				),
				'nr_admin_reply_to'       => array(
					'name'    => 'nr_admin_reply_to',
					'label'   => esc_html__( 'Reply To', 'cbxscratingreview' ),
					'desc'    => __( 'Reply To Email Address. Syntax available - <code>{user_email}</code>', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => '{user_email}'
				),
				'nr_admin_subject'        => array(
					'name'    => 'nr_admin_subject',
					'label'   => esc_html__( 'Subject', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Email Subject.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => esc_html__( 'New Review Notification', 'cbxscratingreview' )
				),
				'nr_admin_heading'        => array(
					'name'    => 'nr_admin_heading',
					'label'   => esc_html__( 'Heading', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Email Template heading.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => esc_html__( 'New Review Received', 'cbxscratingreview' )
				),
				'nr_admin_body'           => array(
					'name'    => 'nr_admin_body',
					'label'   => esc_html__( 'Body', 'cbxscratingreview' ),
					'desc'    => __( 'Email Body.  Syntax available - <code>{score}, {headline}, {comment}, {status}, {post_url}, {review_edit_url}</code>', 'cbxscratingreview' ),
					'type'    => 'wysiwyg',
					'default' => 'Hi, Admin
                            
A new review is made. Here is the details:

Rating: {score}
Title: {headline}
Review: {comment}
Review status: {status}

Post: {post_url}
Review: {review_edit_url}

Please check & do necessary steps and give feedback to client.
Thank you.'
				),
				'nr_admin_cc'             => array(
					'name'    => 'nr_admin_cc',
					'label'   => esc_html__( 'CC', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Email CC, for multiple use comma.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => ''
				),
				'nr_admin_bcc'            => array(
					'name'    => 'nr_admin_bcc',
					'label'   => esc_html__( 'BCC', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Email BCC, for multiple use comma', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => ''
				),
				'nr_user_status_heading'  => array(
					'name'    => 'nr_user_status_heading',
					'label'   => esc_html__( 'New Review User Email Alert', 'cbxscratingreview-comment' ),
					'desc'    => esc_html__( 'New review user email alert configuration', 'cbxscratingreview-comment' ),
					'type'    => 'heading',
					'default' => ''
				),
				'nr_user_status'          => array(
					'name'    => 'nr_user_status',
					'label'   => esc_html__( 'On/Off', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Status of Email', 'cbxscratingreview' ),
					'type'    => 'checkbox',
					'default' => 'on'
				),
				'nr_user_format'          => array(
					'name'    => 'nr_user_format',
					'label'   => esc_html__( 'E-mail Format', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Select the format of the E-mail.', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => 'html',
					'options' => array(
						'html'      => esc_html__( 'HTML', 'cbxscratingreview' ),
						'plain'     => esc_html__( 'Plain', 'cbxscratingreview' ),
						'multipart' => esc_html__( 'Multipart/mixed(attachment)', 'cbxscratingreview' ),
					)
				),
				'nr_user_name'            => array(
					'name'    => 'nr_user_name',
					'label'   => esc_html__( 'From Name', 'cbxscratingreview' ),
					'desc'    => __( 'Name of sender.  Syntax available - <code>{sitename}</code>', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => '{sitename}'
				),
				'nr_user_from'            => array(
					'name'    => 'nr_user_from',
					'label'   => esc_html__( 'From Email', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'From Email Address.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )
				),
				'nr_user_to'              => array(
					'name'    => 'nr_user_to',
					'label'   => esc_html__( 'To Email', 'cbxscratingreview' ),
					'desc'    => __( 'To Email Address. Syntax available - <code>{user_email}</code>', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => '{user_email}'
				),
				'nr_user_reply_to'        => array(
					'name'    => 'nr_user_reply_to',
					'label'   => esc_html__( 'Reply To', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Reply To Email Address.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )
				),
				'nr_user_subject'         => array(
					'name'     => 'nr_user_subject',
					'label'    => esc_html__( 'New Review Email Subject', 'cbxscratingreview' ),
					'desc'     => esc_html__( 'Email subject user will receive when they make an initial review.', 'cbxscratingreview' ),
					'type'     => 'text',
					'default'  => esc_html__( 'New Review Notification', 'cbxscratingreview' ),
					'desc_tip' => true
				),
				'nr_user_heading'         => array(
					'name'    => 'nr_user_heading',
					'label'   => esc_html__( 'New Review Email Heading', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Email heading user will receive when they make an initial review.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => esc_html__( 'New Review Received', 'cbxscratingreview' )
				),
				'nr_user_body'            => array(
					'name'    => 'nr_user_body',
					'label'   => esc_html__( 'New Review Email Body', 'cbxscratingreview' ),
					'desc'    => sprintf( __( 'Email content user will receive when they make an initial review. Syntax available - <code>{user_name}, {user_email}, {score}, {headline}, {comment}, {status}, {post_url}', 'cbxscratingreview' ), 1 ),
					'type'    => 'wysiwyg',
					'default' => 'Hi, {user_name}
                            
We got a review for email address {user_email}.

Review Details: 

Rating: {score}
Title: {headline}
Review: {comment}
Review status: {status}

Post: {post_url}

We will check and get back to you soon.
Thank you.'

				),
				'rsc_user_status_heading' => array(
					'name'    => 'rsc_user_status_heading',
					'label'   => esc_html__( 'Review Status Change User Alert', 'cbxscratingreview-comment' ),
					'desc'    => esc_html__( 'User gets email for review status modification', 'cbxscratingreview-comment' ),
					'type'    => 'heading',
					'default' => ''
				),

				'rsc_user_status'   => array(
					'name'    => 'rsc_user_status',
					'label'   => esc_html__( 'On/Off', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Status of Email', 'cbxscratingreview' ),
					'type'    => 'checkbox',
					'default' => 'on'
				),
				'rsc_user_format'   => array(
					'name'    => 'rsc_user_format',
					'label'   => esc_html__( 'E-mail Format', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Select the format of the E-mail.', 'cbxscratingreview' ),
					'type'    => 'select',
					'default' => 'html',
					'options' => array(
						'html'      => esc_html__( 'HTML', 'cbxscratingreview' ),
						'plain'     => esc_html__( 'Plain', 'cbxscratingreview' ),
						'multipart' => esc_html__( 'Multipart/mixed(attachment)', 'cbxscratingreview' ),
					)
				),
				'rsc_user_name'     => array(
					'name'    => 'rsc_user_name',
					'label'   => esc_html__( 'From Name', 'cbxscratingreview' ),
					'desc'    => __( 'Name of sender.  Syntax available - <code>{sitename}</code>', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => '{sitename}'
				),
				'rsc_user_from'     => array(
					'name'    => 'rsc_user_from',
					'label'   => esc_html__( 'From Email', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'From Email Address.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )
				),
				'rsc_user_to'       => array(
					'name'    => 'rsc_user_to',
					'label'   => esc_html__( 'To Email', 'cbxscratingreview' ),
					'desc'    => __( 'To Email Address. Syntax available - <code>{user_email}</code>', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => '{user_email}'
				),
				'rsc_user_reply_to' => array(
					'name'    => 'rsc_user_reply_to',
					'label'   => esc_html__( 'Reply To', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Reply To Email Address.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => get_bloginfo( 'admin_email' )
				),
				'rsc_user_subject'  => array(
					'name'    => 'rsc_user_subject',
					'label'   => esc_html__( 'Review Status Modification Email Subject', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Email subject user will receive when admin modify review status.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => esc_html__( 'Review Status Change Notification', 'cbxscratingreview' )
				),
				'rsc_user_heading'  => array(
					'name'    => 'rsc_user_heading',
					'label'   => esc_html__( 'Review Status Modification Email Heading', 'cbxscratingreview' ),
					'desc'    => esc_html__( 'Email heading user will receive when admin modify review status.', 'cbxscratingreview' ),
					'type'    => 'text',
					'default' => esc_html__( 'Review Status Changed', 'cbxscratingreview' )
				),
				'rsc_user_body'     => array(
					'name'    => 'rsc_user_body',
					'label'   => esc_html__( 'Review Status Modification Email Body', 'cbxscratingreview' ),
					'desc'    => __( 'Email content user will receive when admin modified review status. Syntax available - <code>{user_name}, {score}, {headline}, {comment}, {status}, {post_url}</code>', 'cbxscratingreview' ),
					'type'    => 'wysiwyg',
					'default' => 'Hi, {user_name}

Your review status has changed to {status}.

Review Details: 

Rating: {score}
Title: {headline}
Review: {comment}
Review status: {status}

Post: {post_url}

Thank you.'
				),
			);

			$single_review_view_id   = intval( $cbxscratingreview_setting->get_option( 'single_review_view_id', 'cbxscratingreview_tools', 0 ) );
			$single_review_edit_id   = intval( $cbxscratingreview_setting->get_option( 'single_review_edit_id', 'cbxscratingreview_tools', 0 ) );
			$review_userdashboard_id = intval( $cbxscratingreview_setting->get_option( 'review_userdashboard_id', 'cbxscratingreview_tools', 0 ) );

			$single_review_view_shortcode_text = __( '<strong>Please note, selected page doesn\'t have the shortcode. Please edit the page and add the shortcode.</strong>', 'cbxscratingreview' );
			$single_review_edit_shortcode_text = __( '<strong>Please note, selected page doesn\'t have the shortcode. Please edit the page and add the shortcode.</strong>', 'cbxscratingreview' );
			$user_dashboard_shortcode_text     = __( '<strong>Please note, selected page doesn\'t have the shortcode. Please edit the page and add the shortcode.</strong>', 'cbxscratingreview' );

			if ( $single_review_view_id > 0 ) {
				$content_post = get_post( $single_review_view_id );
				if ( $content_post !== null ) {
					$content = $content_post->post_content;
					if ( has_shortcode( $content, 'cbxscratingreview_singlereview' ) ) {

						$single_review_view_shortcode_text = sprintf( __( '<strong>Shortcode detected on the selected page.</strong>. <a class="button button-secondary" target="_blank" href="%s">Browse</a> the page on frontend.', 'cbxscratingreview' ), get_permalink( $single_review_view_id ) );
					}
				}

			}

			if ( $single_review_edit_id > 0 ) {
				$content_post = get_post( $single_review_edit_id );
				if ( $content_post !== null ) {
					$content = $content_post->post_content;
					if ( has_shortcode( $content, 'cbxscratingreview_editreview' ) ) {

						$single_review_edit_shortcode_text = sprintf( __( '<strong>Shortcode detected on the selected page.</strong>. <a class="button button-secondary" target="_blank" href="%s">Browse</a> the page on frontend.', 'cbxscratingreview' ), get_permalink( $single_review_edit_id ) );
					}
				}
			}

			if ( $review_userdashboard_id > 0 ) {
				$content_post = get_post( $review_userdashboard_id );
				if ( $content_post !== null ) {
					$content = $content_post->post_content;

					if ( has_shortcode( $content, 'cbxscratingreview_userdashboard' ) ) {
						$user_dashboard_shortcode_text = sprintf( __( '<strong>Shortcode detected on the selected page.</strong>. <a class="button button-secondary" target="_blank" href="%s">Browse</a> the page on frontend.', 'cbxscratingreview' ), get_permalink( $review_userdashboard_id ) );
					}
				}

			}


			$cbxscratingreview_tools_fields = array(
				'tools_heading' => array(
					'name'    => 'tools_heading',
					'label'   => esc_html__( 'Tools Settings', 'cbxscratingreview' ),
					'type'    => 'heading',
					'default' => '',
				),
				'review_userdashboard_id' => array(
					'name'    => 'review_userdashboard_id',
					'label'   => esc_html__( 'Frontend User Dashboard', 'cbxscratingreview' ),
					'desc'    => __( 'Select page which will show the the logged in user\'s dashboard to manage rating and reviews. That page must have the shortcode <code>[cbxscratingreview_userdashboard]</code>.', 'cbxscratingreview' ) . $user_dashboard_shortcode_text,
					'type'    => 'select',
					'default' => '',
					'options' => CBXSCRatingReviewHelper::get_pages()
				),
				'single_review_edit_id'   => array(
					'name'    => 'single_review_edit_id',
					'label'   => esc_html__( 'Frontend Single Review Edit Page', 'cbxscratingreview' ),
					'desc'    => __( 'Select page which will show the single review edit dynamically. That page must have the shortcode <code>[cbxscratingreview_editreview]</code>.', 'cbxscratingreview' ) . $single_review_edit_shortcode_text,
					'type'    => 'select',
					'default' => '',
					'options' => CBXSCRatingReviewHelper::get_pages()
				),
				'single_review_view_id'   => array(
					'name'    => 'single_review_view_id',
					'label'   => esc_html__( 'Frontend Single Review View Page', 'cbxscratingreview' ),
					'desc'    => __( 'Select page which will show the single review dynamically. That page must have the shortcode <code>[cbxscratingreview_singlereview]</code>.', 'cbxscratingreview' ) . $single_review_view_shortcode_text,
					'type'    => 'select',
					'default' => '',
					'options' => CBXSCRatingReviewHelper::get_pages()
				),
				'delete_global_config'    => array(
					'name'    => 'delete_global_config',
					'label'   => esc_html__( 'On Uninstall delete plugin data', 'cbxscratingreview' ),
					'desc'    => '<p>' . __( 'Delete Global Config data and custom table created by this plugin on uninstall.', 'cbxscratingreview' ) . ' ' . __( 'Details table information is <a href="#cbxscratingreview_info">here</a>', 'cbxscratingreview' ) . '</p>' . '<p>' . __( '<strong>Please note that this process can not be undone and it is recommended to keep full database backup before doing this.</strong>', 'cbxscratingreview' ) . '</p>',
					'type'    => 'select',
					'options' => array(
						'yes' => esc_html__( 'Yes', 'cbxscratingreview' ),
						'no'  => esc_html__( 'No', 'cbxscratingreview' ),
					),
					'default' => 'no'
				),
				'reset_data'              => array(
					'name'    => 'reset_data',
					'label'   => esc_html__( 'Reset all data', 'cbxscratingreview' ),
					'desc'    => sprintf( __( 'Reset option values and all tables created by this plugin. 
<a class="button button-primary" onclick="return confirm(\'%s\')" href="%s">Reset Data</a>', 'cbxscratingreview' ), esc_html__( 'Are you sure to reset all data, this process can not be undone?', 'cbxscratingreview' ), $reset_data_link ) . $table_html,
					'type'    => 'html',
					'default' => 'off'
				)
			);

			$settings_builtin_fields =
				apply_filters( 'cbxscratingreview_setting_fields', array(
					'cbxscratingreview_common_config' => apply_filters( 'cbxscratingreview_common_config_fields', $cbxscratingreview_common_config_fields ),
					'cbxscratingreview_global_email'  => apply_filters( 'cbxscratingreview_global_email_fields', $cbxscratingreview_global_email_fields ),
					'cbxscratingreview_email_alert'   => apply_filters( 'cbxscratingreview_email_alert_fields', $cbxscratingreview_email_alert_fields ),
					'cbxscratingreview_tools'         => apply_filters( 'cbxscratingreview_tools_fields', $cbxscratingreview_tools_fields )
				) );


			$settings_fields = array(); //final setting array that will be passed to different filters

			$sections = $this->get_settings_sections();

			foreach ( $sections as $section ) {
				if ( ! isset( $settings_builtin_fields[ $section['id'] ] ) ) {
					$settings_builtin_fields[ $section['id'] ] = array();
				}
			}


			foreach ( $sections as $section ) {
				$settings_fields[ $section['id'] ] = $settings_builtin_fields[ $section['id'] ];
			}


			$settings_fields = apply_filters( 'cbxscratingreview_setting_fields_final', $settings_fields ); //final filter if need

			return $settings_fields;
		}//end get_settings_fields

		/**
		 * Display migration messages
		 */
		public function fullreset_message_display() {

			if ( isset( $_SESSION['cbxscratingreview_fullreset_message'] ) ) {
				$message = $_SESSION['cbxscratingreview_fullreset_message'];
				unset( $_SESSION['cbxscratingreview_fullreset_message'] );

				if ( $message != '' ):
					?>
					<div class="notice notice-success is-dismissible">
						<p><?php echo $message; ?></p>
					</div>
				<?php
				endif;

			}

		}//end fullreset_message_display

		/**
		 * Show Admin Pages
		 */
		public function admin_pages() {


			$cbxscratingreview_setting = $this->setting;


			//review listing page
			$review_listing_page_hook = add_menu_page( esc_html__( 'CBX 5 Star Rating & Review', 'cbxscratingreview' ), esc_html__( '5 Star Reviews', 'cbxscratingreview' ), 'manage_options', 'cbxscratingreviewreviewlist',
				array( $this, 'display_admin_review_listing_page' ), CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/icon_w_24.png', '6' );


			//add screen option save option
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'cbxscratingreviewreviewlist' && ! isset( $_GET['view'] ) ) {
				add_action( "load-$review_listing_page_hook", array( $this, 'cbxscratingreview_review_listing' ) );
			}

			if ( ! session_id() ) {
				session_start();
			}

			//rating avg listing pageadmin_pages
			$rating_avg_listing_page_hook = add_submenu_page(
				'cbxscratingreviewreviewlist', esc_html__( 'Rating Average Listing', 'cbxscratingreview' ), esc_html__( 'Rating Average', 'cbxscratingreview' ),
				'manage_options', 'cbxscratingreviewratingavglist', array(
					$this,
					'display_admin_rating_avg_listing_page'
				)
			);
			//add screen option save option
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'cbxscratingreviewratingavglist' ) {
				add_action( "load-$rating_avg_listing_page_hook", array(
					$this,
					'cbxscratingreview_rating_avg_listing'
				) );
			}


			//add settings for this plugin
			$setting_page_hook = add_submenu_page(
				'cbxscratingreviewreviewlist', esc_html__( 'Global Setting', 'cbxscratingreview' ), esc_html__( 'Global Setting', 'cbxscratingreview' ),
				'manage_options', 'cbxscratingreviewsettings', array( $this, 'display_plugin_admin_settings' )
			);

			//add settings for this plugin
			$help_support_hook = add_submenu_page(
				'cbxscratingreviewreviewlist', esc_html__( 'Helps & Updates', 'cbxscratingreview' ), esc_html__( 'Helps & Updates', 'cbxscratingreview' ),
				'manage_options', 'cbxscratingreview-help-support', array( $this, 'display_plugin_help_support' )
			);


			global $submenu;
			if ( isset( $submenu['cbxscratingreviewreviewlist'][0][0] ) ) {
				$submenu['cbxscratingreviewreviewlist'][0][0] = esc_html__( 'User Reviews', 'cbxscratingreview' );
			}

		}//end admin_pages

		/**
		 * Display plugin setting page
		 */
		public function display_plugin_admin_settings() {
			echo cbxscratingreview_get_template_html( 'admin/settings-display.php', array(
				'ref' => $this
			) );
		}//end display_plugin_admin_settings

		/**
		 * Display plugin Helps & Updates
		 */
		public function display_plugin_help_support() {
			echo cbxscratingreview_get_template_html( 'admin/dashboard.php');
		}//end display_plugin_help_support

		/**
		 * Set options for review listing result
		 *
		 * @param $new_status
		 * @param $option
		 * @param $value
		 *
		 * @return mixed
		 */
		public function cbxscratingreview_review_listing_per_page( $new_status, $option, $value ) {
			if ( 'cbxscratingreview_review_listing_per_page' == $option ) {
				return $value;
			}

			return $new_status;
		}//end cbxscratingreview_review_listing_per_page

		/**
		 * Add screen option for review listing
		 */
		public function cbxscratingreview_review_listing() {

			$option = 'per_page';
			$args   = array(
				'label'   => esc_html__( 'Number of items per page', 'cbxscratingreview' ),
				'default' => 50,
				'option'  => 'cbxscratingreview_review_listing_per_page'
			);

			add_screen_option( $option, $args );
		}//end cbxscratingreview_review_listing

		/**
		 * Admin review listing view
		 */
		public function display_admin_review_listing_page() {
			if ( isset( $_GET['view'] ) && $_GET['view'] == 'addedit' ) {
				echo cbxscratingreview_get_template_html( 'admin/admin-rating-review-review-log-edit.php', array() );
			} elseif ( isset( $_GET['view'] ) && $_GET['view'] == 'view' ) {
				echo cbxscratingreview_get_template_html( 'admin/admin-rating-review-review-log-view.php', array() );
			} else {
				echo cbxscratingreview_get_template_html( 'admin/admin-rating-review-review-logs.php', array() );
			}
		}//end display_admin_review_listing_page

		/**
		 * Set options for review listing result
		 *
		 * @param $new_status
		 * @param $option
		 * @param $value
		 *
		 * @return mixed
		 */
		public function cbxscratingreview_rating_avg_listing_per_page( $new_status, $option, $value ) {
			if ( 'cbxscratingreview_rating_avg_listing_per_page' == $option ) {
				return $value;
			}

			return $new_status;
		}

		/**
		 * Add screen option for rating avg listing
		 */
		public function cbxscratingreview_rating_avg_listing() {

			$option = 'per_page';
			$args   = array(
				'label'   => esc_html__( 'Number of items per page', 'cbxscratingreview' ),
				'default' => 50,
				'option'  => 'cbxscratingreview_rating_avg_listing_per_page'
			);
			add_screen_option( $option, $args );

		}

		/**
		 * Admin review listing view
		 */
		public function display_admin_rating_avg_listing_page() {
			echo cbxscratingreview_get_template_html( 'admin/admin-rating-review-rating-avg-logs.php', array() );
		}//end display_admin_rating_avg_listing_page

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles( $hook ) {

			$current_page = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : '';

			$cbxscratingreview_setting = $this->setting;
			$ratingform_css_dep        = array();


			do_action( 'cbxscratingreview_reg_admin_styles_before' );

			wp_register_style( 'cbxscratingreview-branding', plugin_dir_url( __FILE__ ) . '../assets/css/cbxscratingreview-branding.css', array(), $this->version, 'all' );

			wp_register_style( 'jquery-cbxscratingreview-raty', plugin_dir_url( __FILE__ ) . '../assets/css/jquery.cbxscratingreview_raty.css', array(), $this->version, 'all' );

			wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . '../assets/vendors/jquery-ui/ui-lightness/jquery-ui.min.css', array(), $this->version );


			wp_register_style( 'sweetalert2', plugin_dir_url( __FILE__ ) . '../assets/vendors/sweetalert2/sweetalert2.css', array(), $this->version, 'all' );
			wp_register_style( 'select2', plugin_dir_url( __FILE__ ) . '../assets/vendors/select2/css/select2.min.css', array(), $this->version, 'all' );


			$ratingform_css_dep[] = 'jquery-ui';
			$ratingform_css_dep[] = 'jquery-cbxscratingreview-raty';


			wp_register_style( 'cbxscratingreview-ratingform', plugin_dir_url( __FILE__ ) . '../assets/css/cbxscratingreview-ratingform.css', $ratingform_css_dep, $this->version, 'all' );

			wp_register_style( 'cbxscratingreview-admin', plugin_dir_url( __FILE__ ) . '../assets/css/cbxscratingreview-admin.css', array(
				'jquery-cbxscratingreview-raty',
				'jquery-ui'
			), $this->version, 'all' );

			$ratingform_css_dep[] = 'cbxscratingreview-admin';

			wp_register_style( 'cbxscratingreview-setting', plugin_dir_url( __FILE__ ) . '../assets/css/cbxscratingreview-setting.css', array( 'select2' ), $this->version, 'all' );

			do_action( 'cbxscratingreview_reg_admin_styles' );

			//except setting, other main plugin's views

			if ( $current_page == 'cbxscratingreviewreviewlist' || $current_page == 'cbxscratingreviewratingavglist' ) {
				// enqueue styles
				wp_enqueue_style( 'jquery-cbxscratingreview-raty' );
				wp_enqueue_style( 'jquery-ui' );
				wp_enqueue_style( 'sweetalert2' );
				wp_enqueue_style( 'cbxscratingreview-admin' );

				if ( $current_page == 'cbxscratingreviewreviewlist' && ( isset( $_GET['view'] ) && $_GET['view'] == 'addedit' ) ) {
					wp_enqueue_style( 'cbxscratingreview-admin' );
					wp_enqueue_style( 'jquery-cbxscratingreview-raty' );
					wp_enqueue_style( 'cbxscratingreview-ratingform' );
				}
			}

			//only for setting
			if ( $current_page == 'cbxscratingreviewsettings' ) {
				wp_enqueue_style( 'select2' );
				wp_enqueue_style( 'cbxscratingreview-setting' );
			}


			$admin_slugs = CBXSCRatingReviewHelper::admin_page_slugs();
			if ( in_array( $current_page, $admin_slugs ) ) {
				wp_enqueue_style( 'cbxscratingreview-branding' );
			}

			do_action( 'cbxscratingreview_reg_admin_styles' );

		}//end enqueue_styles

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts( $hook ) {

			$current_page = isset( $_GET['page'] ) ? esc_attr( wp_unslash( $_GET['page'] ) ) : '';

			$cbxscratingreview_setting = $this->setting;

			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';


			$require_headline = intval( $cbxscratingreview_setting->get_option( 'require_headline', 'cbxscratingreview_common_config', 1 ) );
			$require_comment  = intval( $cbxscratingreview_setting->get_option( 'require_comment', 'cbxscratingreview_common_config', 1 ) );


			$ratingform_js_dep = array();

			do_action( 'cbxscratingreview_reg_admin_scripts_before' );

			wp_register_script( 'cbxscratingreview-events', plugin_dir_url( __FILE__ ) . '../assets/js/cbxscratingreview-events.js', array(), $this->version, true );

			wp_enqueue_script( 'jquery' );

			$ratingform_js_vars = apply_filters( 'cbxscratingreview_ratingform_adminedit_js_vars', array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'cbxscratingreview' ),
				'rating'     => array(
					'cancelHint' => esc_html__( 'Cancel this rating!', 'cbxscratingreview' ),
					'hints'      => CBXSCRatingReviewHelper::ratingHints(),
					'noRatedMsg' => esc_html__( 'Not rated yet!', 'cbxscratingreview' ),
					'img_path'   => apply_filters( 'cbxscratingreview_star_image_url', CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/stars/' )
				),
				'validation' => array(
					'required'    => esc_html__( 'This field is required.', 'cbxscratingreview' ),
					'remote'      => esc_html__( 'Please fix this field.', 'cbxscratingreview' ),
					'email'       => esc_html__( 'Please enter a valid email address.', 'cbxscratingreview' ),
					'url'         => esc_html__( 'Please enter a valid URL.', 'cbxscratingreview' ),
					'date'        => esc_html__( 'Please enter a valid date.', 'cbxscratingreview' ),
					'dateISO'     => esc_html__( 'Please enter a valid date ( ISO ).', 'cbxscratingreview' ),
					'number'      => esc_html__( 'Please enter a valid number.', 'cbxscratingreview' ),
					'digits'      => esc_html__( 'Please enter only digits.', 'cbxscratingreview' ),
					'equalTo'     => esc_html__( 'Please enter the same value again.', 'cbxscratingreview' ),
					'maxlength'   => esc_html__( 'Please enter no more than {0} characters.', 'cbxscratingreview' ),
					'minlength'   => esc_html__( 'Please enter at least {0} characters.', 'cbxscratingreview' ),
					'rangelength' => esc_html__( 'Please enter a value between {0} and {1} characters long.', 'cbxscratingreview' ),
					'range'       => esc_html__( 'Please enter a value between {0} and {1}.', 'cbxscratingreview' ),
					'max'         => esc_html__( 'Please enter a value less than or equal to {0}.', 'cbxscratingreview' ),
					'min'         => esc_html__( 'Please enter a value greater than or equal to {0}.', 'cbxscratingreview' ),
					'recaptcha'   => esc_html__( 'Please check the captcha.', 'cbxscratingreview' ),
				),

				'review_common_config' => array(
					'require_headline' => $require_headline,
					'require_comment'  => $require_comment
				),
				/*'enable_location'      => ( $enable_location == 'on' ) ? 1 : 0,
				'googlemap_api_key'    => $googlemap_api_key,
				'googlemap_api_zoom'   => $googlemap_api_zoom*/
			) );

			$admin_js_vars = apply_filters( 'cbxscratingreview_admin_js_vars', array(
				'ajaxurl'            => admin_url( 'admin-ajax.php' ),
				'nonce'              => wp_create_nonce( 'cbxscratingreview' ),
				'rating'             => array(
					'cancelHint' => esc_html__( 'Cancel this rating!', 'cbxscratingreview' ),
					'hints'      => CBXSCRatingReviewHelper::ratingHints(),
					'noRatedMsg' => esc_html__( 'Not rated yet!', 'cbxscratingreview' ),
					'img_path'   => apply_filters( 'cbxscratingreview_star_image_url', CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/stars/' )
				),
				'delete_error'       => esc_html__( 'Sorry! delete failed!', 'cbxscratingreview' ),
				'delete_text'        => esc_html__( 'Delete', 'cbxscratingreview' ),
				'sort_text'          => esc_html__( 'Drag and Sort', 'cbxscratingreview' ),
				'button_text_ok'     => esc_html__( 'Ok', 'cbxscratingreview' ),
				'button_text_cancel' => esc_html__( 'Cancel', 'cbxscratingreview' ),
				'delete_error'       => esc_html__( 'Sorry! Some problem during deletion.', 'cbxscratingreview' ),
				'reviews_arr'        => array(),

			) );

			wp_register_script( 'jquery-cbxscratingreview-raty', plugin_dir_url( __FILE__ ) . '../assets/js/jquery.cbxscratingreview_raty.js', array( 'jquery' ), $this->version, true );
			wp_register_script( 'sweetalert2', plugin_dir_url( __FILE__ ) . '../assets/vendors/sweetalert2/sweetalert2.js', array( 'jquery' ), $this->version, true );

			wp_register_script( 'select2', plugin_dir_url( __FILE__ ) . '../assets/vendors/select2/js/select2.full.min.js', array( 'jquery' ), $this->version, true );

			wp_register_script( 'jquery-validate', plugin_dir_url( __FILE__ ) . '../assets/vendors/jquery-validate/jquery.validate' . $suffix . '.js', array( 'jquery' ), $this->version, true );


			$ratingform_js_dep[] = 'cbxscratingreview-events';
			$ratingform_js_dep[] = 'jquery';
			$ratingform_js_dep[] = 'jquery-ui-datepicker';
			$ratingform_js_dep[] = 'jquery-cbxscratingreview-raty';
			$ratingform_js_dep[] = 'jquery-validate';

			do_action( 'cbxscratingreview_reg_admin_scripts' );


			wp_register_script( 'cbxscratingreview-admin', plugin_dir_url( __FILE__ ) . '../assets/js/cbxscratingreview-admin.js', array(
				'cbxscratingreview-events',
				'jquery',
				'jquery-cbxscratingreview-raty',
				'jquery-ui-datepicker',
				'sweetalert2'
			), $this->version, true );

			$ratingform_js_dep[] = 'cbxscratingreview-admin'; // adding the common js file admin, same logic like public version

			$ratingform_js_dep = apply_filters( 'cbxscratingreview_ratingadminform_js_dep', $ratingform_js_dep );

			wp_register_script( 'cbxscratingreview-ratingform-adminedit', plugin_dir_url( __FILE__ ) . '../assets/js/cbxscratingreview-ratingform-adminedit.js', $ratingform_js_dep, $this->version, true );

			if ( $current_page == 'cbxscratingreviewreviewlist' && ( isset( $_GET['view'] ) && $_GET['view'] == 'addedit' ) ) {

				wp_localize_script( 'cbxscratingreview-ratingform-adminedit', 'cbxscratingreview_ratingform', $ratingform_js_vars );

				wp_enqueue_script( 'cbxscratingreview-events' );
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-datepicker' );

				wp_enqueue_script( 'jquery-cbxscratingreview-raty' );
				wp_enqueue_script( 'jquery-validate' );

				do_action( 'cbxscratingreview_enq_admin_ratingform_scripts' );

				wp_enqueue_script( 'cbxscratingreview-ratingform-adminedit' );

				do_action( 'cbxscratingreview_enq_admin_ratingform_scripts_after' );
			}

			//only for review listing, review edit
			if ( $current_page == 'cbxscratingreviewreviewlist' ) {

				wp_enqueue_media();

				wp_localize_script( 'cbxscratingreview-admin', 'cbxscratingreview_admin', $admin_js_vars );


				// enqueue scripts
				wp_enqueue_script( 'cbxscratingreview-events' );

				wp_enqueue_script( 'jquery-cbxscratingreview-raty' );
				wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_enqueue_script( 'sweetalert2' );
				wp_enqueue_script( 'cbxscratingreview-admin' );
			}

			//only for setting page
			if ( $current_page == 'cbxscratingreviewsettings' ) {

				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_media();

				wp_enqueue_script( 'wp-color-picker' );

				wp_register_script( 'cbxscratingreview-setting', plugin_dir_url( __FILE__ ) . '../assets/js/cbxscratingreview-setting.js', array(
					'cbxscratingreview-events',
					'jquery',
					'wp-color-picker',
					'select2',
				), $this->version, true );
				wp_localize_script( 'cbxscratingreview-setting', 'cbxscratingreview_admin', $admin_js_vars );

				do_action( 'cbxscratingreview_enq_admin_setting_js_before' );

				wp_enqueue_script( 'cbxscratingreview-events' );


				$setting_js_vars = apply_filters( 'cbxscratingreview_setting_js_vars',
					array(
						'please_select' => esc_html__( 'Please Select', 'cbxscratingreview' ),
						'upload_btn'    => esc_html__( 'Upload', 'cbxscratingreview' ),
						'upload_title'  => esc_html__( 'Select Media', 'cbxscratingreview' ),
					) );
				wp_localize_script( 'cbxscratingreview-setting', 'cbxscratingreview_setting', $setting_js_vars );

				wp_enqueue_script( 'cbxscratingreview-setting' );

				do_action( 'cbxscratingreview_enq_admin_setting_js_after' );
			}

			//header scroll
			wp_register_script( 'cbxscratingreview-scroll', plugins_url( '../assets/js/cbxscratingreview-scroll.js', __FILE__ ), array( 'jquery' ),
                $this->version,true );
			if ( $current_page == 'cbxscratingreviewsettings' || $current_page == 'cbxscratingreview-help-support' ) {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'cbxscratingreview-scroll' );
			}
		}//end enqueue_scripts


		//on publish review calculate avg
		public function review_publish_adjust_avg( $review_info ) {
			//calculate avg
			CBXSCRatingReviewHelper::calculatePostAvg( $review_info );
		}

		//on unpublish review adjust avg
		public function review_unpublish_adjust_avg( $review_info ) {
			CBXSCRatingReviewHelper::adjustPostwAvg( $review_info );
		}

		/**
		 * Do some extra cleanup on after review delete
		 *
		 * @param $review_info
		 */
		public function review_delete_after( $review_info ) {
			global $wpdb;

			$review_id = intval( $review_info['id'] );

			//adjust avg
			CBXSCRatingReviewHelper::adjustPostwAvg( $review_info );

		}//end review_delete_after

		/**
		 * On user delete delete reviews
		 *
		 * @param $user_id
		 */
		public function review_delete_after_delete_user( $user_id ) {
			global $wpdb;
			$table_cbxscratingreview_review = $wpdb->prefix . 'cbxscratingreview_log';

			//get all reviews for this user
			$reviews = cbxscratingreview_ReviewsByUser( $user_id, - 1 );
			foreach ( $reviews as $review ) {

				do_action( 'cbxscratingreview_review_delete_before', $review );

				$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxscratingreview_review WHERE id=%d", intval( $review['id'] ) ) );

				if ( $delete_status !== false ) {
					do_action( 'cbxscratingreview_review_delete_after', $review );
				}

			}
		}

		/**
		 * Post delete hook init
		 */
		public function review_delete_after_delete_post_init() {
			add_action( 'delete_post', array( $this, 'review_delete_after_delete_post' ), 10 );
		}

		/**
		 * On post  delete delete reviews
		 *
		 * @param $post_id
		 */
		public function review_delete_after_delete_post( $post_id ) {
			global $wpdb;
			$table_cbxscratingreview_review = $wpdb->prefix . 'cbxscratingreview_log';

			//get all reviews for this post
			$reviews = cbxscratingreview_postReviews( $post_id, - 1 );
			if ( is_array( $reviews ) && sizeof( $reviews ) > 0 ) {
				foreach ( $reviews as $review ) {

					do_action( 'cbxscratingreview_review_delete_before', $review );

					$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxscratingreview_review WHERE id=%d", $review['id'] ) );

					if ( $delete_status !== false ) {
						do_action( 'cbxscratingreview_review_delete_after', $review );
					}

				}
			}
		}//end review_delete_after_delete_post

		/**
		 * Ajax review edit
		 */
		public function review_rating_admin_edit() {
			check_ajax_referer( 'cbxscratingreview', 'security' );

			$cbxscratingreview_setting = $this->setting;

			$show_headline    = intval( $cbxscratingreview_setting->get_option( 'show_headline', 'cbxscratingreview_common_config', 1 ) );
			$require_headline = intval( $cbxscratingreview_setting->get_option( 'require_headline', 'cbxscratingreview_common_config', 1 ) );

			$show_comment    = intval( $cbxscratingreview_setting->get_option( 'show_comment', 'cbxscratingreview_common_config', 1 ) );
			$require_comment = intval( $cbxscratingreview_setting->get_option( 'require_comment', 'cbxscratingreview_common_config', 1 ) );

			$default_status = intval( $cbxscratingreview_setting->get_option( 'default_status', 'cbxscratingreview_common_config', 1 ) );


			$submit_data = $_REQUEST['cbxscratingreview_ratingForm'];

			$validation_errors = $success_data = $return_response = $response_data_arr = array();
			$ok_to_process     = 0;
			$success_msg_class = $success_msg_info = '';
			//$review_photos     = array();


			if ( is_user_logged_in() ) {

				$post_id   = isset( $submit_data['post_id'] ) ? intval( $submit_data['post_id'] ) : 0;
				$review_id = isset( $submit_data['log_id'] ) ? intval( $submit_data['log_id'] ) : 0;


				$rating_score = isset( $submit_data['score'] ) ? floatval( $submit_data['score'] ) : 0;

				$review_headline = isset( $submit_data['headline'] ) ? sanitize_text_field( $submit_data['headline'] ) : '';
				$review_comment  = isset( $submit_data['comment'] ) ? wp_kses( $submit_data['comment'], CBXSCRatingReviewHelper::allowedHtmlTags() ) : '';


				$default_status = apply_filters( 'cbxscratingreview_review_review_default_status', $default_status, $post_id );
				$new_status     = isset( $submit_data['status'] ) ? intval( $submit_data['status'] ) : $default_status;


				if ( $review_id <= 0 ) {
					$validation_errors['top_errors']['log_id']['log_id_wrong'] = esc_html__( 'Sorry! Invalid review id. Please check and try again.', 'cbxscratingreview' );
				} else {
					$review_info_old = cbxscratingreview_singleReview( $review_id );
					if ( $review_info_old == null ) {
						$validation_errors['top_errors']['log']['log_wrong'] = esc_html__( 'Sorry! Invalid review. Please check and try again.', 'cbxscratingreview' );
					}
				}

				if ( $post_id <= 0 ) {
					$validation_errors['top_errors']['post']['post_id_wrong'] = esc_html__( 'Sorry! Invalid post. Please check and try again.', 'cbxscratingreview' );
				}


				if ( $rating_score <= 0 || $rating_score > 5 ) {
					$validation_errors['cbxscratingreview_rating_score']['rating_score_wrong'] = esc_html__( 'Sorry! Invalid rating score. Please check and try again.', 'cbxscratingreview' );
				}


				if ( $show_headline && $require_headline && $review_headline == '' ) {
					$validation_errors['cbxscratingreview_review_headline']['review_headline_empty'] = esc_html__( 'Please provide title', 'cbxscratingreview' );
				}
				if ( $show_comment && $require_comment && $review_comment == '' ) {
					$validation_errors['cbxscratingreview_review_comment']['review_comment_empty'] = esc_html__( 'Please provide review', 'cbxscratingreview' );
				}


			} else {
				$validation_errors['top_errors']['user']['user_guest'] = esc_html__( 'You aren\'t currently logged in. Please login to rate.', 'cbxscratingreview' );
			}

			$validation_errors = apply_filters( 'cbxscratingreview_review_adminedit_validation_errors', $validation_errors, $post_id, $submit_data );

			if ( sizeof( $validation_errors ) > 0 ) {

			} else {


				$old_status = $review_info_old['status'];


				$ok_to_process = 1;

				global $wpdb;

				$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';


				$log_update_status = false;

				$attachment = maybe_unserialize( $review_info_old['attachment'] );

				//$attachment['photos'] = $review_photos;
				//$attachment['video']  = $review_video;

				$attachment = apply_filters( 'cbxscratingreview_review_adminedit_attachment', $attachment, $post_id, $submit_data, $review_id );

				$extraparams = maybe_unserialize( $review_info_old['extraparams'] );
				$extraparams = apply_filters( 'cbxscratingreview_review_adminedit_extraparams', $extraparams, $post_id, $submit_data, $review_id );

				$user_id = intval( get_current_user_id() );

				// insert rating log
				$data = array(
					'score'         => $rating_score,
					'headline'      => $review_headline,
					'comment'       => $review_comment,
					//'review_date'   => $review_date,
					//'location'      => $review_location,
					//'lat'           => $review_lat,
					//'lon'           => $review_lon,
					'extraparams'   => maybe_serialize( $extraparams ),
					'attachment'    => maybe_serialize( $attachment ),
					'status'        => $new_status,
					'mod_by'        => $user_id,
					'date_modified' => current_time( 'mysql' )
				);

				$data = apply_filters( 'cbxscratingreview_review_adminedit_data', $data, $post_id, $submit_data, $review_id );

				$data_format = array(
					'%f', // score
					'%s', // headline
					'%s', // comment
					//'%s', // review_date
					//'%s', // location
					//'%f', // lat
					//'%f', // lon
					'%s', // extraparams
					'%s', // attachment
					'%s', // status
					'%d', // mod_by
					'%s', // date_modified
				);

				$data_format = apply_filters( 'cbxscratingreview_review_adminedit_data_format', $data_format, $post_id, $submit_data, $review_id );

				$data_where = array(
					'id'      => $review_id,
					'post_id' => $post_id
				);

				$data_where_format = array(
					'%d', // id
					'%d' // post_id
				);

				$data_where        = apply_filters( 'cbxscratingreview_review_adminedit_where', $data_where, $post_id, $submit_data, $review_id );
				$data_where_format = apply_filters( 'cbxscratingreview_review_adminedit_where_format', $data_where_format, $post_id, $submit_data, $review_id );

				$log_update_status = $wpdb->update(
					$table_rating_log,
					$data,
					$data_where,
					$data_format,
					$data_where_format
				);

				if ( $log_update_status != false ) {
					$review_id = $review_id;

					do_action( 'cbxscratingreview_review_adminedit_just_success', $post_id, $submit_data, $review_id );


					$success_msg_class = 'success';

					$success_msg_info = esc_html__( 'Review updated successfully', 'cbxscratingreview' );

					$success_msg_info = apply_filters( 'cbxscratingreview_review_adminedit_success_info', $success_msg_info, 'success' );

					$review_info = cbxscratingreview_singleReview( $review_id );

					/*$response_data_arr['user_id']         = $review_info['user_id'];
					$response_data_arr['rating_id']       = $review_info['id'];
					$response_data_arr['rating_score']    = $review_info['score'] . '/5';
					$response_data_arr['headline']        = $review_info['headline'];
					$response_data_arr['comment']         = $review_info['comment'];
					$response_data_arr['date_created']    = CBXSCRatingReviewHelper::dateReadableFormat( $review_info['date_created'] );
					$response_data_arr['new_review_info'] = $review_info;

					//return last review with the response data
					if ( $default_status == 1 ) {
						$response_data_arr['review_html'] = '<li id="cbxscratingreview_review_list_item_' . intval( $review_id ) . '" class="' . apply_filters( 'cbxscratingreview_review_list_item_class', 'cbxscratingreview_review_list_item' ) . '">' . cbxscratingreview_singleReviewRender( $review_info ) . '</li>';
					}*/


					//if status change
					if ( $old_status != $new_status ) {
						if ( $new_status == 1 ) {
							do_action( 'cbxscratingreview_review_publish', $review_info, $review_info_old );
						} else {
							do_action( 'cbxscratingreview_review_unpublish', $review_info, $review_info_old );

						}
						do_action( 'cbxscratingreview_review_status_change', $old_status, $new_status, $review_info, $review_info_old );

						//send email to user for review status change if enabled
						$rsc_user_status = $cbxscratingreview_setting->get_option( 'rsc_user_status', 'cbxscratingreview_email_alert', 'on' );
						if ( $rsc_user_status == 'on' ) {
							$rsc_user_email_status = CBXSCRatingReviewMailAlert::sendReviewStatusUpdateUserEmailAlert( $review_info );
						}

					}//end status change detected
					else {
						//simple update without status change
						do_action( 'cbxscratingreview_review_update_without_status', $new_status, $review_info, $review_info_old );
					}

					$response_data_arr = apply_filters( 'cbxscratingreview_review_adminedit_response_data', $response_data_arr, $post_id, $submit_data, $review_info );


					do_action( 'cbxscratingreview_review_adminedit_success', $post_id, $submit_data, $review_info, $review_info_old );

				}


				$success_data['responsedata'] = $response_data_arr;
				$success_data['class']        = $success_msg_class;
				$success_data['msg']          = $success_msg_info;
			}//end review submit validation

			$return_response['ok_to_process'] = $ok_to_process;
			$return_response['success']       = $success_data;
			$return_response['error']         = $validation_errors;

			echo wp_json_encode( $return_response );
			wp_die();
		}//end review_rating_admin_edit

		/**
		 * Show action links on the plugin screen.
		 *
		 * @param mixed $links Plugin Action links.
		 *
		 * @return  array
		 */
		public function plugin_action_links( $links ) {
			$action_links = array(
				'settings'    => '<a style="color: #6648fe !important; font-weight: bold;" href="' . admin_url( 'admin.php?page=cbxscratingreviewsettings' ) . '" aria-label="' . esc_attr__( 'View settings', 'cbxscratingreview' ) . '">' . esc_html__( 'Settings', 'cbxscratingreview' ) . '</a>',
				'translation' => '<a style="color: #6648fe !important; font-weight: bold;" target="_blank" href="https://translate.wordpress.org/projects/wp-plugins/cbxscratingreview/" aria-label="' . esc_attr__( 'Translations', 'cbxscratingreview' ) . '">' . esc_html__( 'Translations', 'cbxscratingreview' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}//end plugin_action_links

		/**
		 * Filters the array of row meta for each/specific plugin in the Plugins list table.
		 * Appends additional links below each/specific plugin on the plugins page.
		 *
		 * @access  public
		 *
		 * @param array  $links_array      An array of the plugin's metadata
		 * @param string $plugin_file_name Path to the plugin file
		 * @param array  $plugin_data      An array of plugin data
		 * @param string $status           Status of the plugin
		 *
		 * @return  array       $links_array
		 */
		public function plugin_row_meta( $links_array, $plugin_file_name, $plugin_data, $status ) {
			if ( strpos( $plugin_file_name, CBXSCRATINGREVIEW_BASE_NAME ) !== false ) {
				if ( ! function_exists( 'is_plugin_active' ) ) {
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}

				$links_array[] = '<a target="_blank" style="color:#6648fe !important; font-weight: bold;" href="https://wordpress.org/support/plugin/cbxscratingreview/" aria-label="' . esc_attr__( 'Free Support', 'cbxscratingreview' ) . '">' . esc_html__( 'Free Support', 'cbxscratingreview' ) . '</a>';

				$links_array[] = '<a target="_blank" style="color:#6648fe !important; font-weight: bold;" href="https://codeboxr.com/contact-us/" aria-label="' . esc_attr__( 'Pro Support', 'cbxscratingreview' ) . '">' . esc_html__( 'Pro Support', 'cbxscratingreview' ) . '</a>';

				$links_array[] = '<a target="_blank" style="color:#6648fe !important; font-weight: bold;" href="https://wordpress.org/plugins/cbxscratingreview/#reviews" aria-label="' . esc_attr__( 'Reviews', 'cbxscratingreview' ) . '">' . esc_html__( 'Reviews', 'cbxscratingreview' ) . '</a>';

				$links_array[] = '<a target="_blank" style="color:#6648fe !important; font-weight: bold;" href="https://codeboxr.com/documentation-for-cbx-single-criteria-rating-review-for-wordpress/" aria-label="' . esc_attr__( 'Documentation', 'cbxscratingreview' ) . '">' . esc_html__( 'Documentation', 'cbxscratingreview' ) . '</a>';

				$links_array[] = '<a target="_blank" style="color:#6648fe !important; font-weight: bold;" href="https://codeboxr.com/product/cbx-single-criteria-rating-review-for-wordpress/" aria-label="' . esc_attr__( 'Try Pro Addons', 'cbxscratingreview' ) . '">' . esc_html__( 'Try Pro Addons', 'cbxscratingreview' ) . '</a>';
			}

			return $links_array;
		}//end plugin_row_meta

		/**
		 * If we need to do something in upgrader process is completed for poll plugin
		 *
		 * @param $upgrader_object
		 * @param $options
		 */
		public function plugin_upgrader_process_complete( $upgrader_object, $options ) {
			if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
				foreach ( $options['plugins'] as $each_plugin ) {
					if ( $each_plugin == CBXSCRATINGREVIEW_BASE_NAME ) {
						if ( ! class_exists( 'CBXSCRatingReview_Activator' ) ) {
							require_once plugin_dir_path( __FILE__ ) . '../includes/class-cbxscratingreview-activator.php';
						}

						//create tables
						CBXSCRatingReview_Activator::createTables();
						CBXSCRatingReview_Activator::createPages();

						set_transient( 'cbxscratingreview_upgraded_notice', 1 );

						break;
					}
				}
			}

		}//end plugin_upgrader_process_complete

		/**
		 * Show a notice to anyone who has just installed the plugin for the first time
		 * This notice shouldn't display to anyone who has just updated this plugin
		 */
		public function plugin_activate_upgrade_notices() {
			// Check the transient to see if we've just activated the plugin
			if ( get_transient( 'cbxscratingreview_activated_notice' ) ) {
				echo '<div style="border-left: 2px solid #6648fe;" class="notice notice-success is-dismissible">';
				echo '<p>' . sprintf( __( 'Thanks for installing/deactivating <strong>CBX 5 Star Rating & Review</strong> V%s - Codeboxr Team', 'cbxscratingreview' ), CBXSCRATINGREVIEW_PLUGIN_VERSION ) . '</p>';
				echo '<p>' . sprintf( __( 'Check <a href="%s">Plugin Setting</a> | <a href="%s" target="_blank">Learn More</a>', 'cbxscratingreview' ), admin_url( 'admin.php?page=cbxscratingreviewsettings' ), 'https://codeboxr.com/product/cbx-single-criteria-rating-review-for-wordpress/' ) . '</p>';
				echo '</div>';
				// Delete the transient so we don't keep displaying the activation message
				delete_transient( 'cbxscratingreview_activated_notice' );

				$this->pro_addon_compatibility_campaign();

			}

			// Check the transient to see if we've just activated the plugin
			if ( get_transient( 'cbxscratingreview_upgraded_notice' ) ) {
				echo '<div style="border-left: 2px solid #6648fe;" class="notice notice-success is-dismissible">';
				echo '<p>' . sprintf( __( 'Thanks for upgrading <strong>CBX 5 Star Rating & Review</strong> V%s , enjoy the new features and bug fixes - Codeboxr Team', 'cbxscratingreview' ), CBXSCRATINGREVIEW_PLUGIN_VERSION ) . '</p>';
				echo '<p>' . sprintf( __( 'Check <a href="%s">Plugin Setting</a> | <a href="%s" target="_blank">Learn More</a>', 'cbxscratingreview' ), admin_url( 'admin.php?page=cbxscratingreviewsettings' ), 'https://codeboxr.com/product/cbx-single-criteria-rating-review-for-wordpress/' ) . '</p>';
				echo '</div>';
				// Delete the transient so we don't keep displaying the activation message
				delete_transient( 'cbxscratingreview_upgraded_notice' );

				$this->pro_addon_compatibility_campaign();

			}
		}//end plugin_activate_upgrade_notices

		/**
		 * Check plugin compatibility and pro addon install campaign
		 */
		public function pro_addon_compatibility_campaign() {

			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			//if the pro addon is active or installed
			if ( in_array( 'cbxscratingreviewpro/cbxscratingreviewpro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || defined( 'CBXSCRATINGREVIEWPRO_PLUGIN_NAME' ) ) {
				//plugin is activated

				$plugin_version = CBXSCRATINGREVIEWPRO_PLUGIN_VERSION;


				/*if(version_compare($plugin_version,'1.0.11', '<=') ){
					echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'CBX 5 Star Rating & Review Pro Addon Vx.x.x or any previous version is not compatible with CBX 5 Star Rating & Review Vx.x.x or later. Please update CBX 5 Star Rating & Review Pro Addon to version x.x.0 or later  - Codeboxr Team', 'cbxscratingreview' ) . '</p></div>';
				}*/
			} else {
				echo '<div style="border-left: 2px solid #6648fe;" class="notice notice-success is-dismissible"><p>' . sprintf( __( '<a target="_blank" href="%s">CBX 5 Star Rating & Review Pro Addon</a> has lots of pro and extended features, try it - Codeboxr Team', 'cbxscratingreview' ), 'https://codeboxr.com/product/cbx-single-criteria-rating-review-for-wordpress-pro-addon/' ) . '</p></div>';
			}

			//comment addon suport
			//if the pro addon is active or installed
			if ( in_array( 'cbxscratingreviewcomment/cbxscratingreviewcomment.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || defined( 'CBXSCRATINGREVIEWCOMMENT_PLUGIN_NAME' ) ) {
				//plugin is activated

				$plugin_version = CBXSCRATINGREVIEWCOMMENT_PLUGIN_VERSION;


				/*if(version_compare($plugin_version,'1.0.11', '<=') ){
					echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'CBX 5 Star Rating & Review Comment Vx.x.x or any previous version is not compatible with CBX 5 Star Rating & Review Vx.x.x or later. Please update CBX 5 Star Rating & Review Comment Addon to version x.x.0 or later  - Codeboxr Team', 'cbxscratingreview' ) . '</p></div>';
				}*/
			} else {
				echo '<div style="border-left: 2px solid #6648fe;" class="notice notice-success is-dismissible"><p>' . sprintf( __( '<a target="_blank" href="%s">CBX 5 Star Rating & Review Comment Addon</a> enables comment for rating, try it - Codeboxr Team', 'cbxscratingreview' ), 'https://codeboxr.com/product/comment-for-cbx-single-criteria-rating-review-for-wordpress/' ) . '</p></div>';
			}

			//myCred addon suport
			//if the pro addon is active or installed
			if ( in_array( 'cbxscratingreviewmycred/cbxscratingreviewmycred.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || defined( 'CBXSCRATINGREVIEWMYCRED_PLUGIN_NAME' ) ) {
				//plugin is activated

				$plugin_version = CBXSCRATINGREVIEWMYCRED_PLUGIN_VERSION;


				/*if(version_compare($plugin_version,'1.0.11', '<=') ){
					echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'CBX 5 Star Rating & Review myCred Vx.x.x or any previous version is not compatible with CBX 5 Star Rating & Review Vx.x.x or later. Please update CBX 5 Star Rating & Review myCred Addon to version x.x.0 or later  - Codeboxr Team', 'cbxscratingreview' ) . '</p></div>';
				}*/
			} else {
				echo '<div style="border-left: 2px solid #6648fe;" class="notice notice-success is-dismissible"><p>' . sprintf( __( '<a target="_blank" href="%s">CBX 5 Star Rating & Review myCred Addon</a> integrates with myCred plugin, try it - Codeboxr Team', 'cbxscratingreview' ), 'https://codeboxr.com/product/mycred-for-cbx-single-criteria-rating-review-for-wordpress/' ) . '</p></div>';
			}
		}//end pro_addon_compatibility_campaign

		/**
		 * Update notification for pro addon
		 *
		 * @param $transient
		 *
		 * @return object $ transient
		 */
		public function pre_set_site_transient_update_plugins_pro_addon($transient){
			// Extra check for 3rd plugins
			if ( isset( $transient->response[ 'cbxscratingreviewpro/cbxscratingreviewpro.php' ] ) ) {
				return $transient;
			}

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugin_info = array();
			$all_plugins = get_plugins();
			if(!isset($all_plugins['cbxscratingreviewpro/cbxscratingreviewpro.php'])){
				return $transient;
			}
			else{
				$plugin_info = $all_plugins['cbxscratingreviewpro/cbxscratingreviewpro.php'];
			}

			$remote_version = '1.0.6';

			if ( version_compare( $plugin_info['Version'], $remote_version, '<' ) ) {
				$obj = new stdClass();
				$obj->slug = 'cbxscratingreviewpro';
				$obj->new_version = $remote_version;
				$obj->plugin = 'cbxscratingreviewpro/cbxscratingreviewpro.php';
				$obj->url = '';
				$obj->package = false;
				$obj->name = 'CBX 5 Star Rating & Review Pro Addon';
				$transient->response[ 'cbxscratingreviewpro/cbxscratingreviewpro.php' ] = $obj;
			}

			return $transient;
		}//end pre_set_site_transient_update_plugins_pro_addons

		/**
		 * Update notification for comment addon
		 *
		 * @param $transient
		 *
		 * @return object $ transient
		 */
		public function pre_set_site_transient_update_plugins_comment_addon($transient){
			// Extra check for 3rd plugins
			if ( isset( $transient->response[ 'cbxscratingreviewcomment/cbxscratingreviewcomment.php' ] ) ) {
				return $transient;
			}

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugin_info = array();
			$all_plugins = get_plugins();
			if(!isset($all_plugins['cbxscratingreviewcomment/cbxscratingreviewcomment.php'])){
				return $transient;
			}
			else{
				$plugin_info = $all_plugins['cbxscratingreviewcomment/cbxscratingreviewcomment.php'];
			}

			$remote_version = '1.0.3';

			if ( version_compare( $plugin_info['Version'], $remote_version, '<' ) ) {
				$obj = new stdClass();
				$obj->slug = 'cbxscratingreviewcomment';
				$obj->new_version = $remote_version;
				$obj->plugin = 'cbxscratingreviewcomment/cbxscratingreviewcomment.php';
				$obj->url = '';
				$obj->package = false;
				$obj->name = 'CBX 5 Star Rating & Review Comment Addon';
				$transient->response[ 'cbxscratingreviewcomment/cbxscratingreviewcomment.php' ] = $obj;
			}

			return $transient;
		}//end pre_set_site_transient_update_plugins_comment_addon

		/**
		 * Add our self-hosted autoupdate plugin to the filter transient
		 *
		 * @param $transient
		 *
		 * @return object $ transient
		 */
		public function pre_set_site_transient_update_plugins_mycred_addon($transient){


			// Extra check for 3rd plugins
			if ( isset( $transient->response[ 'cbxscratingreviewmycred/cbxscratingreviewmycred.php' ] ) ) {
				return $transient;
			}



			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugin_info = array();
			$all_plugins = get_plugins();

			if(!isset($all_plugins['cbxscratingreviewmycred/cbxscratingreviewmycred.php'])){
				return $transient;
			}
			else{
				$plugin_info = $all_plugins['cbxscratingreviewmycred/cbxscratingreviewmycred.php'];
			}




			$remote_version = '1.0.2';

			if ( version_compare( $plugin_info['Version'], $remote_version, '<' ) ) {
				$obj = new stdClass();
				$obj->slug = 'cbxscratingreviewmycred';
				$obj->new_version = $remote_version;
				$obj->plugin = 'cbxscratingreviewmycred/cbxscratingreviewmycred.php';
				$obj->url = '';
				$obj->package = false;
				$obj->name = 'CBX 5 Star Rating & Review myCred Addon';
				$transient->response[ 'cbxscratingreviewmycred/cbxscratingreviewmycred.php' ] = $obj;
			}

			return $transient;
		}//end pre_set_site_transient_update_plugins_mycred_addon

		/**
		 * Pro Addon update message
		 */
		public function plugin_update_message_pro_addons(){
			echo ' '.sprintf(__('Check how to <a style="color:#9c27b0 !important; font-weight: bold;" href="%s"><strong>Update manually</strong></a> , download latest version from <a style="color:#9c27b0 !important; font-weight: bold;" href="%s"><strong>My Account</strong></a> section of Codeboxr.com', 'cbxscratingreview'), 'https://codeboxr.com/manual-update-pro-addon/', 'https://codeboxr.com/my-account/');
		}//end plugin_update_message_pro_addons

		/**
		 * User's review listing screen option columns
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */
        public function  log_listing_screen_cols( $columns =  array()){
	        $columns = array(
		        //'cb'           => '<input type="checkbox" />', //Render a checkbox instead of text
		        'id'           => esc_html__( 'ID', 'cbxscratingreview' ),
		        'post_id'      => esc_html__( 'Post', 'cbxscratingreview' ),
		        'user_id'      => esc_html__( 'User', 'cbxscratingreview' ),
		        'score'        => esc_html__( 'Score', 'cbxscratingreview' ),
		        'post_type'    => esc_html__( 'Post Type', 'cbxscratingreview' ),
		        'headline'     => esc_html__( 'Headline', 'cbxscratingreview' ),
		        'comment'      => esc_html__( 'Comment', 'cbxscratingreview' ),
		        //'review_date'  => esc_html__( 'Exp. Date', 'cbxscratingreview' ),
		        'date_created' => esc_html__( 'Created', 'cbxscratingreview' ),
		        //'location'     => esc_html__( 'Location', 'cbxscratingreview' ),
		        //'lat'          => esc_html__( 'Lat', 'cbxscratingreview' ),
		        //'lon'          => esc_html__( 'Long', 'cbxscratingreview' ),
		        'status'       => esc_html__( 'Status', 'cbxscratingreview' )
	        );

	        return apply_filters( 'cbxscratingreview_admin_log_listing_hidden_columns', $columns );
        }//end log_listing_screen_cols

		/**
		 * User's avg review listing screen option columns
		 *
		 * @param $columns
		 *
		 * @return mixed
		 */
        public function avglog_listing_screen_cols($columns = array()){
	        $columns = array(
		        //'cb'            => '<input type="checkbox" />', //Render a checkbox instead of text
		        'id'            => esc_html__( 'ID', 'cbxscratingreview' ),
		        'post_id'       => esc_html__( 'Post', 'cbxscratingreview' ),
		        'post_title'    => esc_html__( 'Post Title', 'cbxscratingreview' ),
		        'avg_rating'    => esc_html__( 'Average Rating', 'cbxscratingreview' ),
		        'total_count'   => esc_html__( 'Total', 'cbxscratingreview' ),
		        'date_created'  => esc_html__( 'Created', 'cbxscratingreview' ),
		        'date_modified' => esc_html__( 'Modified', 'cbxscratingreview' ),
	        );

	        //return $columns;
	        return apply_filters( 'cbxscratingreview_admin_avglog_listing_hidden_columns', $columns );
        }//end avglog_listing_screen_cols
	}//end class CBXSCRatingReview_Admin