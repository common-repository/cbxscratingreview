<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Helper class
 *
 * Class CBXSCRatingReviewHelper
 */
class CBXSCRatingReviewHelper {

	/**
	 * Admin page slugs
	 *
	 * @return mixed|void
	 */
	public static function admin_page_slugs() {
		$slugs = array( 'cbxscratingreviewreviewlist', 'cbxscratingreviewratingavglist', 'cbxscratingreviewformlist', 'cbxscratingreviewsettings','cbxscratingreview-help-support' );

		return apply_filters( 'cbxscratingreview_admin_page_slugs', $slugs );
	}//end admin_page_slugs

	/**
	 * acceptable image ext
	 * @return array
	 */
	public static function imageExtArr() {
		return array( 'jpg', 'jpeg', 'gif', 'png' );
	}

	/**
	 * Get available layouts
	 *
	 * @return mixed|void
	 */
	public static function get_layouts() {
		$layouts = array(
			'layout_default'         => esc_html__( 'Default', 'cbxscratingreview' ),
			'layout_red'      => esc_html__( 'Red', 'cbxscratingreview' ),
			'layout_pink'     => esc_html__( 'Pink', 'cbxscratingreview' ),
			'layout_purple'   => esc_html__( 'Purple', 'cbxscratingreview' ),
			'layout_indigo'   => esc_html__( 'Indigo', 'cbxscratingreview' ),
			'layout_navy'     => esc_html__( 'Navy', 'cbxscratingreview' ),
			'layout_blue'     => esc_html__( 'Blue', 'cbxscratingreview' ),
			'layout_cyan'     => esc_html__( 'Cyan', 'cbxscratingreview' ),
			'layout_teal'     => esc_html__( 'Teal', 'cbxscratingreview' ),
			'layout_green'    => esc_html__( 'Green', 'cbxscratingreview' ),
			'layout_orange'   => esc_html__( 'Orange', 'cbxscratingreview' ),
			'layout_brown'    => esc_html__( 'Brown', 'cbxscratingreview' ),
			'layout_bluegrey' => esc_html__( 'Blue Grey', 'cbxscratingreview' ),
		);

		return apply_filters( 'cbxscratingreview_layouts', $layouts );
	}//end method get_layouts

	/**
	 * All possible star filters (useful to create dropdown)
	 *
	 * @param bool $add_all
	 *
	 * @return array
	 */
	public static function get_score_filters( $add_all = true ) {
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		$enable_positive_critical  = intval( $cbxscratingreview_setting->get_option( 'enable_positive_critical', 'cbxscratingreview_common_config', 1 ) );

		$scores = array();

		if ( $add_all ) {
			$scores[''] = esc_html__( 'All Stars', 'cbxscratingreview' );
		}

		$scores['5'] = esc_html__( '5 Stars', 'cbxscratingreview' );
		$scores['4'] = esc_html__( '4 Stars', 'cbxscratingreview' );
		$scores['3'] = esc_html__( '3 Stars', 'cbxscratingreview' );
		$scores['2'] = esc_html__( '2 Stars', 'cbxscratingreview' );
		$scores['1'] = esc_html__( '1 Star', 'cbxscratingreview' );

		if ( $enable_positive_critical ) {
			$scores['-1'] = esc_html__( 'All Positive', 'cbxscratingreview' );
			$scores['-2'] = esc_html__( 'All Critical', 'cbxscratingreview' );
		}


		return $scores;
	}//end get_score_filters

	/**
	 * Returns post types as array
	 *
	 * @return array
	 */
	public static function post_types( $plain = true ) {
		$post_type_args = array(
			'builtin' => array(
				'options' => array(
					'public'   => true,
					'_builtin' => true,
					'show_ui'  => true,
				),
				'label'   => esc_html__( 'Built in post types', 'cbxscratingreview' ),
			)

		);

		$post_type_args = apply_filters( 'cbxscratingreview_post_types_args', $post_type_args );

		$output   = 'objects'; // names or objects, note names is the default
		$operator = 'and'; // 'and' or 'or'

		$postTypes = array();

		if ( $plain ) {
			foreach ( $post_type_args as $postArgType => $postArgTypeArr ) {
				$types = get_post_types( $postArgTypeArr['options'], $output, $operator );

				if ( ! empty( $types ) ) {
					foreach ( $types as $type ) {
						//$postTypes[ $postArgType ]['label']                = $postArgTypeArr['label'];
						$postTypes[ $type->name ] = $type->labels->name;
					}
				}
			}
		} else {
			foreach ( $post_type_args as $postArgType => $postArgTypeArr ) {
				$types = get_post_types( $postArgTypeArr['options'], $output, $operator );

				if ( ! empty( $types ) ) {
					foreach ( $types as $type ) {
						//$postTypes[ $postArgType ]['label']                = $postArgTypeArr['label'];
						$postTypes[ esc_attr( $postArgTypeArr['label'] ) ][ $type->name ] = $type->labels->name;
					}
				}
			}
		}


		return apply_filters( 'cbxscratingreview_post_types', $postTypes, $plain );
	}

	/**
	 * Returns filtered array of post types in plain list
	 *
	 * @param array $filter
	 *
	 * @return array
	 */
	public static function post_types_filtered( $filter = array() ) {
		$post_types          = CBXSCRatingReviewHelper::post_types( true );
		$post_types_filtered = array();
		if ( is_array( $filter ) && sizeof( $filter ) > 0 ) {
			foreach ( $post_types as $key => $value ) {
				if ( in_array( $key, $filter ) ) {
					$post_types_filtered[ $key ] = $value;
				}
			}
		}//if filter has item

		return $post_types_filtered;
	}//end post_types_filtered

	/**
	 * Get the user roles
	 *
	 * @param string $useCase
	 *
	 * @return array
	 */
	public static function user_roles( $plain = true, $include_guest = false ) {
		global $wp_roles;

		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/user.php' );

		}

		$userRoles = array();
		if ( $plain ) {
			foreach ( get_editable_roles() as $role => $roleInfo ) {
				$userRoles[ $role ] = $roleInfo['name'];
			}
			if ( $include_guest ) {
				$userRoles['guest'] = esc_html__( "Guest", 'cbxscratingreview' );
			}
		} else {
			//optgroup
			$userRoles_r = array();
			foreach ( get_editable_roles() as $role => $roleInfo ) {
				$userRoles_r[ $role ] = $roleInfo['name'];
			}

			$userRoles = array(
				'Registered' => $userRoles_r,
			);

			if ( $include_guest ) {
				$userRoles['Anonymous'] = array(
					'guest' => esc_html__( "Guest", 'cbxscratingreview' )
				);
			}
		}

