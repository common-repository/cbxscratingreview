<?php

	/**
	 * Fired during plugin activation
	 *
	 * @link       http://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    CBXSCRatingReview
	 * @subpackage CBXSCRatingReview/includes
	 */

	/**
	 * Fired during plugin activation.
	 *
	 * This class defines all code necessary to run during the plugin's activation.
	 *
	 * @since      1.0.0
	 * @package    CBXSCRatingReview
	 * @subpackage CBXSCRatingReview/includes
	 * @author     Sabuj Kundu <sabuj@codeboxr.com>
	 */
	class CBXSCRatingReview_Activator {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {
			//check if can activate plugin
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}


			$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
			check_admin_referer( "activate-plugin_{$plugin}" );

			//create tables
			CBXSCRatingReview_Activator::createTables();

			//create pages
			CBXSCRatingReview_Activator::createPages();

			set_transient( 'cbxscratingreview_activated_notice', 1 );
		}//end activate

		/**
		 * Create  necessary tables needed for 'cbxscratingreview'
		 */
		public static function createTables() {
			global $wpdb;

			$charset_collate = $wpdb->get_charset_collate();


			//tables
			$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';
			$table_rating_avg = $wpdb->prefix . 'cbxscratingreview_log_avg';


			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			//create rating log table
			$table_rating_log_sql = "CREATE TABLE $table_rating_log (
                          id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                          post_id bigint(20) unsigned NOT NULL COMMENT 'foreign key of posts table',
                          post_type varchar(50) NOT NULL COMMENT 'post type e.g. post, page, media',
                          user_id bigint(20) unsigned NOT NULL COMMENT 'foreign key of users table',
                          score float NOT NULL DEFAULT '0' COMMENT 'user given rating, can be half i.e. 3.5',   
                          headline text NOT NULL DEFAULT '' COMMENT 'review short title',
                          comment text NOT NULL DEFAULT '' COMMENT 'review full desc',                              
                          attachment text NOT NULL DEFAULT '' COMMENT 'photos or video url',                             
                          extraparams text NOT NULL DEFAULT '' COMMENT 'extra parameters for future new fields',  
                          status varchar(20) NOT NULL DEFAULT 0 COMMENT '0 pending, 1 published, 2 unpublished, 3 spam',                          
                          mod_by bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'foreign key of user table. who last modify this list',
                          date_created datetime NOT NULL COMMENT 'created date',
                          date_modified datetime DEFAULT NULL COMMENT 'modified date',
                          PRIMARY KEY (id)
                        ) $charset_collate; ";

			dbDelta( $table_rating_log_sql );


			//create table for rating avg log
			$table_rating_avg_log_sql = "CREATE TABLE $table_rating_avg (
                          id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                          post_id bigint(20) unsigned NOT NULL COMMENT 'foreign key of posts table',
                          post_type varchar(50) NOT NULL COMMENT 'post type e.g. post, page, media',
                          avg_rating float NOT NULL DEFAULT '0' COMMENT 'user given rating avg',  
                          total_count bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'total user rate for this post',
                          rating_stat text NOT NULL DEFAULT '' COMMENT 'statistics about how many user give 1, 2, 3, 4, 5 rating',  
                		  date_created datetime NOT NULL COMMENT 'created date',
                          date_modified datetime DEFAULT NULL COMMENT 'modified date',
                          PRIMARY KEY (id)
                        ) $charset_collate; ";

			dbDelta( $table_rating_avg_log_sql );
		}//end createTables

		/**
		 * Create pages on activate
		 */
		public static function createPages() {
			$pages = apply_filters( 'cbxscratingreview_create_pages', array(
				'review_userdashboard_id' => array(
					'slug'    => _x( 'reviewdashboard', 'Page slug', 'cbxscratingreview' ),
					'title'   => _x( 'Reviewer Dashboard', 'Page title', 'cbxscratingreview' ),
					'content' => '[cbxscratingreview_userdashboard]'
				),
				'single_review_edit_id'   => array(
					'slug'    => _x( 'reviewedit', 'Page slug', 'cbxscratingreview' ),
					'title'   => _x( 'Edit Review', 'Page title', 'cbxscratingreview' ),
					'content' => '[cbxscratingreview_editreview]'
				),
				'single_review_view_id'   => array(
					'slug'    => _x( 'reviewdetails', 'Page slug', 'cbxscratingreview' ),
					'title'   => _x( 'Review Details', 'Page title', 'cbxscratingreview' ),
					'content' => '[cbxscratingreview_singlereview]'
				)
			) );

			foreach ( $pages as $key => $page ) {
				CBXSCRatingReview_Activator::createPage( $key, esc_sql( $page['slug'] ), $page['title'], $page['content'] );
			}
		}//end createPages

		/**
		 * Create a page and store the ID in an option.
		 *
		 * @param string $key
		 * @param string $slug
		 * @param string $page_title
		 * @param string $page_content
		 *
		 * @return int|string|void|WP_Error|null
		 */
		public static function createPage( $key = '', $slug = '', $page_title = '', $page_content = '' ) {
			global $wpdb;

			if ( $key == '' ) {
				return null;
			}
			if ( $slug == '' ) {
				return null;
			}

			$cbxscratingreview_tools = get_option( 'cbxscratingreview_tools' );
			$option_value            = isset( $cbxscratingreview_tools[ $key ] ) ? intval( $cbxscratingreview_tools[ $key ] ) : 0;

			//if valid page id already exists
			if ( $option_value > 0 ) {
				$page_object = get_post( $option_value );

				if ( is_object( $page_object ) && 'page' === $page_object->post_type && ! in_array( $page_object->post_status, array(
						'pending',
						'trash',
						'future',
						'auto-draft'
					) ) ) {
					// Valid page is already in place
					return $page_object->ID;
				}
			}

			if ( strlen( $page_content ) > 0 ) {
				// Search for an existing page with the specified page content (typically a shortcode)
				$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
			} else {
				// Search for an existing page with the specified page slug
				$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
			}

			$valid_page_found = apply_filters( 'cbxscratingreview_create_page_id', $valid_page_found, $slug, $page_content );


			// Search for a matching valid trashed page
			if ( strlen( $page_content ) > 0 ) {
				// Search for an existing page with the specified page content (typically a shortcode)
				$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
			} else {
				// Search for an existing page with the specified page slug
				$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
			}

			if ( $trashed_page_found ) {
				$page_id   = $trashed_page_found;
				$page_data = array(
					'ID'          => $page_id,
					'post_status' => 'publish',
				);
				wp_update_post( $page_data );
			} else {
				$page_data = array(
					'post_status'    => 'publish',
					'post_type'      => 'page',
					//'post_author'    => 1,
					'post_name'      => $slug,
					'post_title'     => $page_title,
					'post_content'   => $page_content,
					//'post_parent'    => $post_parent,
					'comment_status' => 'closed'
				);
				$page_id   = wp_insert_post( $page_data );
			}

			//let's update the option
			$cbxscratingreview_tools[ $key ] = $page_id;
			update_option( 'cbxscratingreview_tools', $cbxscratingreview_tools );

			return $page_id;
		}//end createPage

	}//end class CBXSCRatingReview_Activator