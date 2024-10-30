<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXSCRatingReview
 * @subpackage CBXSCRatingReview/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CBXSCRatingReview
 * @subpackage CBXSCRatingReview/public
 * @author     Sabuj Kundu <sabuj@codeboxr.com>
 */
class CBXSCRatingReview_Public {

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

	public $setting;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
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

		$this->setting     = new CBXSCRatingReviewSettings();
	}//end of constructor

	/**
	 * Init all shortcodes
	 */
	public function init_shortcodes() {
		//show rating form
		add_shortcode( 'cbxscratingreview_reviewform', array( $this, 'reviewform_shortcode' ) );

		//show rating form avg by post id
		add_shortcode( 'cbxscratingreview_postavgrating', array( $this, 'postavgrating_shortcode' ) );

		//show ratings by post id
		add_shortcode( 'cbxscratingreview_postreviews', array( $this, 'postreviews_shortcode' ) );
		add_shortcode( 'cbxscratingreview_userdashboard', array( $this, 'userdashboard_shortcode' ) );

		//show single review by rating id from shortcode or from $_GET
		add_shortcode( 'cbxscratingreview_singlereview', array( $this, 'singlereview_shortcode' ) );

		//edit review by review id from shortcode or from $_GET
		add_shortcode( 'cbxscratingreview_editreview', array( $this, 'editreview_shortcode' ) );


		add_shortcode( 'cbxscratingreviewlratings', array( $this, 'cbxscratingreviewlratings_shortcode' ) ); //latest ratings
		add_shortcode( 'cbxscratingreviewmrposts', array( $this, 'cbxscratingreviewmrposts_shortcode' ) ); //most rated posts
	}//end init_shortcodes

	/**
	 * Init all widgets
	 */
	public function init_register_widget() {
		//latest ratings widget
		if ( ! class_exists( 'CBXSCRatingReviewLRatingsWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/classic_widgets/cbxscratingreviewlratings/class-cbxscratingreviewlratings-widget.php';  //latest ratings
		}
		register_widget( 'CBXSCRatingReviewLRatingsWidget' );

		//most rated post widget
		if ( ! class_exists( 'CBXSCRatingReviewMRPostsWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/classic_widgets/cbxscratingreviewmrposts/class-cbxscratingreviewmrposts-widget.php'; //most rated posts
		}
		register_widget( 'CBXSCRatingReviewMRPostsWidget' );

	}//end init_register_widget

	/**
	 * Rating Form shortcode callback
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function reviewform_shortcode( $atts ) {
		global $post;

		$post_id = intval( $post->ID );

		//$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		$atts = shortcode_atts(
			array(
				'title'       => '',//leave empty to ignore
				'post_id'     => $post_id,
				'guest_login' => 1, //1 = show note for guest to login, 0 = don't display
				'scope'       => 'shortcode',
			), $atts, 'cbxscratingreview_reviewform' );

		$post_id     = isset( $atts['post_id'] ) ? intval( $atts['post_id'] ) : 0;
		$title       = isset( $atts['title'] ) ? sanitize_text_field( $atts['title'] ) : '';
		$scope       = isset( $atts['scope'] ) ? sanitize_text_field( $atts['scope'] ) : 'shortcode';
		$guest_login = isset( $atts['guest_login'] ) ? intval( $atts['guest_login'] ) : 1;


		if ( $post_id > 0 && function_exists( 'cbxscratingreview_reviewformRender' ) ) {
			return cbxscratingreview_reviewformRender( $post_id, $title, $scope, $guest_login );
		}

		return '';
	}//end reviewform_shortcode

	/**
	 * Post avg info shortcode call back
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function postavgrating_shortcode( $atts ) {
		global $post;
		$post_id = intval( $post->ID );

		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		$atts                      = shortcode_atts(
			array(
				'post_id'    => $post_id,
				'show_score' => 1, //show rating score
				'show_star'  => 1, //show rating
				'show_chart' => 0, //show chart
			), $atts, 'cbxscratingreview_postavgrating' );

		$post_id    = isset( $atts['post_id'] ) ? intval( $atts['post_id'] ) : 0;
		$show_score = isset( $atts['show_score'] ) ? intval( $atts['show_score'] ) : 1;
		$show_star  = isset( $atts['show_star'] ) ? intval( $atts['show_star'] ) : 1;
		$show_chart = isset( $atts['show_chart'] ) ? intval( $atts['show_chart'] ) : 0;

		if ( function_exists( 'cbxscratingreview_postAvgRatingRender' ) ) {
			return cbxscratingreview_postAvgRatingRender( $post_id, $show_score, $show_star, $show_chart );
		}

		return '';
	}//end postavgrating_shortcode


	/**
	 * Post reviews shortcode callback
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function postreviews_shortcode( $atts ) {
		global $post;
		$post_id = intval( $post->ID );

		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		$perpage_default           = intval( $cbxscratingreview_setting->get_option( 'default_per_page', 'cbxscratingreview_common_config', 10 ) );
		$show_filter_default       = intval( $cbxscratingreview_setting->get_option( 'show_review_filter', 'cbxscratingreview_common_config', 1 ) );

		$atts = shortcode_atts(
			array(
				'post_id'     => $post_id,
				//'orderby'     => 'id', //id, total_count, post_id
				'orderby'     => 'id', //id, post_id
				'order'       => 'DESC', //DESC, ASC,
				'score'       => '',
				'perpage'     => $perpage_default,
				'show_filter' => $show_filter_default,
				'show_more'   => 1,
			), $atts, 'cbxscratingreview_postreviews' );


		$post_id     = isset( $atts['post_id'] ) ? intval( $atts['post_id'] ) : 0;
		$orderby     = isset( $atts['orderby'] ) ? esc_attr( $atts['orderby'] ) : 'id';
		$order       = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
		$score       = isset( $atts['score'] ) ? esc_attr( $atts['score'] ) : '';
		$perpage     = isset( $atts['perpage'] ) ? intval( $atts['perpage'] ) : $perpage_default;
		$show_filter = isset( $atts['show_filter'] ) ? intval( $atts['show_filter'] ) : $show_filter_default;
		$show_more   = isset( $atts['show_more'] ) ? intval( $atts['show_more'] ) : 1;




		$output = '';

		if ( $show_filter ) {
			if ( function_exists( 'cbxscratingreview_postReviewsFilterRender' ) ) {
				$output .= cbxscratingreview_postReviewsFilterRender( $post_id, $perpage, 1, $score, $orderby, $order );
			}
		}

		if ( function_exists( 'cbxscratingreview_postReviewsRender' ) ) {
			$output .= cbxscratingreview_postReviewsRender( $post_id, $perpage, 1, $score, $orderby, $order, $show_more );
		}

		return $output;
	}//end postreviews_shortcode


	/**
	 * User rating frontend dashboard
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	public function userdashboard_shortcode( $atts ) {
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		$atts                      = shortcode_atts(
			array(
				'orderby' => 'id', //id, post_id, post_type, score, status
				'order'   => 'DESC', //DESC, ASC,
				'perpage' => 20,
			), $atts, 'cbxscratingreview_userdashboard' );

		$orderby = isset( $atts['orderby'] ) ? esc_attr( $atts['orderby'] ) : 'id'; // //id, post_id, score, status
		$order   = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
		$perpage = isset( $atts['perpage'] ) ? absint( $atts['perpage'] ) : 20;


		cbxscratingreview_AddJsCss();

		$dashboard_html = cbxscratingreview_get_template_html( 'rating-review-user-dashboard.php', array(
				'cbxscratingreview_setting' => $cbxscratingreview_setting,
				'orderby'                   => $orderby,
				'order'                     => $order,
				'perpage'                   => $perpage
			)
		);

		return $dashboard_html;

	}//end userdashboard_shortcode

	/**
	 * Single review render using shortcode
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function singlereview_shortcode( $atts ) {
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		$atts                      = shortcode_atts(
			array(
				'review_id' => 0,
			), $atts, 'cbxscratingreview_singlereview' );

		$single_review_html = '';

		if ( function_exists( 'cbxscratingreview_singleReviewRender' ) ) {
			//at first take from shortcode
			$review_id = isset( $atts['review_id'] ) ? intval( $atts['review_id'] ) : 0;
			if ( $review_id == 0 ) {
				//now take from url
				$review_id = isset( $_GET['review_id'] ) ? intval( $_GET['review_id'] ) : 0;
			}

			if ( $review_id > 0 ) {
				$post_review = cbxscratingreview_singleReview( $review_id );

				$post_id    = intval( $post_review['post_id'] );
				$post_title = get_the_title( $post_id );
				$post_link  = get_permalink( $post_id );

				$single_review_html .= '<p>' . esc_html__( 'Reviewed', 'cbxscratingreview' ) . ' : <a target="_blank" href="' . esc_url( $post_link ) . '">' . esc_attr( $post_title ) . '</a></p>';

				$single_review_html .= '<ul class="cbxscratingreview_review_list_items">';
				$single_review_html .= '<li id="cbxscratingreview_review_list_item_' . intval( $review_id ) . '" class="' . apply_filters( 'cbxscratingreview_review_list_item_class', 'cbxscratingreview_review_list_item' ) . '">';

				$single_review_html .= cbxscratingreview_singleReviewRender( $post_review );

				$single_review_html .= '</li>';
				$single_review_html .= '</ul>';
			} else {
				$single_review_html .= '<div class="alert alert-danger" role="alert">' . esc_html__( 'Sorry, review not found or unpublished', 'cbxscratingreview' ) . '</div>';
			}
		}

		return $single_review_html;

	}//end singlereview_shortcode

	/**
	 * Single review edit render using shortcode
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function editreview_shortcode( $atts ) {
		$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		$atts                      = shortcode_atts(
			array(
				'review_id' => 0,
			), $atts, 'cbxscratingreview_editreview' );

		$single_review_edit_html = '';

		if ( function_exists( 'cbxscratingreview_singleReviewEditRender' ) ) {

			$review_id = isset( $atts['review_id'] ) ? intval( $atts['review_id'] ) : 0;
			if ( $review_id == 0 ) {
				//now take from url
				$review_id = isset( $_GET['review_id'] ) ? intval( $_GET['review_id'] ) : 0;
			}

			if ( $review_id > 0 ) {
				$single_review_edit_html = cbxscratingreview_singleReviewEditRender( $review_id );
			} else {
				$single_review_edit_html .= '<div class="alert alert-danger" role="alert">' . esc_html__( 'Sorry, review not found', 'cbxscratingreview' ) . '</div>';
			}
		}

		return $single_review_edit_html;
	}//end editreview_shortcode


	/**
	 * Shortcode callback for most rated post
	 */
	public function cbxscratingreviewmrposts_shortcode( $atts ) {
		//$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
		$atts = shortcode_atts(
			array(
				'title'   => esc_html__( 'Most Rated Posts', 'cbxscratingreview' ),
				'scope'   => 'shortcode',
				'limit'   => 10,
				'orderby' => 'avg_rating', //avg_rating, total_count, post_id
				'order'   => 'DESC', //DESC, ASC,
				'type'    => 'post'
			), $atts, 'cbxscratingreviewmrposts' );

		$title   = isset( $atts['title'] ) ? sanitize_text_field( $atts['title'] ) : esc_html__( 'Most Rated Posts', 'cbxscratingreview' );
		$limit   = intval( $atts['limit'] );
		$orderby = isset( $atts['orderby'] ) ? $atts['orderby'] : 'avg_rating';
		$order   = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
		$type    = isset( $atts['type'] ) ? esc_attr( $atts['type'] ) : 'post';
		$scope   = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? esc_attr( $atts['scope'] ) : 'shortcode';


		cbxscratingreview_AddJsCss();

		$data_posts = cbxscratingreview_most_rated_posts( $limit, $orderby, $order, $type ); //variable name $data_posts  is important for template files


		$content = cbxscratingreview_get_template_html( 'widgets/most_rated_posts.php', array(
				//'cbxscratingreview_setting' => $cbxscratingreview_setting,
				'title'      => $title,
				'scope'      => $scope,
				'data_posts' => $data_posts,
				'limit'      => $limit,
				'orderby'    => $orderby,
				'order'      => $order,
				'type'       => $type
			)
		);

		return $content;
	}//end cbxscratingreviewmrposts_shortcode

	/**
	 * Shortcode callback for latest ratings
	 */
	public function cbxscratingreviewlratings_shortcode( $atts ) {
		//$cbxscratingreview_setting = new CBXSCRatingReviewSettings();

		$atts = shortcode_atts(
			array(
				'title'   => esc_html__( 'Latest Ratings', 'cbxscratingreview' ),
				'scope'   => 'shortcode',
				'limit'   => 10,
				'orderby' => 'id', //id, score, post_id
				'order'   => 'DESC',
				'type'    => 'post',
			), $atts, 'cbxscratingreviewlratings' );


		$title   = isset( $atts['title'] ) ? sanitize_text_field( $atts['title'] ) : esc_html__( 'Latest Ratings', 'cbxscratingreview' );
		$limit   = isset( $atts['limit'] ) ? intval( $atts['limit'] ) : 10;
		$orderby = isset( $atts['orderby'] ) ? $atts['orderby'] : 'id'; //id, score, post_id
		$order   = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
		$type    = isset( $atts['type'] ) ? esc_attr( $atts['type'] ) : 'post';
		$scope   = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? esc_attr( $atts['scope'] ) : 'shortcode';


		cbxscratingreview_AddJsCss();

		$data_posts = cbxscratingreview_lastest_ratings( $limit, $orderby, $order, $type ); //variable name $data_posts  is important for template files


		$content = cbxscratingreview_get_template_html( 'widgets/latest_ratings.php', array(
			//'cbxscratingreview_setting' => $cbxscratingreview_setting,
			'title'      => $title,
			'scope'      => $scope,
			'data_posts' => $data_posts,
			'limit'      => $limit,
			'orderby'    => $orderby,
			'order'      => $order,
			'type'       => $type
		) );

		return $content;
	}//end cbxscratingreviewlratings_shortcode

	/**
	 * Shortcode callback for user latest ratings
	 */
	/*public function cbxscratingreviewlratings_by_user_shortcode( $atts ) {
		//global $post;

		$atts = shortcode_atts(
			array(
				'scope'     => 'shortcode',
				'per_page'  => 10,
				'page'      => 1,
				'load_more' => 0,
				'orderby'   => 'avg_rating',
				'order'     => 'DESC',
			), $atts, 'cbxscratingreview' );

		$scope     = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? esc_attr( $atts['scope'] ) : 'shortcode';
		$per_page  = intval( $atts['per_page'] );
		$page      = intval( $atts['page'] );
		$load_more = isset( $atts['load_more'] ) ? intval( $atts['load_more'] ) : 0;
		$user_id   = isset( $atts['user_id'] ) ? intval( $atts['user_id'] ) : 0;

		if ( $user_id <= 0 ) {
			$user_id = intval( get_current_user_id() );
		}

		if ( $user_id > 0 ) {
			$user_latest_ratings = cbxscratingreview_cbxscratingreviewlratings( $per_page, $user_id );
			if ( sizeof( $user_latest_ratings ) > 0 ) {
				// Display the latest ratings link
				ob_start();
				include( plugin_dir_path( __FILE__ ) . '../widgets/userlatestratings-widget/views/public.php' );
				$content = ob_get_contents();
				ob_end_clean();

				return $content;
			}
		}

		return '';
	}*/


	/**
	 * Shortcode callback for author most rated post
	 */
	/*public function cbxscratingreview_authormostratedpost_shortcode( $atts ) {
		//global $post;

		$atts = shortcode_atts(
			array(
				'scope'   => 'shortcode',
				'limit'   => 10,
				'orderby' => 'avg_rating',
				'order'   => 'DESC',
			), $atts, 'cbxscratingreview' );

		$scope   = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? esc_attr( $atts['scope'] ) : 'shortcode';
		$limit   = intval( $atts['limit'] );
		$orderby = isset( $atts['orderby'] ) ? esc_attr( $atts['orderby'] ) : 'avg_rating';
		$order   = isset( $atts['order'] ) ? esc_attr( $atts['order'] ) : 'DESC';
		$user_id = isset( $atts['user_id'] ) ? intval( $atts['user_id'] ) : 0;

		if ( $user_id <= 0 ) {
			$user_id = intval( get_current_user_id() );
		}

		if ( $user_id > 0 ) {
			$author_most_rated_post = cbxscratingreview_mostRatedPosts( $limit, $orderby, $order, $user_id );
			if ( sizeof( $author_most_rated_post ) > 0 ) {
				// Display the most rated post link
				ob_start();
				include( plugin_dir_path( __FILE__ ) . '../widgets/authormostratedpost-widget/views/public.php' );
				$content = ob_get_contents();
				ob_end_clean();

				return $content;
			}
		}

		return '';
	}*/


	/**
	 * Shortcode callback for author post latest ratings
	 */
	/*public function cbxscratingreview_authorpostlatestratings_shortcode( $atts ) {
		//global $post;

		$atts = shortcode_atts(
			array(
				'scope'   => 'shortcode',
				'limit'   => 10,
				'orderby' => 'avg_rating',
				'order'   => 'DESC',
			), $atts, 'cbxscratingreview' );

		$scope   = ( isset( $atts['scope'] ) && $atts['scope'] != '' ) ? esc_attr( $atts['scope'] ) : 'shortcode';
		$limit   = intval( $atts['limit'] );
		$user_id = isset( $atts['user_id'] ) ? intval( $atts['user_id'] ) : 0;

		if ( $user_id <= 0 ) {
			$user_id = intval( get_current_user_id() );
		}

		if ( $user_id > 0 ) {
			$latest_ratings = cbxscratingreview_authorpostlatestRatings( $limit, $user_id );
			if ( sizeof( $latest_ratings ) > 0 ) {
				// Display the latest ratings link
				ob_start();
				include( plugin_dir_path( __FILE__ ) . '../widgets/latestratings-widget/views/public.php' );
				$content = ob_get_contents();
				ob_end_clean();

				return $content;
			}
		}

		return '';
	}*/

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$cbxscratingreview_setting = $this->setting;


		do_action( 'cbxscratingreview_reg_styles_before' );

		$ratingform_css_dep = array();
		$common_css_dep     = array();


		wp_register_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . '../assets/css/ui-lightness/jquery-ui.min.css', array(), $this->version );
		wp_register_style( 'jquery-cbxscratingreview-raty', plugin_dir_url( __FILE__ ) . '../assets/css/jquery.cbxscratingreview_raty.css', array(), $this->version, 'all' );


		$ratingform_css_dep[] = 'jquery-ui';
		$ratingform_css_dep[] = 'jquery-cbxscratingreview-raty';

		$common_css_dep[] = 'jquery-cbxscratingreview-raty';

		$common_css_dep = apply_filters( 'cbxscratingreview_common_css_dep', $common_css_dep );

		do_action( 'cbxscratingreview_reg_styles' );


		wp_register_style( 'cbxscratingreview-public', plugin_dir_url( __FILE__ ) . '../assets/css/cbxscratingreview-public.css', $common_css_dep, $this->version, 'all' );
		$ratingform_css_dep[] = 'cbxscratingreview-public';

		$ratingform_css_dep = apply_filters( 'cbxscratingreview_ratingform_css_dep', $ratingform_css_dep );

		wp_register_style( 'cbxscratingreview-ratingform', plugin_dir_url( __FILE__ ) . '../assets/css/cbxscratingreview-ratingform.css', $ratingform_css_dep, $this->version, 'all' );

		do_action( 'cbxscratingreview_reg_styles_after' );

	}//end enqueue_styles

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		$cbxscratingreview_setting = $this->setting;

		$require_headline = intval( $cbxscratingreview_setting->get_option( 'require_headline', 'cbxscratingreview_common_config', 1 ) );
		$require_comment  = intval( $cbxscratingreview_setting->get_option( 'require_comment', 'cbxscratingreview_common_config', 1 ) );
		$half_rating      = intval( $cbxscratingreview_setting->get_option( 'half_rating', 'cbxscratingreview_common_config', 0 ) );


		$ratingform_js_dep     = array();
		$ratingeditform_js_dep = array();
		$common_js_dep         = array();


		do_action( 'cbxscratingreview_reg_scripts_before' );

		//common for everywhere
		wp_register_script( 'cbxscratingreview-events', plugin_dir_url( __FILE__ ) . '../assets/js/cbxscratingreview-events.js', array(), $this->version, true );
		wp_register_script( 'jquery-cbxscratingreview-raty', plugin_dir_url( __FILE__ ) . '../assets/js/jquery.cbxscratingreview_raty.js', array( 'jquery' ), $this->version, true );


		wp_register_script( 'jquery-validate', plugin_dir_url( __FILE__ ) . '../assets/vendors/jquery-validate/jquery.validate' . $suffix . '.js', array( 'jquery' ), $this->version, true );

		$ratingform_js_dep[] = 'cbxscratingreview-events';
		$ratingform_js_dep[] = 'jquery';
		$ratingform_js_dep[] = 'jquery-ui-datepicker';
		$ratingform_js_dep[] = 'jquery-cbxscratingreview-raty';
		$ratingform_js_dep[] = 'jquery-validate';

		$ratingeditform_js_dep[] = 'cbxscratingreview-events';
		$ratingeditform_js_dep[] = 'jquery';
		$ratingeditform_js_dep[] = 'jquery-ui-datepicker';
		$ratingeditform_js_dep[] = 'jquery-cbxscratingreview-raty';
		$ratingeditform_js_dep[] = 'jquery-validate';


		$common_js_dep[] = 'cbxscratingreview-events';
		$common_js_dep[] = 'jquery';
		$common_js_dep[] = 'jquery-cbxscratingreview-raty';


		do_action( 'cbxscratingreview_reg_scripts' );

		$common_js_dep = apply_filters( 'cbxscratingreview_common_js_dep', $common_js_dep );

		wp_register_script( 'cbxscratingreview-public', plugin_dir_url( __FILE__ ) . '../assets/js/cbxscratingreview-public.js', $common_js_dep, $this->version, true );

		$ratingform_js_dep[]     = 'cbxscratingreview-public';
		$ratingeditform_js_dep[] = 'cbxscratingreview-public';

		$ratingform_js_dep     = apply_filters( 'cbxscratingreview_ratingform_js_dep', $ratingform_js_dep );
		$ratingeditform_js_dep = apply_filters( 'cbxscratingreview_editform_js_dep', $ratingeditform_js_dep );

		wp_register_script( 'cbxscratingreview-ratingform', plugin_dir_url( __FILE__ ) . '../assets/js/cbxscratingreview-ratingform.js', $ratingform_js_dep, $this->version, true );
		wp_register_script( 'cbxscratingreview-ratingeditform', plugin_dir_url( __FILE__ ) . '../assets/js/cbxscratingreview-ratingform-frontedit.js', $ratingeditform_js_dep, $this->version, true );


		// Localize the script with new data
		$cbxscratingreview_public_ratingform_js_vars = apply_filters( 'cbxscratingreview_public_ratingform_js_vars', array(
			'ajaxurl'              => admin_url( 'admin-ajax.php' ),
			'nonce'                => wp_create_nonce( 'cbxscratingreview' ),
			'rating'               => array(
				'half_rating' => $half_rating,
				'cancelHint'  => esc_html__( 'Cancel this rating!', 'cbxscratingreview' ),
				'hints'       => CBXSCRatingReviewHelper::ratingHints(),
				'noRatedMsg'  => esc_html__( 'Not rated yet!', 'cbxscratingreview' ),
				'img_path'    => apply_filters( 'cbxscratingreview_star_image_url', CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/stars/' )
			),
			'validation'           => array(
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
				'require_comment'  => $require_comment,
			),
			'sort_text'            => esc_html__( 'Drag and Sort', 'cbxscratingreview' )
		) );

		$cbxscratingreview_public_common_js_vars = apply_filters( 'cbxscratingreview_public_common_js_vars', array(
			'ajaxurl'               => admin_url( 'admin-ajax.php' ),
			'nonce'                 => wp_create_nonce( 'cbxscratingreview' ),
			'rating'                => array(
				'cancelHint' => esc_html__( 'Cancel this rating!', 'cbxscratingreview' ),
				'hints'      => CBXSCRatingReviewHelper::ratingHints(),
				'noRatedMsg' => esc_html__( 'Not rated yet!', 'cbxscratingreview' ),
				'img_path'   => apply_filters( 'cbxscratingreview_star_image_url', CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/stars/' )
			),
			'no_reviews_found_html' => '<li class="' . apply_filters( 'cbxscratingreview_review_list_item_class_notfound_class', 'cbxscratingreview_review_list_item cbxscratingreview_review_list_item_notfound' ) . '"><p class="no_reviews_found">' . esc_html__( 'No reviews yet!', 'cbxscratingreview' ) . '</p>
				</li>',
			'load_more_text'        => esc_html__( 'Load More', 'cbxscratingreview' ),
			'load_more_busy_text'   => esc_html__( 'Loading next page ...', 'cbxscratingreview' ),
			'delete_confirm'        => esc_html__( 'Are you sure to delete your review, this processs can not be undone ?', 'cbxscratingreview' ),
			'delete_text'           => esc_html__( 'Delete', 'cbxscratingreview' ),
			'delete_error'          => esc_html__( 'Sorry! Review delete failed!', 'cbxscratingreview' ),
		) );

		wp_localize_script( 'cbxscratingreview-public', 'cbxscratingreview_public', $cbxscratingreview_public_common_js_vars );
		wp_localize_script( 'cbxscratingreview-ratingform', 'cbxscratingreview_ratingform', $cbxscratingreview_public_ratingform_js_vars );
		wp_localize_script( 'cbxscratingreview-ratingeditform', 'cbxscratingreview_ratingeditform', $cbxscratingreview_public_ratingform_js_vars );

		do_action( 'cbxscratingreview_reg_scripts_after' );

	}//end enqueue_scripts


	/**
	 * Add all common js and css needed for review and rating
	 */
	public function enqueue_common_js_css_rating() {
		do_action( 'cbxscratingreview_enq_common_js_css_before' );

		// enqueue styles
		wp_enqueue_style( 'jquery-cbxscratingreview-raty' );


		// enqueue scripts
		wp_enqueue_script( 'cbxscratingreview-events' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-cbxscratingreview-raty' );

		do_action( 'cbxscratingreview_enq_common_js_css' );

		wp_enqueue_style( 'cbxscratingreview-public' );
		wp_enqueue_script( 'cbxscratingreview-public' );

		do_action( 'cbxscratingreview_enq_common_js_css_after' );

	}//end enqueue_common_js_css_rating

	/**
	 * Add all js and css needed for review submit form
	 */
	public function enqueue_ratingform_js_css_rating() {
		$cbxscratingreview_setting = $this->setting;

		do_action( 'cbxscratingreview_enq_ratingform_js_css_before' );

		//enqueue styles
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'jquery-cbxscratingreview-raty' );


		//enqueue script
		wp_enqueue_script( 'cbxscratingreview-events' );
		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-cbxscratingreview-raty' );
		wp_enqueue_script( 'jquery-validate' );


		do_action( 'cbxscratingreview_enq_ratingform_js_css' );

		wp_enqueue_style( 'cbxscratingreview-public' );
		wp_enqueue_style( 'cbxscratingreview-ratingform' );

		wp_enqueue_script( 'cbxscratingreview-public' );
		wp_enqueue_script( 'cbxscratingreview-ratingform' );

		do_action( 'cbxscratingreview_enq_ratingform_js_css_after' );
	}//end enqueue_ratingform_js_css_rating

	/**
	 * Add all js and css needed for review edit form
	 */
	public function enqueue_ratingeditform_js_css_rating() {
		$cbxscratingreview_setting = $this->setting;

		do_action( 'cbxscratingreview_enq_ratingeditform_js_css_before' );


		//enqueue styles
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'jquery-cbxscratingreview-raty' );


		//enqueue script
		wp_enqueue_script( 'cbxscratingreview-events' );
		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-cbxscratingreview-raty' );
		wp_enqueue_script( 'jquery-validate' );


		do_action( 'cbxscratingreview_enq_ratingeditform_js_css' );

		//note: form always load the common public js and js

		wp_enqueue_style( 'cbxscratingreview-public' );
		wp_enqueue_style( 'cbxscratingreview-ratingform' );

		wp_enqueue_script( 'cbxscratingreview-public' );
		wp_enqueue_script( 'cbxscratingreview-ratingeditform' );

		do_action( 'cbxscratingreview_enq_ratingeditform_js_css_after' );
	}//end enqueue_ratingeditform_js_css_rating


	/**
	 * Ajax handler for post reviews load more
	 */
	public function post_more_reviews_ajax_load() {
		check_ajax_referer( 'cbxscratingreview', 'security' );

		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$perpage = isset( $_POST['perpage'] ) ? intval( $_POST['perpage'] ) : 0;
		$page    = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
		$orderby = isset( $_POST['orderby'] ) ? esc_attr( $_POST['orderby'] ) : 'id';
		$order   = isset( $_POST['order'] ) ? esc_attr( $_POST['order'] ) : 'DESC';
		//$status  = isset( $_POST['status'] ) ? esc_attr( $_POST['status'] ) : ''; //this should be 1 for current regular implementation
		$score = isset( $_POST['score'] ) ? esc_attr( $_POST['score'] ) : '';

		$load_more = isset( $_POST['load_more'] ) ? intval( $_POST['load_more'] ) : 0;

		//filter must be set false

		$output = CBXSCRatingReviewHelper::postReviewsRender( $post_id, $perpage, $page, 1, $score, $orderby, $order, $load_more );

		echo wp_json_encode( $output );

		wp_die();
	}//end post_more_reviews_ajax_load

	/**
	 * Ajax handler for post reviews load more
	 */
	public function post_filter_reviews_ajax_load() {
		check_ajax_referer( 'cbxscratingreview', 'security' );

		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$perpage = isset( $_POST['perpage'] ) ? intval( $_POST['perpage'] ) : 0;
		$page    = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
		$orderby = isset( $_POST['orderby'] ) ? esc_attr( $_POST['orderby'] ) : 'id';
		$order   = isset( $_POST['order'] ) ? esc_attr( $_POST['order'] ) : 'DESC';
		//$status  = isset( $_POST['status'] ) ? esc_attr( $_POST['status'] ) : ''; //this should be 1 for current regular implementation
		$score = isset( $_POST['score'] ) ? esc_attr( $_POST['score'] ) : '';

		//filter must be set false
		//$output = cbxscratingreview_postReviewsRender( $post_id, $perpage, $page, 1, $score, $orderby, $order, false, false );
		$output_list = CBXSCRatingReviewHelper::postReviewsRender( $post_id, $perpage, $page, 1, $score, $orderby, $order, 0 );

		$total_count   = cbxscratingreview_totalPostReviewsCount( $post_id, 1, $score );
		$maximum_pages = ceil( $total_count / $perpage );

		$show_readmore = ( $maximum_pages > 1 ) ? 1 : 0;

		$output = array(
			'list_html' => $output_list,
			'orderby'   => $orderby,
			'order'     => $order,
			'score'     => $score,
			'load_more' => $show_readmore,
			'maxpage'   => $maximum_pages,
			'total'     => $total_count,
		);

		echo wp_json_encode( $output );

		wp_die();
	}//end post_more_reviews_ajax_load

	/**
	 * Frontend Review rating entry via ajax
	 */
	public function review_rating_frontend_submit() {
		check_ajax_referer( 'cbxscratingreview', 'security' );

		$cbxscratingreview_setting = $this->setting;

		$show_headline    = intval( $cbxscratingreview_setting->get_option( 'show_headline', 'cbxscratingreview_common_config', 1 ) );
		$show_comment     = intval( $cbxscratingreview_setting->get_option( 'show_comment', 'cbxscratingreview_common_config', 1 ) );
		$require_headline = intval( $cbxscratingreview_setting->get_option( 'require_headline', 'cbxscratingreview_common_config', 1 ) );
		$require_comment  = intval( $cbxscratingreview_setting->get_option( 'require_comment', 'cbxscratingreview_common_config', 1 ) );

		$default_status = intval( $cbxscratingreview_setting->get_option( 'default_status', 'cbxscratingreview_common_config', 1 ) );

		$submit_data = $_REQUEST['cbxscratingreview_ratingForm'];

		$validation_errors = $success_data = $return_response = $response_data_arr = array();
		$ok_to_process     = 0;
		$success_msg_class = $success_msg_info = '';


		$user_id = intval( get_current_user_id() );
		if ( is_user_logged_in() ) {
			$post_id = isset( $submit_data['post_id'] ) ? intval( $submit_data['post_id'] ) : 0;

			$rating_score = isset( $submit_data['score'] ) ? floatval( $submit_data['score'] ) : 0;


			$review_headline = isset( $submit_data['headline'] ) ? sanitize_text_field( $submit_data['headline'] ) : '';
			$review_comment  = isset( $submit_data['comment'] ) ? wp_kses( $submit_data['comment'], CBXSCRatingReviewHelper::allowedHtmlTags() ) : '';


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

		$validation_errors = apply_filters( 'cbxscratingreview_review_entry_validation_errors', $validation_errors, $post_id, $submit_data );

		if ( sizeof( $validation_errors ) > 0 ) {

		} else {

			$default_status = apply_filters( 'cbxscratingreview_review_review_default_status', $default_status, $post_id );

			$ok_to_process = 1;

			global $wpdb;

			$table_rating_log = $wpdb->prefix . 'cbxscratingreview_log';

			$user_rated_before = cbxscratingreview_isPostRatedByUser();

			$multiple_review = false;
			$multiple_review = apply_filters( 'cbxscratingreview_review_review_repeat', $multiple_review, $post_id, $user_id );


			$log_insert_status = false;

			if ( ( $user_rated_before == false ) || $multiple_review ) {
				$attachment = array();


				$attachment = apply_filters( 'cbxscratingreview_review_entry_attachment', $attachment, $post_id, $submit_data );

				$extraparams = array();
				$extraparams = apply_filters( 'cbxscratingreview_review_entry_extraparams', $extraparams, $post_id, $submit_data );


				// insert rating log
				$data = array(
					'post_id'      => $post_id,
					'post_type'    => get_post_type( $post_id ),
					'user_id'      => $user_id,
					'score'        => $rating_score,
					'headline'     => $review_headline,
					'comment'      => $review_comment,
					'extraparams'  => maybe_serialize( $extraparams ),
					'attachment'   => maybe_serialize( $attachment ),
					'status'       => $default_status,
					'date_created' => current_time( 'mysql' )
				);

				$data = apply_filters( 'cbxscratingreview_review_entry_data', $data, $post_id, $submit_data );

				$data_format = array(
					'%d', // post_id
					'%s', // post_type
					'%d', // user_id
					'%f', // score
					'%s', // headline
					'%s', // comment
					'%s', // extraparams
					'%s', // attachment
					'%s', // status
					'%s', // date_created
				);

				$data_format = apply_filters( 'cbxscratingreview_review_entry_data_format', $data_format, $post_id, $submit_data );

				$log_insert_status = $wpdb->insert(
					$table_rating_log,
					$data,
					$data_format
				);

				if ( $log_insert_status != false ) {
					$new_review_id = $wpdb->insert_id;

					do_action( 'cbxscratingreview_review_entry_just_success', $post_id, $submit_data, $new_review_id );

					$success_msg_class = 'success';
					$success_msg_info  = sprintf( esc_html__( 'Thank you for your rating and review. You rated %s', 'cbxscratingreview' ), number_format_i18n( $rating_score, 2 ) . '/5' );

					if ( $default_status != 1 ) {
						$success_msg_info .= '<br/>';
						$success_msg_info .= esc_html__( 'It will be published after admin approval and you will be notified.', 'cbxscratingreview' );

						$success_msg_info = apply_filters( 'cbxscratingreview_review_entry_success_info', $success_msg_info, 'success' );
					}

					$review_info = cbxscratingreview_singleReview( $new_review_id );

					$response_data_arr['user_id']         = $review_info['user_id'];
					$response_data_arr['rating_id']       = $review_info['id'];
					$response_data_arr['rating_score']    = $review_info['score'] . '/5';
					$response_data_arr['headline']        = $review_info['headline'];
					$response_data_arr['comment']         = $review_info['comment'];
					$response_data_arr['date_created']    = CBXSCRatingReviewHelper::dateReadableFormat( $review_info['date_created'] );
					$response_data_arr['new_review_info'] = $review_info;

					//return last review with the response data
					if ( $default_status == 1 ) {
						$response_data_arr['review_html'] = '<li id="cbxscratingreview_review_list_item_' . intval( $new_review_id ) . '" class="' . apply_filters( 'cbxscratingreview_review_list_item_class', 'cbxscratingreview_review_list_item' ) . '">' . cbxscratingreview_singleReviewRender( $review_info ) . '</li>';
					}


					//if published then calculate avg
					if ( intval( $default_status ) == 1 ) {
						do_action( 'cbxscratingreview_review_publish', $review_info );
					}

					$response_data_arr = apply_filters( 'cbxscratingreview_review_entry_response_data', $response_data_arr, $post_id, $submit_data, $review_info );


					//send email to admin
					$nr_admin_status = $cbxscratingreview_setting->get_option( 'nr_admin_status', 'cbxscratingreview_email_alert', 'on' );
					if ( $nr_admin_status == 'on' ) {
						$nr_admin_email_status = CBXSCRatingReviewMailAlert::sendNewReviewAdminEmailAlert( $review_info );
					}

					//send email to user
					$nr_user_status = $cbxscratingreview_setting->get_option( 'nr_user_status', 'cbxscratingreview_email_alert', 'on' );
					if ( $nr_user_status == 'on' ) {
						$nr_user_email_status = CBXSCRatingReviewMailAlert::sendNewReviewUserEmailAlert( $review_info );
					}

					do_action( 'cbxscratingreview_review_entry_success', $post_id, $submit_data, $review_info );

				}
			} else {
				$success_msg_class = 'warning';
				$success_msg_info  = esc_html__( 'Sorry! You already rated this or multiple reviews is not possible.', 'cbxscratingreview' );
				$success_msg_info  = apply_filters( 'cbxscratingreview_review_entry_success_info', $success_msg_info, 'failed' );
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
	}//end review_rating_submit

	/**
	 * Ajax review edit from frontend
	 */
	public function review_rating_front_edit() {
		check_ajax_referer( 'cbxscratingreview', 'security' );

		$cbxscratingreview_setting = $this->setting;

		$show_headline    = intval( $cbxscratingreview_setting->get_option( 'show_headline', 'cbxscratingreview_common_config', 1 ) );
		$show_comment     = intval( $cbxscratingreview_setting->get_option( 'show_comment', 'cbxscratingreview_common_config', 1 ) );
		$require_headline = intval( $cbxscratingreview_setting->get_option( 'require_headline', 'cbxscratingreview_common_config', 1 ) );
		$require_comment  = intval( $cbxscratingreview_setting->get_option( 'require_comment', 'cbxscratingreview_common_config', 1 ) );

		$default_status = intval( $cbxscratingreview_setting->get_option( 'default_status', 'cbxscratingreview_common_config', 1 ) );


		$submit_data = $_REQUEST['cbxscratingreview_ratingForm'];

		$validation_errors = $success_data = $return_response = $response_data_arr = array();
		$ok_to_process     = 0;
		$success_msg_class = $success_msg_info = '';

		//$review_photos     = array();


		if ( is_user_logged_in() ) {

			$user_id   = intval( get_current_user_id() );
			$post_id   = isset( $submit_data['post_id'] ) ? intval( $submit_data['post_id'] ) : 0;
			$review_id = isset( $submit_data['log_id'] ) ? intval( $submit_data['log_id'] ) : 0;


			//$enable_location = apply_filters( 'cbxscratingreview_enable_location_form', $enable_location, $post_id );


			$rating_score    = isset( $submit_data['score'] ) ? floatval( $submit_data['score'] ) : 0;
			$review_headline = isset( $submit_data['headline'] ) ? sanitize_text_field( $submit_data['headline'] ) : '';
			$review_comment  = isset( $submit_data['comment'] ) ? wp_kses( $submit_data['comment'], CBXSCRatingReviewHelper::allowedHtmlTags() ) : '';


			if ( $review_id <= 0 ) {
				$validation_errors['top_errors']['log_id']['log_id_wrong'] = esc_html__( 'Sorry! Invalid review id. Please check and try again.', 'cbxscratingreview' );
			} else {
				$review_info_old = cbxscratingreview_singleReview( $review_id );
				if ( $review_info_old == null ) {
					$validation_errors['top_errors']['log']['log_wrong'] = esc_html__( 'Sorry! Invalid review. Please check and try again.', 'cbxscratingreview' );
				} else {
					$review_info_old_user_id = intval( $review_info_old['user_id'] );
					if ( $review_info_old_user_id != $user_id ) {
						$validation_errors['top_errors']['log']['user_wrong'] = esc_html__( 'Are you cheating, huh ?', 'cbxscratingreview' );
					}
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

		$validation_errors = apply_filters( 'cbxscratingreview_review_frontedit_validation_errors', $validation_errors, $post_id, $submit_data );

		if ( sizeof( $validation_errors ) > 0 ) {

		} else {


			$old_status = $review_info_old['status'];


			$ok_to_process = 1;

			global $wpdb;

			$table_rating_log  = $wpdb->prefix . 'cbxscratingreview_log';
			$log_update_status = false;

			$attachment = maybe_unserialize( $review_info_old['attachment'] );

			$attachment = apply_filters( 'cbxscratingreview_review_frontedit_attachment', $attachment, $post_id, $submit_data, $review_id );

			$extraparams = maybe_unserialize( $review_info_old['extraparams'] );
			$extraparams = apply_filters( 'cbxscratingreview_review_frontedit_extraparams', $extraparams, $post_id, $submit_data, $review_id );


			// edit rating log
			$data = array(
				'score'         => $rating_score,
				'headline'      => $review_headline,
				'comment'       => $review_comment,
				'extraparams'   => maybe_serialize( $extraparams ),
				'attachment'    => maybe_serialize( $attachment ),
				'mod_by'        => $user_id,
				'date_modified' => current_time( 'mysql' )
			);

			$data = apply_filters( 'cbxscratingreview_review_frontedit_data', $data, $post_id, $submit_data, $review_id );

			$data_format = array(
				'%f', // score
				'%s', // headline
				'%s', // comment
				'%s', // extraparams
				'%s', // attachment
				'%d', // mod_by
				'%s', // date_modified
			);

			$data_format = apply_filters( 'cbxscratingreview_review_frontedit_data_format', $data_format, $post_id, $submit_data, $review_id );

			$data_where = array(
				'id'      => $review_id,
				'post_id' => $post_id,
				'user_id' => $user_id
			);

			$data_where_format = array(
				'%d', // id
				'%d', // post_id
				'%d' // user_id
			);

			$data_where        = apply_filters( 'cbxscratingreview_review_frontedit_where', $data_where, $post_id, $submit_data, $review_id );
			$data_where_format = apply_filters( 'cbxscratingreview_review_frontedit_where_format', $data_where_format, $post_id, $submit_data, $review_id );

			$log_update_status = $wpdb->update(
				$table_rating_log,
				$data,
				$data_where,
				$data_format,
				$data_where_format
			);

			if ( $log_update_status != false ) {
				$review_id = $review_id;

				do_action( 'cbxscratingreview_review_frontedit_just_success', $post_id, $submit_data, $review_id );


				$success_msg_class = 'success';

				$success_msg_info = esc_html__( 'Review updated successfully', 'cbxscratingreview' );

				$success_msg_info = apply_filters( 'cbxscratingreview_review_frontedit_success_info', $success_msg_info, 'success' );

				$review_info = cbxscratingreview_singleReview( $review_id );

				//simple update without status change
				do_action( 'cbxscratingreview_review_update_without_status', $old_status, $review_info, $review_info_old );


				$response_data_arr = apply_filters( 'cbxscratingreview_review_frontedit_response_data', $response_data_arr, $post_id, $submit_data, $review_info );


				do_action( 'cbxscratingreview_review_frontedit_success', $post_id, $submit_data, $review_info, $review_info_old );

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
	}//end review_rating_front_edit

	/**
	 * if review edited in publish mode
	 *
	 * @param $new_status
	 * @param $review_info
	 * @param $review_info_old
	 */
	public function cbxscratingreview_review_update_without_status_adjust_postavg( $new_status, $review_info, $review_info_old ) {
		//if status is edited in puhlished mode
		if ( $new_status == 1 ) {
			CBXSCRatingReviewHelper::editPostwAvg( $new_status, $review_info, $review_info_old );
		}
	}//end cbxscratingreview_review_update_without_status_adjust_postavg

	/**
	 * Review Toolbar render
	 */
	public function cbxscratingreview_single_review_toolbar( $post_review ) {
		echo cbxscratingreview_reviewToolbarRender( $post_review );
	}//end cbxscratingreview_single_review_toolbar

	public function cbxscratingreview_single_review_delete_button( $post_review ) {
		$cbxscratingreview_setting = $this->setting;

		$allow_review_delete = $cbxscratingreview_setting->get_option( 'allow_review_delete', 'cbxscratingreview_common_config', 1 );
		if ( is_user_logged_in() && intval( $allow_review_delete ) == 1 ) {
			$current_user_id = get_current_user_id();
			$review_user_id  = intval( $post_review['user_id'] );

			if ( $current_user_id == $review_user_id ) {
				echo cbxscratingreview_reviewDeleteButtonRender( $post_review );
			}

		}
	}//end cbxscratingreview_single_review_delete_button

	/**
	 * Ajax review delete
	 */
	public function review_delete_ajax() {
		check_ajax_referer( 'cbxscratingreview', 'security' );
		$cbxscratingreview_setting = $this->setting;

		$allow_review_delete = $cbxscratingreview_setting->get_option( 'allow_review_delete', 'cbxscratingreview_common_config', 1 );

		$output            = array();
		$output['success'] = 0;


		$review_id = isset( $_POST['review_id'] ) ? intval( $_POST['review_id'] ) : 0;

		if ( intval( $allow_review_delete ) == 0 ) {
			$output['message'] = esc_html__( 'Review delete is not possible. Please contact site authority.', 'cbxscratingreview' );
		} else if ( $review_id == 0 ) {
			$output['message'] = esc_html__( 'Review id is invalid', 'cbxscratingreview' );
		} else if ( ! is_user_logged_in() ) {
			$output['message'] = esc_html__( 'You are not logged in and you don\'t own the review. Area you cheating?', 'cbxscratingreview' );
		} else {
			//now let's try to delete the message
			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;


			$review_info = cbxscratingreview_singleReview( $review_id );

			global $wpdb;
			$table_cbxscratingreview_review = $wpdb->prefix . 'cbxscratingreview_log';
			do_action( 'cbxscratingreview_review_delete_before', $review_info );

			$delete_status = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_cbxscratingreview_review WHERE id=%d AND user_id=%d", $review_id, $user_id ) );

			if ( $delete_status !== false ) {
				do_action( 'cbxscratingreview_review_delete_after', $review_info );

				$output['success'] = 1;
				$output['message'] = esc_html__( 'Review deleted successfully!', 'cbxscratingreview' );
			} else {
				$output['message'] = esc_html__( 'Review deleted failed!', 'cbxscratingreview' );
			}
		}

		echo wp_json_encode( $output );
		wp_die();
	}//end review_delete_ajax

	/**
	 * Auto integration for 'the_content'
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function the_content_auto_integration( $content ) {
		if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ) {
			return $content;
		}

		return $this->content_auto_integration( $content );
	}//end  the_content_auto_integration

	/**
	 * Auto integration for 'the_excerpt'
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function the_excerpt_auto_integration( $content ) {
		return $this->content_auto_integration( $content );
	}//end  the_excerpt_auto_integration


	/**
	 * Auto integration for content (the_content, the_excerpt)
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function content_auto_integration( $content ) {
		if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'] ) ) {
			return $content;
		}

		global $post;
		$cbxscratingreview_setting = $this->setting;


		$enable_auto_integration = intval( $cbxscratingreview_setting->get_option( 'enable_auto_integration', 'cbxscratingreview_common_config', 1 ) );

		if ( apply_filters( 'cbxscratingreview_enable_auto_integration', $enable_auto_integration ) ) {

			$post_id   = intval( $post->ID );
			$post_type = $post->post_type;


			//check if post type supported
			$post_types_supported = $cbxscratingreview_setting->get_option( 'post_types_auto', 'cbxscratingreview_common_config', array() );
			$show_on_single       = $cbxscratingreview_setting->get_option( 'show_on_single', 'cbxscratingreview_common_config', 1 );
			$show_on_home         = $cbxscratingreview_setting->get_option( 'show_on_home', 'cbxscratingreview_common_config', 1 );
			$show_on_arcv         = $cbxscratingreview_setting->get_option( 'show_on_arcv', 'cbxscratingreview_common_config', 1 );


			if ( ! in_array( $post_type, $post_types_supported ) ) {
				return $content;
			}


			if ( is_home() && $show_on_home ) {
				$extra_html_avg = '';
				if ( function_exists( 'cbxscratingreview_postAvgRatingRender' ) ) {
					$extra_html_avg = cbxscratingreview_postAvgRatingRender( $post_id );
				}

				return $extra_html_avg . $content;
			} else if ( is_archive() && $show_on_arcv ) {
				$extra_html_avg = '';
				if ( function_exists( 'cbxscratingreview_postAvgRatingRender' ) ) {
					$extra_html_avg = cbxscratingreview_postAvgRatingRender( $post_id );
				}

				return $extra_html_avg . $content;


			} else if ( is_singular() && $show_on_single ) {


				$extra_html_avg  = '';
				$extra_html_form = '';
				$extra_html_list = '';
				if ( function_exists( 'cbxscratingreview_postAvgRatingRender' ) ) {
					$extra_html_avg = cbxscratingreview_postAvgRatingRender( $post_id, true, true, 1 );
				}

				if ( function_exists( 'cbxscratingreview_reviewformRender' ) ) {
					$extra_html_form = cbxscratingreview_reviewformRender( $post_id );
				}

				$extra_html_list = do_shortcode( '[cbxscratingreview_postreviews post_id="' . $post_id . '"]' );

				return $extra_html_avg . $content . $extra_html_form . $extra_html_list;
			}

		}

		return $content;
	}//end content_auto_integration

	/**
	 * Init elementor widget
	 *
	 * @throws Exception
	 */
	public function init_elementor_widgets() {
		//register review form widget
		if ( ! class_exists( 'CBXSCRatingReviewReviewForm_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxscratingreview-reviewform-elemwidget.php';
		}

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CBXSCRatingReviewReviewForm_ElemWidget() );

		//register avg rating widget
		if ( ! class_exists( 'CBXSCRatingReviewPostAvgRating_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxscratingreview-postavgrating-elemwidget.php';
		}

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CBXSCRatingReviewPostAvgRating_ElemWidget() );


		//register the post's reviews widget
		if ( ! class_exists( 'CBXSCRatingReviewPostReviews_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxscratingreview-postreviews-elemwidget.php';
		}

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CBXSCRatingReviewPostReviews_ElemWidget() );

		//register the user dashboard widget
		if ( ! class_exists( 'CBXSCRatingReviewUserDashboard_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxscratingreview-userdashboard-elemwidget.php';
		}

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CBXSCRatingReviewUserDashboard_ElemWidget() );

		//register the single review widget
		if ( ! class_exists( 'CBXSCRatingReviewSingleReview_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxscratingreview-singlereview-elemwidget.php';
		}

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CBXSCRatingReviewSingleReview_ElemWidget() );

		//register the edit review widget
		if ( ! class_exists( 'CBXSCRatingReviewEditReview_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxscratingreview-editreview-elemwidget.php';
		}

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CBXSCRatingReviewEditReview_ElemWidget() );


		//register the latest rating widget
		if ( ! class_exists( 'CBXSCRatingReviewLRating_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxscratingreview-lratings-elemwidget.php';
		}

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CBXSCRatingReviewLRating_ElemWidget() );

		//register most rated posts widget
		if ( ! class_exists( 'CBXSCRatingReviewMRPosts_ElemWidget' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/elementor_widgets/class-cbxscratingreview-mrposts-elemwidget.php';
		}

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new CBXSCRatingReviewMRPosts_ElemWidget() );
	}//end widgets_registered

	/**
	 * Add new category to elementor
	 *
	 * @param $elements_manager
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'cbxscratingreview',
			array(
				'title' => esc_html__( 'CBX 5 Star Rating & Review', 'cbxscratingreview' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}//end add_elementor_widget_categories

	/**
	 * Load Elementor Custom Icon
	 */
	public function elementor_icon_loader() {
		wp_register_style( 'cbxscratingreview_elementor_icon', CBXSCRATINGREVIEW_ROOT_URL . 'assets/css/cbxscratingreview-elementor.css', false, $this->version );
		wp_enqueue_style( 'cbxscratingreview_elementor_icon' );

	}//end elementor_icon_loader

	/**
	 * WPBakery Widgets registers
	 *
	 * Before WPBakery inits includes the new widgets
	 */
	public function vc_before_init_actions() {
		//includes the vc widgets
		//user dashboard widget
		if ( ! class_exists( 'CBXSCRatingReviewUserDashboard_VCWidget' ) ) {
			require_once CBXSCRATINGREVIEW_ROOT_PATH . 'widgets/vc_widgets/class-cbxscratingreview-userdashboard-vcwidget.php';
		}
		new CBXSCRatingReviewUserDashboard_VCWidget();

		//individual review display widget
		if ( ! class_exists( 'CBXSCRatingReviewSingleReview_VCWidget' ) ) {
			require_once CBXSCRATINGREVIEW_ROOT_PATH . 'widgets/vc_widgets/class-cbxscratingreview-singlereview-vcwidget.php';
		}
		new CBXSCRatingReviewSingleReview_VCWidget();

		//individual review edit widget
		if ( ! class_exists( 'CBXSCRatingReviewEditReview_VCWidget' ) ) {
			require_once CBXSCRATINGREVIEW_ROOT_PATH . 'widgets/vc_widgets/class-cbxscratingreview-editreview-vcwidget.php';
		}
		new CBXSCRatingReviewEditReview_VCWidget();

		//review form display widget
		if ( ! class_exists( 'CBXSCRatingReviewReviewForm_VCWidget' ) ) {
			require_once CBXSCRATINGREVIEW_ROOT_PATH . 'widgets/vc_widgets/class-cbxscratingreview-reviewform-vcwidget.php';
		}
		new CBXSCRatingReviewReviewForm_VCWidget();

		//Post's reviews display widget
		if ( ! class_exists( 'CBXSCRatingReviewPostReviews_VCWidget' ) ) {
			require_once CBXSCRATINGREVIEW_ROOT_PATH . 'widgets/vc_widgets/class-cbxscratingreview-postreviews-vcwidget.php';
		}
		new CBXSCRatingReviewPostReviews_VCWidget();

		//Post's avg rating display widget
		if ( ! class_exists( 'CBXSCRatingReviewPostAvgRating_VCWidget' ) ) {
			require_once CBXSCRATINGREVIEW_ROOT_PATH . 'widgets/vc_widgets/class-cbxscratingreview-postavgrating-vcwidget.php';
		}
		new CBXSCRatingReviewPostAvgRating_VCWidget();

		//Most rated Posts widget
		if ( ! class_exists( 'CBXSCRatingReviewMRPosts_VCWidget' ) ) {
			require_once CBXSCRATINGREVIEW_ROOT_PATH . 'widgets/vc_widgets/class-cbxscratingreview-mrposts-vcwidget.php';
		}
		new CBXSCRatingReviewMRPosts_VCWidget();

		//Most rated Posts widget
		if ( ! class_exists( 'CBXSCRatingReviewLRatings_VCWidget' ) ) {
			require_once CBXSCRATINGREVIEW_ROOT_PATH . 'widgets/vc_widgets/class-cbxscratingreview-lratings-vcwidget.php';
		}
		new CBXSCRatingReviewLRatings_VCWidget();


	}//end vc_before_init_actions

}//end class CBXSCRatingReview_Public