		return apply_filters( 'cbxscratingreview_userroles', $userRoles, $plain, $include_guest );
	}

	public static function time2str( $ts, $fallback_format = 'M j, Y H:i' ) {
		if ( ! ctype_digit( $ts ) ) {
			$ts = strtotime( $ts );
		}
		$diff = time() - $ts;
		if ( $diff == 0 ) {
			return esc_html__( 'now', 'cbxscratingreview' );
		} elseif ( $diff > 0 ) {
			$day_diff = floor( $diff / 86400 );
			if ( $day_diff == 0 ) {
				if ( $diff < 60 ) {
					return esc_html__( 'just now', 'cbxscratingreview' );
				}
				if ( $diff < 120 ) {
					return esc_html__( '1 minute ago', 'cbxscratingreview' );
				}
				if ( $diff < 3600 ) {
					return sprintf( esc_html__( '%s minutes ago', 'cbxscratingreview' ), floor( $diff / 60 ) );
				}
				if ( $diff < 7200 ) {
					return esc_html__( '1 hour ago', 'cbxscratingreview' );
				}
				if ( $diff < 86400 ) {
					return floor( $diff / 3600 ) . ' hours ago';
				}
			}
			if ( $day_diff == 1 ) {
				return esc_html__( 'Yesterday', 'cbxscratingreview' );
			}
			if ( $day_diff < 7 ) {
				return sprintf( esc_html__( '%s days ago', 'cbxscratingreview' ), $day_diff );
			}
			if ( $day_diff < 31 ) {
				return sprintf( esc_html__( '%s weeks ago', 'cbxscratingreview' ), ceil( $day_diff / 7 ) );
			}
			if ( $day_diff < 60 ) {
				return esc_html__( 'last month', 'cbxscratingreview' );
			}

			return date( $fallback_format, $ts );
		} else {
			$diff     = abs( $diff );
			$day_diff = floor( $diff / 86400 );
			if ( $day_diff == 0 ) {
				if ( $diff < 120 ) {
					return esc_html__( 'in a minute', 'cbxscratingreview' );
				}
				if ( $diff < 3600 ) {
					return sprintf( esc_html__( 'in %s minutes', 'cbxscratingreview' ), floor( $diff / 60 ) );
				}
				if ( $diff < 7200 ) {
					return esc_html__( 'in an hour', 'cbxscratingreview' );
				}
				if ( $diff < 86400 ) {
					return sprintf( esc_html__( 'in %s hours', 'cbxscratingreview' ), floor( $diff / 3600 ) );
				}
			}
			if ( $day_diff == 1 ) {
				return esc_html__( 'Tomorrow', 'cbxscratingreview' );
			}
			if ( $day_diff < 4 ) {
				return date( 'l', $ts );
			}
			if ( $day_diff < 7 + ( 7 - date( 'w' ) ) ) {
				return esc_html__( 'next week', 'cbxscratingreview' );
			}
			if ( ceil( $day_diff / 7 ) < 4 ) {
				return sprintf( esc_html__( 'in %s weeks', 'cbxscratingreview' ), ceil( $day_diff / 7 ) );
			}
			if ( date( 'n', $ts ) == date( 'n' ) + 1 ) {
				return esc_html__( 'next month', 'cbxscratingreview' );
			}

			return date( $fallback_format, $ts );
		}
	}

	/**
	 * Add all common js and css needed for review and rating
	 */
	public static function AddJsCss() {
		$plugin_public = new CBXSCRatingReview_Public( CBXSCRATINGREVIEW_PLUGIN_NAME, CBXSCRATINGREVIEW_PLUGIN_VERSION );
		$plugin_public->enqueue_common_js_css_rating();
	}

	/**
	 * Add all js and css needed for review submit form
	 */
	public static function AddRatingFormJsCss() {
		$plugin_public = new CBXSCRatingReview_Public( CBXSCRATINGREVIEW_PLUGIN_NAME, CBXSCRATINGREVIEW_PLUGIN_VERSION );
		$plugin_public->enqueue_ratingform_js_css_rating();
	}

	/**
	 * Add all js and css needed for review edit form
	 */
	public static function AddRatingEditFormJsCss() {
		$plugin_public = new CBXSCRatingReview_Public( CBXSCRATINGREVIEW_PLUGIN_NAME, CBXSCRATINGREVIEW_PLUGIN_VERSION );
		$plugin_public->enqueue_ratingeditform_js_css_rating();
	}

	/**
	 * Returns rating hints keys
	 *
	 * @return array
	 */
	public static function ratingHints() {

		$rating_hints = array(
			esc_html__( 'Bad', 'cbxscratingreview' ),
			esc_html__( 'Poor', 'cbxscratingreview' ),
			esc_html__( 'Regular', 'cbxscratingreview' ),
			esc_html__( 'Good', 'cbxscratingreview' ),
			esc_html__( 'Gorgeous', 'cbxscratingreview' ),
		);

		return apply_filters( 'cbxscratingreview_rating_hints', $rating_hints );
	}//end ratingHints

	/**
	 * Rating hints colors
	 *
	 * @return array
	 */
	public static function ratingHintsColors() {
		$rating_hints_colors = array( '#57bb8a', '#9ace6a', '#ffcf02', '#ff9f02', '#ff6f31' );

		return apply_filters( 'cbxscratingreview_rating_hints_colors', $rating_hints_colors );
	}


	/**
	 * all posible status for a review
	 * @return array
	 */
	public static function ReviewStatusOptions() {
		$exprev_status_arr = array(
			'0' => esc_html__( 'Pending', 'cbxscratingreview' ),
			'1' => esc_html__( 'Published', 'cbxscratingreview' ),
			'2' => esc_html__( 'Unpublished', 'cbxscratingreview' ),
			'3' => esc_html__( 'Spam', 'cbxscratingreview' ),
		);

		return apply_filters( 'cbxscratingreview_review_review_status_options', $exprev_status_arr );
	}

	/**
	 * all posible status for a review
	 * @return array
	 */
	public static function ReviewPositiveScores() {
		$exprev_status_arr = array(
			'1' => esc_html__( '1 or above', 'cbxscratingreview' ),
			'2' => esc_html__( '2 or above', 'cbxscratingreview' ),
			'3' => esc_html__( '3 or above', 'cbxscratingreview' ),
			'4' => esc_html__( '4 or above', 'cbxscratingreview' ),
			'5' => esc_html__( '5', 'cbxscratingreview' ),
		);

		return apply_filters( 'cbxscratingreview_review_review_status_options', $exprev_status_arr );
	}


	/**
	 * Return all meta keys created by this plugin
	 */
	public static function getMetaKeys() {
		$meta_keys                             = array();
		$meta_keys['_cbxscratingreview_avg']   = 'Post rating avg';
		$meta_keys['_cbxscratingreview_total'] = 'Post total Rating/reviews count';

		return apply_filters( 'cbxscratingreview_meta_keys', $meta_keys );
	}

	/**
	 * Get all  core tables list(key and db table name)
	 */
	public static function getAllDBTablesList() {
		global $wpdb;

		//tables
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';
		$table_rating_avg = $wpdb->prefix . 'cbxscratingreview_log_avg';


		$table_names        = array();
		$table_names['log'] = $table_rating_log;
		$table_names['avg'] = $table_rating_avg;


		return apply_filters( 'cbxscratingreview_table_list', $table_names );
	}//end function getAllDBTablesList

	/**
	 * Get all core table keys (key and names)
	 *
	 * @return mixed|void
	 */
	public static function getAllDBTablesKeyList() {
		$table_key_names        = array();
		$table_key_names['log'] = esc_html__( 'Review Log Table', 'cbxscratingreview' );
		$table_key_names['avg'] = esc_html__( 'Review Avg Table', 'cbxscratingreview' );

		return apply_filters( 'cbxscratingreview_table_key_names', $table_key_names );
	}//end getAllDBTablesKeyList

	/**
	 * List all global option name with prefix cbxpoll_
	 */
	public static function getAllOptionNames() {
		global $wpdb;

		$prefix       = 'cbxscratingreview_';
		$option_names = $wpdb->get_results( "SELECT * FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'", ARRAY_A );

		return apply_filters( 'cbxscratingreview_option_names', $option_names );
	}//end getAllOptionNames

	/**
	 * Get Single review by review id
	 *
	 * @param int $review_id
	 *
	 * @return null|string
	 */
	public static function singleReview( $review_id = 0 ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';
		$table_users      = $wpdb->prefix . 'users';

		$review_id = intval( $review_id );

		$single_review = null;
		if ( $review_id > 0 ) {
			$join = $where_sql = $sql_select = '';
			$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

			$where_sql = $wpdb->prepare( "log.id=%d", $review_id );

			$sql_select = "SELECT log.*, users.user_email, users.display_name FROM $table_rating_log AS log";

			$single_review = $wpdb->get_row( "$sql_select $join WHERE $where_sql ", 'ARRAY_A' );
			if ( $single_review !== null ) {
				$single_review['attachment']  = maybe_unserialize( $single_review['attachment'] );
				$single_review['extraparams'] = maybe_unserialize( $single_review['extraparams'] );
			}

		}

		return $single_review;
	}//end singleReview

	/**
	 * Render single review by review id
	 *
	 * @param int $review_id
	 *
	 * @return string
	 */
	public static function singleReviewRender( $review_id = 0 ) {
		$single_review_html = '';

		if ( is_numeric( $review_id ) ) {
			$post_review = self::singleReview( intval( $review_id ) );

		} else {
			$post_review = $review_id;
		}


		if ( ! is_null( $post_review ) ) {
			//ob_start();

			$single_review_html = cbxscratingreview_get_template_html( 'rating-review-reviews-list-item.php', array(
				'review_id'   => $review_id,
				'post_review' => $post_review
			) );

			//$single_review_html = ob_get_contents();
			//ob_end_clean();
		}

		return $single_review_html;
	}//end singleReviewRender

	/**
	 * Render single review edit form by review id
	 *
	 * @param int $review_id
	 *
	 * @return string
	 */
	public static function singleReviewEditRender( $review_id = 0 ) {
		cbxscratingreview_AddJsCss();
		cbxscratingreview_AddRatingEditFormJsCss();

		$review_edit_form_html = '';

		if ( ! is_user_logged_in() ) {
			$review_edit_url = add_query_arg( 'review_id', $review_id, get_permalink() );

			$url = wp_login_url( $review_edit_url );
			if ( headers_sent() ) {
				return '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Please login to edit review', 'cbxscratingreview' ) . '</a>';
			} else {
				//redirect any request to {site_url()}/wp-login.php?redirect_to={$requested_url}
				wp_redirect( $url );
				exit;
			}


		}

		if ( is_numeric( $review_id ) ) {
			$post_review = self::singleReview( intval( $review_id ) );

		} else {
			$post_review = $review_id;
		}


		if ( ! is_null( $post_review ) ) {
			$user_id             = intval( get_current_user_id() );
			$post_review_user_id = intval( $post_review['user_id'] );
			$post_review_status  = intval( $post_review['status'] );


			if ( $user_id > 0 && $user_id != $post_review_user_id ) {
				return esc_html__( 'Are you cheating, huh ?', 'cbxscratingreview' );
			}

			if ( $post_review_status == 0 || $post_review_status == 1 ) {
				$review_edit_form_html = cbxscratingreview_get_template_html( 'rating-review-editform.php', array( 'review_id' => $review_id, 'user_id' => $user_id, 'post_review_status' => $post_review_status, 'post_review' => $post_review ) );

			} else {
				return esc_html__( 'Sorry, you can edit only pending or published review', 'cbxscratingreview' );
			}
		}

		return $review_edit_form_html;
	}//end singleReviewEditRender


	/**
	 * Review lists data of a Post
	 *
	 * @param int $post_id
	 * @param int $perpage
	 * @param int $page
	 * @param string $status
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return array|null|object
	 */
	public static function postReviews( $post_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $orderby = 'id', $order = 'DESC' ) {

		global $wpdb;
		$table_rating_log          = $wpdb->prefix . 'cbxscratingreview_log';
		$table_users               = $wpdb->prefix . 'users';
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		$orderby = ( $orderby == '' ) ? 'id' : $orderby;
		$order   = ( $order == '' ) ? 'DESC' : $order;

		$post_reviews = null;

		if ( $post_id > 0 ) {
			$join = $where_sql = $sql_select = '';
			$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

			$where_sql = $wpdb->prepare( "log.post_id=%d", $post_id );
			if ( $status != '' ) {
				$status = intval( $status );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
			}

			if ( $score != '' ) {

				if ( $score == - 1 || $score == - 2 ) {

					$positive_score = intval( $cbxscratingreview_setting->get_option( 'positive_score', 'cbxscratingreview_common_config', 4 ) );

					//positive or critial score
					if ( $score == - 1 ) {
						//all positives
						if ( $where_sql != '' ) {
							$where_sql .= ' AND ';
						}

						//$where_sql .= $wpdb->prepare( ' CEIL(log.score) >= %d', $positive_score );
						$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) >= %d', $positive_score );
					} else if ( $score == - 2 ) {
						//all criticals
						if ( $where_sql != '' ) {
							$where_sql .= ' AND ';
						}

						//$where_sql .= $wpdb->prepare( ' CEIL(log.score) < %d', $positive_score );
						$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) < %d', $positive_score );
					}
				} else {
					//regular score
					//$score = ceil( $score );
					$score = round( $score );

					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}

					//$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %f', $score );
					$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) = %d', $score );
				}
			}


			$sql_select = "SELECT log.*, users.user_email, users.display_name FROM $table_rating_log AS log";


			$sorting_order = " ORDER BY $orderby $order ";

			$limit_sql = '';
			if ( $perpage != '-1' ) {
				$perpage     = intval( $perpage );
				$start_point = ( $page * $perpage ) - $perpage;
				$limit_sql   = "LIMIT";
				$limit_sql   .= ' ' . $start_point . ',';
				$limit_sql   .= ' ' . $perpage;
			}


			$post_reviews = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );

			if ( $post_reviews !== null ) {
				foreach ( $post_reviews as &$post_review ) {
					$post_review['attachment']  = maybe_unserialize( $post_review['attachment'] );
					$post_review['extraparams'] = maybe_unserialize( $post_review['extraparams'] );
				}
			}
		}

		return $post_reviews;
	}//end postReviews


	/**
	 * All Reviews lists data
	 *
	 * @param int $perpage
	 * @param int $page
	 * @param string $status
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return array|null|object
	 */
	public static function allpostReviews( $perpage = 10, $page = 1, $status = '', $score = '', $orderby = 'id', $order = 'DESC' ) {

		global $wpdb;
		$table_rating_log          = $wpdb->prefix . 'cbxscratingreview_log';
		$table_users               = $wpdb->prefix . 'users';
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		//$post_id = intval( $post_id );
		//$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		$orderby = ( $orderby == '' ) ? 'id' : $orderby;
		$order   = ( $order == '' ) ? 'DESC' : $order;

		$post_reviews = null;

		//if ( $post_id > 0 ) {
		$join = $where_sql = $sql_select = '';
		$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

		//$where_sql = $wpdb->prepare( "log.post_id=%d", $post_id );
		if ( $status != '' ) {
			$status = intval( $status );
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
		}

		if ( $score != '' ) {

			if ( $score == - 1 || $score == - 2 ) {

				$positive_score = intval( $cbxscratingreview_setting->get_option( 'positive_score', 'cbxscratingreview_common_config', 4 ) );

				//positive or critial score
				if ( $score == - 1 ) {
					//all positives
					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}

					//$where_sql .= $wpdb->prepare( ' CEIL(log.score) >= %d', $positive_score );
					$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) >= %d', $positive_score );
				} else if ( $score == - 2 ) {
					//all criticals
					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}

					//$where_sql .= $wpdb->prepare( ' CEIL(log.score) < %d', $positive_score );
					$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) < %d', $positive_score );
				}
			} else {
				//regular score
				//$score = ceil( $score );
				$score = round( $score );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}

				//$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %f', $score );
				$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) = %d', $score );
			}
		}


		$sql_select = "SELECT log.*, users.user_email, users.display_name FROM $table_rating_log AS log";


		$sorting_order = " ORDER BY $orderby $order ";

		$limit_sql = '';
		if ( $perpage != '-1' ) {
			$perpage     = intval( $perpage );
			$start_point = ( $page * $perpage ) - $perpage;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $perpage;
		}


		$post_reviews = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );

		if ( $post_reviews !== null ) {
			foreach ( $post_reviews as &$post_review ) {
				$post_review['attachment']  = maybe_unserialize( $post_review['attachment'] );
				$post_review['extraparams'] = maybe_unserialize( $post_review['extraparams'] );
			}
		}

		//}

		return $post_reviews;
	}//end allpostReviews

	/**
	 * Review lists data of a Post by a User
	 *
	 * @param int $post_id
	 * @param int $user_id
	 * @param int $perpage
	 * @param int $page
	 * @param string $status
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return array|null|object|void
	 */
	/*public static function postReviewsByUser( $post_id = 0, $user_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $orderby = 'id', $order = 'DESC' ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
		$user_id = intval( $user_id );

		$post_review_by_user = null;
		if ( $post_id > 0 && $user_id > 0 ) {
			$post_review_by_user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_rating_log WHERE post_id = %d AND user_id = %d", $post_id, $user_id ), ARRAY_A );
		}

		return $post_review_by_user;
	}*/


	/**
	 * Render the review filter template
	 *
	 * @param int $perpage
	 * @param int $page
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return false|string
	 */
	public static function allPostReviewsFilterRender( $perpage = 10, $page = 1, $score = '', $orderby = 'id', $order = 'DESC' ) {
		/*global $current_user;
		$ok_to_render = false;

		$post_reviews_filter_html = '';

		//$post_id   = intval( $post_id );
		//$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
		//$post_type = get_post_type( $post_id );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();;
		} else {
			$user_id = 0;
		}

		if ( $user_id == 0 ) {
			$userRoles = array( 'guest' );
		} else {
			$userRoles = $current_user->roles;
		}

		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		//check if post type supported
		$post_types_supported = $cbxscratingreview_setting->get_option( 'post_types', 'cbxscratingreview_common_config', array() );


		if ( is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 ) {
			$ok_to_render = true;
		}
		//end post type check

		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		$total_reviews             = cbxscratingreview_totalReviewsCount( 1, $score);

		if ( $ok_to_render ) {

			$post_reviews_filter_html = cbxscratingreview_get_template_html('rating-review-all-reviews-list-filter.php', array());
		}

		return $post_reviews_filter_html;*/
	}//end allPostReviewsFilterRender


	/**
	 * Render the review filter template
	 *
	 * @param int $post_id
	 * @param int $perpage
	 * @param int $page
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 *
	 * @return false|string
	 */
	public static function postReviewsFilterRender( $post_id = 0, $perpage = 10, $page = 1, $score = '', $orderby = 'id', $order = 'DESC' ) {

		global $current_user;
		$ok_to_render = false;

		$post_reviews_filter_html = '';

		$post_id   = intval( $post_id );
		$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
		$post_type = get_post_type( $post_id );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();;
		} else {
			$user_id = 0;
		}

		if ( $user_id == 0 ) {
			$userRoles = array( 'guest' );
		} else {
			$userRoles = $current_user->roles;
		}

		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		//check if post type supported
		$post_types_supported = $cbxscratingreview_setting->get_option( 'post_types', 'cbxscratingreview_common_config', array() );


		if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
			$ok_to_render = true;
		}
		//end post type check

		//$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		$total_reviews             = cbxscratingreview_totalPostReviewsCount( $post_id, 1 );

		if ( $ok_to_render ) {
			/*
			ob_start();
			include( cbxscratingreview_locate_template( 'rating-review-reviews-list-filter.php' ) );
			$post_reviews_filter_html = ob_get_contents();
			ob_end_clean();
			*/

			$post_reviews_filter_html = cbxscratingreview_get_template_html( 'rating-review-reviews-list-filter.php', array(
				'total_reviews'             => $total_reviews,
				'cbxscratingreview_setting' => $cbxscratingreview_setting,
				'post_id'                   => $post_id,
				'perpage'                   => $perpage,
				'page'                      => $page,
				'score'                     => $score,
				'orderby'                   => $orderby,
				'order'                     => $order,
			) );
		}

		return $post_reviews_filter_html;
	}//end postReviewsFilterRender

	/**
	 * render Review lists data of a Post
	 *
	 * @param int $post_id
	 * @param int $perpage
	 * @param int $page
	 * @param string $status
	 * @param string $score
	 * @param string $orderby
	 * @param string $order
	 * @param bool $load_more
	 * @param bool $show_filter
	 *
	 * @return string
	 */
	public static function postReviewsRender( $post_id = 0, $perpage = 10, $page = 1, $status = '', $score = '', $orderby = 'id', $order = 'DESC', $load_more = false ) {
		global $current_user;
		$ok_to_render = false;

		$post_reviews_html = '';

		$post_id   = intval( $post_id );
		$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
		$post_type = get_post_type( $post_id );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();;
		} else {
			$user_id = 0;
		}

		if ( $user_id == 0 ) {
			$userRoles = array( 'guest' );
		} else {
			$userRoles = $current_user->roles;
		}

		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		//check if post type supported
		$post_types_supported = $cbxscratingreview_setting->get_option( 'post_types', 'cbxscratingreview_common_config', array() );


		if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
			$ok_to_render = true;
		}
		//end post type check


		//check if user role supported
		if ( $ok_to_render ) {
			$user_roles_rate = $cbxscratingreview_setting->get_option( 'user_roles_view', 'cbxscratingreview_common_config', array() );

			if ( ! is_array( $user_roles_rate ) ) {
				$user_roles_rate = array();
			}

			$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
			if ( sizeof( $intersectedRoles ) == 0 ) {
				$ok_to_render = false;
			}
		}

		//end check user role support

		if ( $ok_to_render ) {
			/*if ( $load_more == false ) {
				cbxscratingreview_AddJsCss();
			}*/


			$post_reviews_html = '';


			$post_reviews = self::postReviews( $post_id, $perpage, $page, $status, $score, $orderby, $order );
			if ( $load_more ) {

				$post_reviews_html .= cbxscratingreview_get_template_html( 'rating-review-reviews-list.php',
					array(
						'post_reviews' => $post_reviews,
						'post_id'      => $post_id,
						'score'        => $score,
						'perpage'      => $perpage,
						'page'         => $page,
						'orderby'      => $orderby,
						'order'        => $order,
					) );
			} else {
				if ( sizeof( $post_reviews ) > 0 ) {
					foreach ( $post_reviews as $index => $post_review ) {
						$post_reviews_html .= '<li id="cbxscratingreview_review_list_item_' . intval( $post_review['id'] ) . '"   class="' . apply_filters( 'cbxscratingreview_review_list_item_class', 'cbxscratingreview_review_list_item' ) . '">';
						$post_reviews_html .= cbxscratingreview_get_template_html( 'rating-review-reviews-list-item.php',
							array(
								'post_review' => $post_review,
								'post_id'     => $post_id,
								'score'       => $score,
								'perpage'     => $perpage,
								'page'        => $page,
								'orderby'     => $orderby,
								'order'       => $order,
							) );
						$post_reviews_html .= '</li>';
					}
				} else {
					$post_reviews_html .= '<li class="' . apply_filters( 'cbxscratingreview_review_list_item_class_notfound_class', 'cbxscratingreview_review_list_item cbxscratingreview_review_list_item_notfound' ) . '">';
					$post_reviews_html .= '<p class="no_reviews_found">' . esc_html__( 'No reviews yet!', 'cbxscratingreview' ) . '</p>';
					$post_reviews_html .= '</li>';
				}
			}

			if ( $load_more ) {
				$total_count       = cbxscratingreview_totalPostReviewsCount( $post_id, $status, $score );
				$maximum_pages     = ceil( $total_count / $perpage );
				$post_reviews_html .= cbxscratingreview_get_template_html( 'rating-review-reviews-list-more.php',
					array(
						'maximum_pages' => $maximum_pages,
						//'form'          => $form,
						'post_id'       => $post_id,
						'score'         => $score,
						//'form_id'       => $form_id,
						'perpage'       => $perpage,
						'page'          => $page,
						'orderby'       => $orderby,
						'order'         => $order,
					) );

			}


			return $post_reviews_html;
		}

		return $post_reviews_html;
	}//end postReviewsRender


	/**
	 * All Review lists data
	 *
	 * @param int $perpage
	 * @param int $page
	 * @param string $status
	 * @param string $orderby
	 * @param string $order
	 * @param string $score
	 *
	 * @return array|null|object
	 */
	public static function Reviews( $perpage = 10, $page = 1, $status = '', $orderby = 'id', $order = 'DESC', $score = '' ) {

		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';
		//$table_rating_avg_log = $wpdb->prefix . 'cbxscratingreview_log_avg';
		$table_users = $wpdb->prefix . 'users';


		$post_reviews = null;

		$join = $where_sql = $sql_select = '';

		$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";


		if ( $status != '' ) {
			$status = intval( $status );
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.status = %d', $status );
		}

		if ( $score != '' ) {

			//$score = ceil( $score );
			$score = round( $score );

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}

			//$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %d', $score );
			$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) = %d', $score );
		}

		$sql_select = "SELECT log.*, users.user_email, users.display_name FROM $table_rating_log AS log";

		$sorting_order = " ORDER BY $orderby $order ";

		$limit_sql = '';
		if ( $perpage != '-1' ) {
			$start_point = ( $page * $perpage ) - $perpage;
			$limit_sql   = "LIMIT";
			$limit_sql   .= ' ' . $start_point . ',';
			$limit_sql   .= ' ' . $perpage;
		}


		if ( $where_sql == '' ) {
			$where_sql = ' 1 ';
		}

		$post_reviews = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );


		return $post_reviews;
	}//end Reviews


	/**
	 * All Review lists data by a User
	 *
	 * @param int $user_id
	 * @param int $page
	 * @param int $perpage
	 * @param string $status
	 *
	 * @return array|null|object
	 */
	public static function ReviewsByUser( $user_id = 0, $perpage = 10, $page = 1, $status = '', $orderby = 'id', $order = 'DESC', $filter_score = '' ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';
		//$table_rating_avg_log = $wpdb->prefix . 'cbxscratingreview_log_avg';
		$table_users = $wpdb->prefix . 'users';

		$user_id = intval( $user_id );
		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;

		$post_reviews_by_user = null;
		if ( $user_id > 0 ) {


			$join = $where_sql = $sql_select = '';

			$join = " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";
			$join = apply_filters( 'cbxscratingreview_ReviewsByUser_join', $join, $user_id, $perpage, $page, $status, $orderby, $order );

			$where_sql = $wpdb->prepare( "log.user_id=%d", $user_id );

			if ( $status != '' ) {
				$status = intval( $status );
				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}
				$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
			}

			if ( $filter_score != '' ) {

				//$filter_score = ceil( $filter_score );
				$filter_score = round( $filter_score );

				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}

				//$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %s', $filter_score );
				$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) = %d', $filter_score );
			}

			$sql_select = "log.*, users.user_email, users.display_name";
			$sql_select = apply_filters( 'cbxscratingreview_ReviewsByUser_select', $sql_select, $user_id, $perpage, $page, $status, $orderby, $order );

			$sorting_order = " ORDER BY $orderby $order ";

			$limit_sql = '';
			if ( $perpage != '-1' ) {
				$start_point = ( $page * $perpage ) - $perpage;
				$limit_sql   = "LIMIT";
				$limit_sql   .= ' ' . $start_point . ',';
				$limit_sql   .= ' ' . $perpage;
			}

			$where_sql = apply_filters( 'cbxscratingreview_ReviewsByUser_where', $where_sql, $user_id, $perpage, $page, $status, $orderby, $order );

			if ( $where_sql == '' ) {
				$where_sql = ' 1 ';
			}


			$post_reviews_by_user = $wpdb->get_results( "SELECT $sql_select FROM $table_rating_log AS log $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );
		}

		return $post_reviews_by_user;
	}//end ReviewsByUser

	/**
	 * Total reviews count
	 *
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return int
	 */
	public static function totalReviewsCount( $status = '', $filter_score = '' ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';
		//$table_rating_avg_log = $wpdb->prefix . 'cbxscratingreview_log_avg';
		//$table_users          = $wpdb->prefix . 'users';


		$totalcount = 0;

		$join = $where_sql = $sql_select = '';


		if ( $status != '' ) {
			$status = intval( $status );
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
		}

		if ( $filter_score != '' ) {

			//$filter_score = ceil( $filter_score );
			$filter_score = round( $filter_score );

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}

			//$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %s', $filter_score );
			$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) = %d', $filter_score );
		}

		$sql_select = "SELECT COUNT(*) as totalcount FROM $table_rating_log AS log";

		if ( $where_sql == '' ) {
			$where_sql = ' 1 ';
		}

		$totalcount = $wpdb->get_var( "$sql_select WHERE $where_sql" );

		return intval( $totalcount );
	}//end totalReviewsCount

	/**
	 * Total reviews count
	 *
	 * @param string $post_type
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return int
	 */
	public static function totalReviewsCountPostType( $post_type = 'post', $status = '', $filter_score = '' ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';
		//$table_rating_avg_log = $wpdb->prefix . 'cbxscratingreview_log_avg';
		//$table_users          = $wpdb->prefix . 'users';


		$totalcount = 0;

		$join = $where_sql = $sql_select = '';

		$where_sql .= $wpdb->prepare( ' log.post_type = %s', $post_type );

		if ( $status != '' ) {
			$status = intval( $status );
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
		}

		if ( $filter_score != '' ) {
			//$filter_score = ceil( $filter_score );
			$filter_score = round( $filter_score );

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}

			//$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %s', $filter_score );
			$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) = %d', $filter_score );
		}

		$sql_select = "SELECT COUNT(*) as totalcount FROM $table_rating_log AS log";

		if ( $where_sql == '' ) {
			$where_sql = ' 1 ';
		}

		$totalcount = $wpdb->get_var( "$sql_select WHERE $where_sql" );

		return intval( $totalcount );
	}//end totalReviewsCountPostType

	/**
	 * Total reviews count by User
	 *
	 * @param int $user_id
	 * @param string $status
	 * @param string $filter_score
	 *
	 * @return int
	 */
	public static function totalReviewsCountByUser( $user_id = 0, $status = '', $filter_score = '' ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';
		//$table_rating_avg_log = $wpdb->prefix . 'cbxscratingreview_log_avg';
		//$table_users          = $wpdb->prefix . 'users';

		$user_id = intval( $user_id );

		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;
		if ( $user_id == 0 ) {
			return 0;
		}


		$totalcount = 0;

		$join = $where_sql = $sql_select = '';


		if ( $status != '' ) {
			$status = intval( $status );
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
		}

		if ( $filter_score != '' ) {

			//$filter_score = ceil( $filter_score );
			$filter_score = round( $filter_score );

			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}

			//$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %s', $filter_score );
			$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) = %d', $filter_score );
		}

		if ( $where_sql != '' ) {
			$where_sql .= ' AND ';
		}
		$where_sql .= $wpdb->prepare( ' log.user_id = %d', $user_id );

		$sql_select = "SELECT COUNT(*) as totalcount FROM $table_rating_log AS log";

		if ( $where_sql == '' ) {
			$where_sql = ' 1 ';
		}

		$totalcount = $wpdb->get_var( "$sql_select WHERE $where_sql" );

		return intval( $totalcount );
	}//end totalReviewsCountByUser


	/**
	 * Total reviews count of a Post
	 *
	 * @param int $post_id
	 * @param string $status
	 * @param string $score
	 *
	 * @return int
	 */
	public static function totalPostReviewsCount( $post_id = 0, $status = '', $score = '' ) {
		$reviews_count = 0;

		global $wpdb;
		$table_rating_log          = $wpdb->prefix . 'cbxscratingreview_log';
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		if ( $post_id == 0 ) {
			return 0;
		}

		$join = $where_sql = $sql_select = '';
		if ( $status != '' ) {
			$status = intval( $status );
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
		}

		if ( $score != '' ) {
			if ( $score == - 1 || $score == - 2 ) {
				$positive_score = intval( $cbxscratingreview_setting->get_option( 'positive_score', 'cbxscratingreview_common_config', 4 ) );
				//positive or critial score
				if ( $score == - 1 ) {
					//all positives
					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}

					//$where_sql .= $wpdb->prepare( ' CEIL(log.score) >= %d', $positive_score );
					$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) >= %d', $positive_score );
				} else if ( $score == - 2 ) {
					//all criticals
					if ( $where_sql != '' ) {
						$where_sql .= ' AND ';
					}

					//$where_sql .= $wpdb->prepare( ' CEIL(log.score) < %d', $positive_score );
					$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) < %d', $positive_score );
				}
			} else {

				//$score = ceil( $score );
				$score = round( $score );


				if ( $where_sql != '' ) {
					$where_sql .= ' AND ';
				}

				//$where_sql .= $wpdb->prepare( ' CEIL(log.score) = %f', $score );
				$where_sql .= $wpdb->prepare( ' ROUND(CAST( log.score AS DECIMAL(2,1))) = %d', $score );
			}

		}

		if ( $where_sql != '' ) {
			$where_sql .= ' AND ';
		}
		$where_sql .= $wpdb->prepare( ' log.post_id = %d', $post_id );

		if ( $where_sql == '' ) {
			$where_sql = ' 1 ';
		}

		$count = $wpdb->get_var( "SELECT COUNT(*) as count FROM $table_rating_log as log WHERE $where_sql" );
		if ( $count !== null ) {
			return intval( $count );
		} else {
			return 0;
		}
	}//end totalPostReviewsCount

	/**
	 * Total reviews count of a Post by a User
	 *
	 * @param int $post_id
	 * @param int $user_id
	 * @param string $status
	 *
	 * @return int
	 */
	public static function totalPostReviewsCountByUser( $post_id = 0, $user_id = 0, $status = '' ) {

		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		if ( $post_id == 0 ) {
			return 0;
		}

		$user_id = intval( $user_id );

		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;
		if ( $user_id == 0 ) {
			return 0;
		}

		$join = $where_sql = $sql_select = '';

		if ( $status != '' ) {
			$status = intval( $status );
			if ( $where_sql != '' ) {
				$where_sql .= ' AND ';
			}
			$where_sql .= $wpdb->prepare( ' log.status = %s', $status );
		}

		if ( $where_sql != '' ) {
			$where_sql .= ' AND ';
		}
		$where_sql .= $wpdb->prepare( ' log.post_id = %d', $post_id );

		if ( $where_sql != '' ) {
			$where_sql .= ' AND ';
		}
		$where_sql .= $wpdb->prepare( ' log.user_id = %d', $user_id );

		if ( $where_sql == '' ) {
			$where_sql = ' 1 ';
		}


		$count = $wpdb->get_var( "SELECT COUNT(*) as count FROM $table_rating_log as log WHERE $where_sql" );
		if ( $count !== null ) {
			return intval( $count );
		} else {
			return 0;
		}
	}//end totalPostReviewsCountByUser

	/**
	 * is this post rated previously
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function isPostRated( $post_id = 0 ) {

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		$is_post_rated = false;

		if ( $post_id == 0 ) {
			return false;
		}

		if ( $post_id > 0 ) {
			$is_post_rated = ( CBXSCRatingReviewHelper::totalPostReviewsCount( $post_id, '' ) > 0 ) ? true : false;
		}

		return $is_post_rated;
	}//end isPostRated


	/**
	 * is this post rated previously by user
	 *
	 * @param int $post_id
	 * @param int $user_id
	 *
	 * @return true/false
	 */
	public static function isPostRatedByUser( $post_id = 0, $user_id = 0 ) {

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		if ( $post_id == 0 ) {
			return false;
		}

		$user_id = intval( $user_id );

		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;
		if ( $user_id == 0 ) {
			return false;
		}


		$is_post_rated_by_user = false;
		if ( $post_id > 0 && $user_id > 0 ) {
			$is_post_rated_by_user = ( CBXSCRatingReviewHelper::totalPostReviewsCountByUser( $post_id, $user_id, '' ) > 0 ) ? true : false;
		}

		return $is_post_rated_by_user;
	}//end isPostRatedByUser

	/**
	 * Last Post rate date by a User
	 *
	 * @param int $post_id
	 * @param int $user_id
	 *
	 * @return  datetime
	 */
	public static function lastPostReviewDateByUser( $post_id = 0, $user_id = 0 ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		if ( $post_id == 0 ) {
			return null;
		}

		$user_id = intval( $user_id );

		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;
		if ( $user_id == 0 ) {
			return null;
		}

		$date = $wpdb->get_var( $wpdb->prepare( "SELECT date_created FROM $table_rating_log WHERE post_id = %d AND user_id=%d ORDER BY date_created DESC", $post_id, $user_id ) );

		return $date;
	}//end lastPostReviewDateByUser


	/**
	 * Last Post review by a User(a user can rate a post repeatedly, so the last one is fine)
	 *
	 * @param int $post_id
	 * @param int $user_id
	 *
	 * @return  object
	 */
	public static function lastPostReviewByUser( $post_id = 0, $user_id = 0 ) {
		global $wpdb;
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		if ( $post_id == 0 ) {
			return null;
		}

		$user_id = intval( $user_id );

		$user_id = ( $user_id == 0 ) ? intval( get_current_user_id() ) : $user_id;
		if ( $user_id == 0 ) {
			return null;
		}

		$single_review = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_rating_log WHERE post_id = %d AND user_id=%d ORDER BY date_created DESC", $post_id, $user_id ), ARRAY_A );

		if ( $single_review !== null ) {
			$single_review['attachment']  = maybe_unserialize( $single_review['attachment'] );
			$single_review['extraparams'] = maybe_unserialize( $single_review['extraparams'] );
		}

		return $single_review;
	}//end lastPostReviewDateByUser

	/**
	 * Average rating information of a post
	 *
	 * @param int $post_id
	 *
	 * @return null|string
	 */
	public static function postAvgRatingInfo( $post_id = 0 ) {
		global $wpdb;
		$table_rating_avg_log = $wpdb->prefix . 'cbxscratingreview_log_avg';

		$post_id = intval( $post_id );
		$post_id = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;

		$post_avg_rating = null;
		if ( $post_id > 0 ) {
			$post_avg_rating = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_rating_avg_log WHERE post_id = %d", $post_id ), ARRAY_A );
		}


		if ( is_null( $post_avg_rating ) ) {
			$post_avg_rating['avg_rating']               = 0;
			$post_avg_rating['total_count']              = 0;
			$post_avg_rating['id']                       = 0;
			$post_avg_rating['post_id']                  = $post_id;
			$post_avg_rating['date_created']             = null;
			$post_avg_rating['date_modified']            = null;
			$post_avg_rating['rating_stat']              = array();
			$post_avg_rating['rating_stat_scores']       = array();
			$post_avg_rating['positive_critical_scores'] = array();
		} else {
			$post_avg_rating['rating_stat'] = maybe_unserialize( $post_avg_rating['rating_stat'] );
			if ( isset( $post_avg_rating['rating_stat']['rating_stat_scores'] ) ) {
				$post_avg_rating['rating_stat_scores'] = maybe_unserialize( $post_avg_rating['rating_stat']['rating_stat_scores'] );
			} else {
				$post_avg_rating['rating_stat_scores'] = array();
			}

			if ( isset( $post_avg_rating['rating_stat']['positive_critical_scores'] ) ) {
				$post_avg_rating['positive_critical_scores'] = maybe_unserialize( $post_avg_rating['rating_stat']['positive_critical_scores'] );
			} else {
				$post_avg_rating['positive_critical_scores'] = array();
			}
		}


		return apply_filters( 'cbxscratingreview_post_avg', $post_avg_rating, $post_id );
	}//end postAvgRatingInfo

	/**
	 * Single avg rating info by avg id
	 *
	 * @param int $avg_id
	 *
	 * @return array|null|object|void
	 */
	public static function singleAvgRatingInfo( $avg_id = 0 ) {
		global $wpdb;
		$table_rating_avg = $wpdb->prefix . 'cbxscratingreview_log_avg';

		$avg_id = intval( $avg_id );

		$single_avg_rating = null;
		if ( $avg_id > 0 ) {
			$single_avg_rating = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_rating_avg WHERE id = %d", intval( $avg_id ) ), ARRAY_A );
			if ( $single_avg_rating !== null ) {
				$single_avg_rating['rating_stat'] = maybe_unserialize( $single_avg_rating['rating_stat'] );
				if ( isset( $single_avg_rating['rating_stat']['rating_stat_scores'] ) ) {
					$single_avg_rating['rating_stat_scores'] = maybe_unserialize( $single_avg_rating['rating_stat']['rating_stat_scores'] );
				} else {
					$single_avg_rating['rating_stat_scores'] = array();
				}

				if ( isset( $single_avg_rating['rating_stat']['positive_critical_scores'] ) ) {
					$single_avg_rating['positive_critical_scores'] = maybe_unserialize( $single_avg_rating['rating_stat']['positive_critical_scores'] );
				} else {
					$single_avg_rating['positive_critical_scores'] = array();
				}
			}

		}

		return $single_avg_rating;
	}//end singleAvgRatingInfo


	/**
	 * Add or update Avg calculation
	 *
	 * @param $review_info
	 *
	 */
	public static function calculatePostAvg( $review_info ) {
		global $wpdb;
		$table_rating_avg_log      = $wpdb->prefix . 'cbxscratingreview_log_avg';
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		//$positive_score            = intval( $cbxscratingreview_setting->get_option( 'positive_score', 'cbxscratingreview_common_config', 4 ) );

		$post_id         = intval( $review_info['post_id'] );
		$post_avg_rating = cbxscratingreview_postAvgRatingInfo( $post_id );
		$score           = $review_info['score'];

		//$is_positive_review = ( $score >= $positive_score ) ? 1 : 0;

		//$ceil_rating_score = ceil( $score );
		$ceil_rating_score = round( $score );

		//if fresh avg calculation
		if ( is_null( $post_avg_rating ) || intval( $post_avg_rating['id'] ) == 0 ) {

			$rating_stat = array();

			//rating score percentage calculation
			$rating_stat_scores                       = array();
			$rating_stat_scores[ $ceil_rating_score ] = array(
				'count'   => 1,
				'percent' => 100,
			);
			$rating_stat['rating_stat_scores']        = $rating_stat_scores;

			//positive and critical review count and percentage

			//$positive_critical_scores = array();

			/*if ( $is_positive_review ) {
				$positive_critical_scores['positive_count'] = 1;
				$positive_critical_scores['critical_count'] = 0;

				$positive_critical_scores['positive_percent'] = 100;
				$positive_critical_scores['critical_percent'] = 0;
			} else {
				$positive_critical_scores['positive_count'] = 0;
				$positive_critical_scores['critical_count'] = 1;

				$positive_critical_scores['positive_percent'] = 0;
				$positive_critical_scores['critical_percent'] = 100;
			}*/

			//$rating_stat['positive_critical_scores'] = $positive_critical_scores;

			$avg_insert_status = $wpdb->insert(
				$table_rating_avg_log,
				array(
					'post_id'      => $post_id,
					'post_type'    => get_post_type( $post_id ),
					'avg_rating'   => $score,
					'total_count'  => 1,
					'date_created' => current_time( 'mysql' ),
					'rating_stat'  => maybe_serialize( $rating_stat ),
				),
				array(
					'%d', // post_id,
					'%s', // post_type
					'%f', // avg_rating
					'%d', // total_count
					'%s', // date_created
					'%s', // rating_stat
				)
			);

			if ( $avg_insert_status !== false ) {
				//add post avg in post meta key
				update_post_meta( $post_id, '_cbxscratingreview_avg', $score );
				update_post_meta( $post_id, '_cbxscratingreview_total', 1 );
			}
			//send the currently added review as html
		} else {
			// update avg rating
			$new_total_rating_score = ( $post_avg_rating['avg_rating'] * $post_avg_rating['total_count'] ) + $score;
			$new_total_count        = intval( $post_avg_rating['total_count'] ) + 1;
			$new_rating_score       = number_format( ( $new_total_rating_score / $new_total_count ), 2 );


			$rating_stat = maybe_unserialize( $post_avg_rating['rating_stat'] );

			//rating score percentage calculation

			$rating_stat_scores = $rating_stat['rating_stat_scores'];

			if ( isset( $rating_stat_scores[ $ceil_rating_score ] ) ) {
				$new_count                                         = intval( $rating_stat_scores[ $ceil_rating_score ]['count'] ) + 1;
				$rating_stat_scores[ $ceil_rating_score ]['count'] = $new_count;
			} else {
				$rating_stat_scores[ $ceil_rating_score ]['count'] = 1;
			}

			//calculate percentage once again
			foreach ( $rating_stat_scores as $score => $count_percent ) {
				$rating_stat_scores[ $score ]['percent'] = number_format( ( intval( $rating_stat_scores[ $score ]['count'] ) / $new_total_count ) * 100, 2 );
			}

			$rating_stat['rating_stat_scores'] = $rating_stat_scores;


			//positive and critical review count and percentage
			//$positive_critical_scores = $rating_stat['positive_critical_scores'];

			/*if ( $is_positive_review ) {
				$positive_critical_scores['positive_count'] = intval( $positive_critical_scores['positive_count'] ) + 1;

			} else {
				$positive_critical_scores['critical_count'] = intval( $positive_critical_scores['critical_count'] ) + 1;
			}*/

			//$positive_critical_scores['positive_percent'] = number_format( ( intval( $positive_critical_scores['positive_count'] ) / $new_total_count ) * 100, 2 );;
			//$positive_critical_scores['critical_percent'] = number_format( ( intval( $positive_critical_scores['critical_count'] ) / $new_total_count ) * 100, 2 );;

			//$rating_stat['positive_critical_scores'] = $positive_critical_scores;

			$avg_update_status = $wpdb->update(
				$table_rating_avg_log,
				array(
					'avg_rating'    => $new_rating_score,
					'total_count'   => $new_total_count,
					'rating_stat'   => maybe_serialize( $rating_stat ),
					'date_modified' => current_time( 'mysql' ),
				),
				array( 'id' => $post_avg_rating['id'] ),
				array(
					'%f', // avg_rating
					'%d', // total_count
					'%s', // rating_stat
					'%s', // date_modifed
				),
				array(
					'%d',
				)
			);

			if ( $avg_update_status !== false ) {
				//update post avg in post meta key
				update_post_meta( $post_id, '_cbxscratingreview_avg', $new_rating_score );
				update_post_meta( $post_id, '_cbxscratingreview_total', $new_total_count );
			}
		}//end avg calculation
	}//end calculatePostAvg

	/**
	 * Readjust average after delete of any review or status change to any other state than published(1)
	 *
	 * @param array $review_info
	 *
	 * @return false|int|null
	 */
	public static function adjustPostwAvg( $review_info = array() ) {

		global $wpdb;
		$table_rating_avg          = $wpdb->prefix . 'cbxscratingreview_log_avg';
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		//$positive_score            = intval( $cbxscratingreview_setting->get_option( 'positive_score', 'cbxscratingreview_common_config', 4 ) );


		$post_id         = $review_info['post_id'];
		$post_avg_rating = cbxscratingreview_postAvgRatingInfo( $post_id );

		$avg_rating   = $post_avg_rating['avg_rating'];
		$total_count  = $post_avg_rating['total_count'];
		$total_rating = $avg_rating * $total_count;

		$new_total_count = $total_count - 1;


		$score = $review_info['score'];
		//$is_positive_review = ( $score >= $positive_score ) ? true : false;

		//$ceil_rating_score = ceil( $score );
		$ceil_rating_score = round( $score );

		// avg adjust
		$process_status = null;

		if ( $new_total_count != 0 ) {
			$new_total_rating = $total_rating - $score;
			$new_rating_score = number_format( ( $new_total_rating / $new_total_count ), 2 );

			$rating_stat = maybe_unserialize( $post_avg_rating['rating_stat'] );

			//rating score percentage calculation
			$rating_stat_scores = $rating_stat['rating_stat_scores'];

			if ( isset( $rating_stat_scores[ $ceil_rating_score ] ) ) {
				$new_count                                         = intval( $rating_stat_scores[ $ceil_rating_score ]['count'] ) - 1;
				$rating_stat_scores[ $ceil_rating_score ]['count'] = $new_count;
			}

			//calculate percentage once again
			foreach ( $rating_stat_scores as $score => $count_percent ) {
				$rating_stat_scores[ $score ]['percent'] = number_format( ( intval( $rating_stat_scores[ $score ]['count'] ) / $new_total_count ) * 100, 2 );
			}

			$rating_stat['rating_stat_scores'] = $rating_stat_scores;
			//end rating score percentage calculation


			//positive and critical review count and percentage
			//$positive_critical_scores = $rating_stat['positive_critical_scores'];

			/*if ( $is_positive_review ) {
				$positive_critical_scores['positive_count'] = intval( $positive_critical_scores['positive_count'] ) - 1;

			} else {
				$positive_critical_scores['critical_count'] = intval( $positive_critical_scores['critical_count'] ) - 1;
			}*/

			//$positive_critical_scores['positive_percent'] = number_format( ( intval( $positive_critical_scores['positive_count'] ) / $new_total_count ) * 100, 2 );;
			//$positive_critical_scores['critical_percent'] = number_format( ( intval( $positive_critical_scores['critical_count'] ) / $new_total_count ) * 100, 2 );;

			//$rating_stat['positive_critical_scores'] = $positive_critical_scores;
			//end positive and critical review count and percentage

			$process_status = $wpdb->update(
				$table_rating_avg,
				array(
					'avg_rating'    => $new_rating_score,
					'total_count'   => $new_total_count,
					'date_modified' => current_time( 'mysql' ),
					'rating_stat'   => maybe_serialize( $rating_stat )
				),
				array( 'id' => $post_avg_rating['id'] ),
				array(
					'%f', //avg_rating
					'%d', //total_count
					'%s', //date_modified
					'%s', //rating_stat
				),
				array( '%d' )
			);
			update_post_meta( $post_id, '_cbxscratingreview_avg', $new_rating_score );
			update_post_meta( $post_id, '_cbxscratingreview_total', $new_total_count );
		} else {
			// as no entry for this post so delete the entry from avg table
			$process_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_rating_avg WHERE id=%d", intval( $post_avg_rating['id'] ) ) );

			//update_post_meta( $post_id, '_cbxscratingreview_avg', 0 );
			//update_post_meta( $post_id, '_cbxscratingreview_total', 0 );

			delete_post_meta( $post_id, '_cbxscratingreview_avg' );
			delete_post_meta( $post_id, '_cbxscratingreview_total' );
		}


		return $process_status;
	}//end adjustPostwAvg

	/**
	 *
	 *
	 * @param array $review_info
	 *
	 * @return false|int|null
	 */
	public static function editPostwAvg( $new_status, $review_info, $review_info_old ) {
		//if not publish status we can ignore
		if ( $new_status != 1 ) {
			return;
		}

		$score     = $review_info['score'];
		$score_old = $review_info_old['score'];
		//if new score is same we can ignore
		if ( $score == $score_old ) {
			return;
		}


		global $wpdb;
		$table_rating_avg          = $wpdb->prefix . 'cbxscratingreview_log_avg';
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		//$positive_score            = intval( $cbxscratingreview_setting->get_option( 'positive_score', 'cbxscratingreview_common_config', 4 ) );


		$post_id         = $review_info['post_id'];
		$post_avg_rating = cbxscratingreview_postAvgRatingInfo( $post_id );

		$avg_rating   = $post_avg_rating['avg_rating'];
		$total_count  = $post_avg_rating['total_count'];
		$total_rating = $avg_rating * $total_count;

		$new_total_count = $total_count - 0;


		//$is_positive_review     = ( $score >= $positive_score ) ? true : false;
		//$is_positive_review_old = ( $score_old >= $positive_score ) ? true : false;

		//$ceil_rating_score     = ceil( $score );
		//$ceil_rating_score_old = ceil( $score );

		$ceil_rating_score     = round( $score );
		$ceil_rating_score_old = round( $score );

		// avg adjust
		$process_status = null;

		//if ( $new_total_count != 0 ) {
		$new_total_rating = $total_rating - $score_old + $score;
		$new_rating_score = number_format( ( $new_total_rating / $new_total_count ), 2 );

		$rating_stat = maybe_unserialize( $post_avg_rating['rating_stat'] );

		//rating score percentage calculation
		$rating_stat_scores = $rating_stat['rating_stat_scores'];

		//at first reduce old score count
		if ( isset( $rating_stat_scores[ $ceil_rating_score_old ] ) ) {
			$new_count                                             = intval( $rating_stat_scores[ $ceil_rating_score_old ]['count'] ) - 1;
			$rating_stat_scores[ $ceil_rating_score_old ]['count'] = $new_count;
		}

		//now add new score value
		if ( isset( $rating_stat_scores[ $ceil_rating_score ] ) ) {
			$new_count                                         = intval( $rating_stat_scores[ $ceil_rating_score ]['count'] ) + 1;
			$rating_stat_scores[ $ceil_rating_score ]['count'] = $new_count;
		}

		//calculate percentage once again
		foreach ( $rating_stat_scores as $score => $count_percent ) {
			$rating_stat_scores[ $score ]['percent'] = number_format( ( intval( $rating_stat_scores[ $score ]['count'] ) / $new_total_count ) * 100, 2 );
		}

		$rating_stat['rating_stat_scores'] = $rating_stat_scores;
		//end rating score percentage calculation


		//positive and critical review count and percentage
		//$positive_critical_scores = $rating_stat['positive_critical_scores'];

		//at first adjust for old score value
		/*if ( $is_positive_review_old ) {
			$positive_critical_scores['positive_count'] = intval( $positive_critical_scores['positive_count'] ) - 1;

		} else {
			$positive_critical_scores['critical_count'] = intval( $positive_critical_scores['critical_count'] ) - 1;
		}

		//now adjust for new score value
		if ( $is_positive_review ) {
			$positive_critical_scores['positive_count'] = intval( $positive_critical_scores['positive_count'] ) + 1;

		} else {
			$positive_critical_scores['critical_count'] = intval( $positive_critical_scores['critical_count'] ) + 1;
		}*/

		//$positive_critical_scores['positive_percent'] = number_format( ( intval( $positive_critical_scores['positive_count'] ) / $new_total_count ) * 100, 2 );;
		//$positive_critical_scores['critical_percent'] = number_format( ( intval( $positive_critical_scores['critical_count'] ) / $new_total_count ) * 100, 2 );;

		//$rating_stat['positive_critical_scores'] = $positive_critical_scores;
		//end positive and critical review count and percentage

		$process_status = $wpdb->update(
			$table_rating_avg,
			array(
				'avg_rating'    => $new_rating_score,
				'total_count'   => $new_total_count,
				'date_modified' => current_time( 'mysql' ),
				'rating_stat'   => maybe_serialize( $rating_stat )
			),
			array( 'id' => $post_avg_rating['id'] ),
			array(
				'%f', //avg_rating
				'%d', //total_count
				'%s', //date_modified
				'%s', //rating_stat
			),
			array( '%d' )
		);
		update_post_meta( $post_id, '_cbxscratingreview_avg', $new_rating_score );
		update_post_meta( $post_id, '_cbxscratingreview_total', $new_total_count );
		//}
		/*else {
			// as no entry for this post so delete the entry from avg table
			$process_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_rating_avg WHERE id=%d", intval( $post_avg_rating['id'] ) ) );

			update_post_meta( $post_id, '_cbxscratingreview_avg', 0 );
			update_post_meta( $post_id, '_cbxscratingreview_total', 0 );
		}*/


		return $process_status;
	}//end editPostwAvg


	/**
	 * @param $timestamp
	 *
	 * @return false|string
	 */
	public static function dateReadableFormat( $timestamp, $format = 'M j, Y' ) {
		$format = ( $format == '' ) ? 'M j, Y' : $format;

		return date( $format, strtotime( $timestamp ) );
	}//end dateReadableFormat


	/**
	 * HTML elements, attributes, and attribute values will occur in your output
	 * @return array
	 */
	public static function allowedHtmlTags() {
		$allowed_html_tags = array(
			'a'      => array(
				'href'  => array(),
				'title' => array(),
				//'class' => array(),
				//'data'  => array(),
				//'rel'   => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'ul'     => array(//'class' => array(),
			),
			'ol'     => array(//'class' => array(),
			),
			'li'     => array(//'class' => array(),
			),
			'strong' => array(),
			'p'      => array(
				//'class' => array(),
				//'data'  => array(),
				//'style' => array(),
			),
			'span'   => array(
				//					'class' => array(),
				//'style' => array(),
			),
		);

		return apply_filters( 'cbxscratingreview_allowed_html_tags', $allowed_html_tags );
	}//end allowedHtmlTags


	/**
	 * Get most rated posts
	 *
	 * @param int $limit
	 * @param string $orderby
	 * @param string $order
	 * @param string $type
	 *
	 * @return array|null|object
	 */
	public static function most_rated_posts( $perpage = 10, $orderby = 'avg_rating', $order = 'DESC', $type = 'post' ) {
		global $wpdb;
		$table_posts          = $wpdb->prefix . 'posts';
		$table_rating_avg_log = $wpdb->prefix . 'cbxscratingreview_log_avg';

		$join = $where_sql = $sql_select = '';

		//$join = " LEFT JOIN $table_posts AS posts ON posts.ID = avg_log.post_id ";

		/*if ( $user_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'post_author=%d', $user_id );
		}*/


		//if($post_type != ''){

		//AND 	posts.post_type             IN ( '" . implode( "','", wc_get_order_types() ) . "' )
		//if(is_array($post_types)) $post_types =  implode( ",", $post_types);

		$where_sql .= $wpdb->prepare( ' avg_log.post_type=%s ', $type ); //" avg_log.post_type IN('".$post_types."')";


		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		$sql_select = "SELECT avg_log.* FROM $table_rating_avg_log AS avg_log";

		$sorting_order = " ORDER BY $orderby $order ";

		$page        = 1;
		$start_point = ( $page * $perpage ) - $perpage;
		$limit_sql   = "LIMIT";
		$limit_sql   .= ' ' . $start_point . ',';
		$limit_sql   .= ' ' . $perpage;

		$posts = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );

		return $posts;
	}//end most_rated_posts


	/**
	 * Latest ratings
	 *
	 * @param int $perpage
	 * @param string $orderby
	 * @param string $order
	 * @param string $type
	 * @param int $user_id
	 *
	 * @return array|null|object
	 */
	public static function lastest_ratings( $perpage = 10, $orderby = 'id', $order = 'DESC', $type = 'post', $user_id = 0 ) {
		global $wpdb;
		//$table_users      = $wpdb->prefix . 'users';
		//$table_posts      = $wpdb->prefix . 'posts';
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';

		$join = $where_sql = $sql_select = '';

		//$join = " LEFT JOIN $table_posts AS posts ON posts.ID = log.post_id ";
		//$join .= " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

		if ( $user_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'user_id=%d', $user_id );
		}

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		$sql_select = "SELECT log.* FROM $table_rating_log AS log";

		$orderby       = 'id';
		$order         = 'DESC';
		$sorting_order = " ORDER BY $orderby $order ";

		$page = 1;

		$start_point = ( $page * $perpage ) - $perpage;
		$limit_sql   = "LIMIT";
		$limit_sql   .= ' ' . $start_point . ',';
		$limit_sql   .= ' ' . $perpage;

		$latest_ratings = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );

		return $latest_ratings;
	}//end lastest_ratings

	/**
	 * latest ratings of author posts
	 * @return array|null|object
	 */
	public static function authorpostlatestRatings( $perpage = 10, $user_id = 0 ) {
		global $wpdb;
		$table_users      = $wpdb->prefix . 'users';
		$table_posts      = $wpdb->prefix . 'posts';
		$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';

		$join = $where_sql = $sql_select = '';

		$join = " LEFT JOIN $table_posts AS posts ON posts.ID = log.post_id ";
		$join .= " LEFT JOIN $table_users AS users ON users.ID = log.user_id ";

		if ( $user_id !== 0 ) {
			$where_sql .= ( ( $where_sql != '' ) ? ' AND ' : '' ) . $wpdb->prepare( 'user_id=%d AND posts.post_author=%d', $user_id, $user_id );
		}

		if ( $where_sql == '' ) {
			$where_sql = '1';
		}

		$sql_select = "SELECT log.*, posts.post_title, users.display_name FROM $table_rating_log AS log";

		$orderby       = 'id';
		$order         = 'DESC';
		$sorting_order = " ORDER BY $orderby $order ";

		$page        = 1;
		$start_point = ( $page * $perpage ) - $perpage;
		$limit_sql   = "LIMIT";
		$limit_sql   .= ' ' . $start_point . ',';
		$limit_sql   .= ' ' . $perpage;

		$author_post_latest_ratings = $wpdb->get_results( "$sql_select $join WHERE $where_sql $sorting_order $limit_sql", 'ARRAY_A' );

		return $author_post_latest_ratings;
	}


	/**
	 * Render single post avg rating
	 *
	 * @param int $post_id
	 * @param int $show_score
	 * @param int $show_star
	 * @param int $show_chart
	 *
	 * @return false|string
	 */
	public static function postAvgRatingRender( $post_id = 0, $show_score = 1, $show_star = 1, $show_chart = 0 ) {

		global $current_user;
		$ok_to_render = false;

		$avg_rating_html = '';

		$post_id   = intval( $post_id );
		$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
		$post_type = get_post_type( $post_id );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();;
		} else {
			$user_id = 0;
		}

		if ( $user_id == 0 ) {
			$userRoles = array( 'guest' );
		} else {
			$userRoles = $current_user->roles;
		}

		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		//check if post type supported
		$post_types_supported = $cbxscratingreview_setting->get_option( 'post_types', 'cbxscratingreview_common_config', array() );


		//check if post type is supported
		if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
			$ok_to_render = true;
		}
		//end post type support check

		//check if user role supported
		if ( $ok_to_render ) {
			$user_roles_rate = $cbxscratingreview_setting->get_option( 'user_roles_view', 'cbxscratingreview_common_config', array() );

			if ( ! is_array( $user_roles_rate ) ) {
				$user_roles_rate = array();
			}

			$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
			if ( sizeof( $intersectedRoles ) == 0 ) {
				$ok_to_render = false;
			}
		}
		//end user role checking


		if ( $ok_to_render ) {
			cbxscratingreview_AddJsCss();

			$avg_rating_info = cbxscratingreview_postAvgRatingInfo( $post_id );

			$avg_rating_html = cbxscratingreview_get_template_html( 'rating-review-avg-rating.php',
				array(
					'avg_rating_info' => $avg_rating_info,
					'post_id'         => $post_id,
					'show_star'       => $show_star,
					'show_score'      => $show_score,
					'show_chart'      => $show_chart,

				) );

			return $avg_rating_html;
		}

		return $avg_rating_html;
	}//end postAvgRatingRender


	/**
	 * Render rating form
	 *
	 * @param int $post_id
	 * @param string $post_id
	 * @param string $scope
	 * @param int $guest_login
	 *
	 * @return string
	 */
	public static function reviewformRender( $post_id = 0, $title = '', $scope = 'shortcode', $guest_login = 1 ) {
		global $current_user;
		$ok_to_render = false;

		$rating_form_html = '';

		$post_id   = intval( $post_id );
		$post_id   = ( $post_id == 0 ) ? intval( get_the_ID() ) : $post_id;
		$post_type = get_post_type( $post_id );
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();;
		} else {
			$user_id = 0;
		}

		if ( $user_id == 0 ) {
			$userRoles = array( 'guest' );
		} else {
			$userRoles = $current_user->roles;
		}

		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		//check if post type supported
		$post_types_supported = $cbxscratingreview_setting->get_option( 'post_types', 'cbxscratingreview_common_config', array() );


		if ( $post_id > 0 && is_array( $post_types_supported ) && sizeof( $post_types_supported ) > 0 && in_array( $post_type, $post_types_supported ) ) {
			$ok_to_render = true;
		}
		//end checking post types

		//check if user role supported
		if ( $ok_to_render ) {
			$user_roles_rate = $cbxscratingreview_setting->get_option( 'user_roles_rate', 'cbxscratingreview_common_config', array() );

			if ( ! is_array( $user_roles_rate ) ) {
				$user_roles_rate = array();
			}


			$intersectedRoles = array_intersect( $user_roles_rate, $userRoles );
			if ( sizeof( $intersectedRoles ) == 0 ) {
				$ok_to_render = false;
			}
		}//end checking user role support


		//now check if the user rated before
		if ( $ok_to_render == true ) {
			$user_rated_before = cbxscratingreview_isPostRatedByUser( $post_id, $user_id );
			if ( $user_rated_before ) {
				$ok_to_render = false;

				//display user's previous rating

				$user_last_review_html = '';
				$user_last_review      = CBXSCRatingReviewHelper::lastPostReviewByUser( $post_id, $user_id );
				if ( $user_last_review !== null ) {
					$user_last_review_html .= '<p class="cbxscratingreview_user_last_review">' . sprintf( esc_html__( 'You already rated & reviewed this. You rated %s', 'cbxscratingreview' ), number_format_i18n( $user_last_review['score'], 2 ) . '/5' ) . '</p>';
					$rating_form_html      .= apply_filters( 'cbxscratingreview_user_last_reviewed_this_post', $user_last_review_html, $post_id, $user_last_review );
				}

			}

			//still put option if we want to allow repeat review
			$ok_to_render = apply_filters( 'cbxscratingreview_allow_repeat_review', $ok_to_render, $user_rated_before, $user_id, $post_id );
		}


		if ( apply_filters( 'cbxscratingreview_render', $ok_to_render, $post_id, $post_type ) ) {
			cbxscratingreview_AddJsCss();
			cbxscratingreview_AddRatingFormJsCss();


			$rating_form_html .= cbxscratingreview_get_template_html( 'rating-review-form.php',
				array(
					'post_id' => $post_id,
					'title'   => $title,
					'scope'   => $scope
				) );

			return $rating_form_html;
		}


		if ( $user_id == 0 && $guest_login ) {
			cbxscratingreview_AddJsCss();
			cbxscratingreview_AddRatingFormJsCss();


			$rating_form_html .= cbxscratingreview_get_template_html( 'rating-review-form-guest.php',
				array(
					'post_id' => $post_id,
					'title'   => $title,
					'scope'   => $scope
				) );

			return $rating_form_html;
		}

		return $rating_form_html;
	}//end reviewformRender


	/**
	 * Char Length check  thinking utf8 in mind
	 *
	 * @param $text
	 *
	 * @return int
	 */
	public static function utf8_compatible_length_check( $text ) {
		if ( seems_utf8( $text ) ) {
			$length = mb_strlen( $text );
		} else {
			$length = strlen( $text );
		}

		return $length;
	}

	/**
	 * Setup a post object and store the original loop item so we can reset it later
	 *
	 * @param obj $post_to_setup The post that we want to use from our custom loop
	 */
	public static function setup_admin_postdata( $post_to_setup ) {

		//only on the admin side
		if ( is_admin() ) {

			//get the post for both setup_postdata() and to be cached
			global $post;

			//only cache $post the first time through the loop
			if ( ! isset( $GLOBALS['post_cache'] ) ) {
				$GLOBALS['post_cache'] = $post;
			}

			//setup the post data as usual
			$post = $post_to_setup;
			setup_postdata( $post );
		}
	}


	/**
	 * Reset $post back to the original item
	 *
	 */
	public static function wp_reset_admin_postdata() {

		//only on the admin and if post_cache is set
		if ( is_admin() && ! empty( $GLOBALS['post_cache'] ) ) {

			//globalize post as usual
			global $post;

			//set $post back to the cached version and set it up
			$post = $GLOBALS['post_cache'];
			setup_postdata( $post );

			//cleanup
			unset( $GLOBALS['post_cache'] );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links Plugin Action links.
	 *
	 * @return  array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=cbxscratingreviewsettings' ) . '" aria-label="' . esc_attr__( 'View settings', 'cbxscratingreview' ) . '">' . esc_html__( 'Settings', 'cbxscratingreview' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}//end plugin_action_links

	/**
	 * Get all the pages
	 *
	 * @return array page names with key value pairs
	 */
	public static function get_pages() {
		$pages         = get_pages();
		$pages_options = array();
		if ( $pages ) {
			foreach ( $pages as $page ) {
				$pages_options[ $page->ID ] = $page->post_title;
			}
		}

		return $pages_options;
	}//end get_pages

	/**
	 * Review toolbar render
	 *
	 * @param $post_review
	 *
	 * @return string
	 */
	public static function reviewToolbarRender( $post_review ) {
		$rating_review_toolbar_html = cbxscratingreview_get_template_html( 'rating-review-reviews-list-item-toolbar.php', array( 'post_review' => $post_review ) );

		return $rating_review_toolbar_html;
	}//end reviewToolbarRender

	/**
	 * render single review delete button
	 *
	 * @param array $post_review
	 *
	 * @return string
	 */
	public static function reviewDeleteButtonRender( $post_review = array() ) {
		cbxscratingreview_AddJsCss();

		$report_form_html = '';
		if ( is_array( $post_review ) && sizeof( $post_review ) > 0 ) {
			$report_form_html = cbxscratingreview_get_template_html( 'rating-review-review-delete-button.php', array( 'post_review' => $post_review ) );
		}

		return $report_form_html;
	}//end reviewReportButtonRender

	/**
	 * Bookmark login form
	 *
	 * @return array
	 */
	public static function guest_login_forms() {
		$forms = array();

		$forms['wordpress'] = esc_html__( 'WordPress Core Login Form', 'cbxscratingreview' );

		return apply_filters( 'cbxscratingreview_guest_login_forms', $forms );
	}//end guest_login_forms

	/**
	 * Add utm params to any url
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	public static function url_utmy( $url = '' ) {
		if ( $url == '' ) {
			return $url;
		}

		$url = add_query_arg( array(
			'utm_source'   => 'plgsidebarinfo',
			'utm_medium'   => 'plgsidebar',
			'utm_campaign' => 'wpfreemium',
		), $url );

		return $url;
	}//end url_utmy


}//end class CBXSCRatingReviewHelper