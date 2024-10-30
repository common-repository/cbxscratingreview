<?php
	// Prevent direct file access
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Post's Reviews widget for vc
	 *
	 * Class CBXSCRatingReviewPostReviews_VCWidget
	 */
	class CBXSCRatingReviewPostReviews_VCWidget extends WPBakeryShortCode {
		/**
		 * Constructor.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'bakery_shortcode_mapping' ), 12 );
		}// /end of constructor


		/**
		 * Shortcode Mapping
		 */
		public function bakery_shortcode_mapping() {
			$cbxscratingreview_setting = new \CBXSCRatingReviewSettings();
			$perpage_default           = intval( $cbxscratingreview_setting->get_option( 'default_per_page', 'cbxscratingreview_common_config', 10 ) );
			$show_filter_default       = intval( $cbxscratingreview_setting->get_option( 'show_review_filter', 'cbxscratingreview_common_config', 1 ) );
			//$enable_positive_critical = intval( $cbxscratingreview_setting->get_option( 'enable_positive_critical', 'cbxscratingreview_common_config', 1 ) );

			$all_scores = array_flip( CBXSCRatingReviewHelper::get_score_filters() );


			// Map the block with vc_map()
			vc_map( array(
				"name"        => esc_html__( 'Post Reviews Widget', 'cbxscratingreview' ),
				"description" => esc_html__( 'Post Reviews Widget Settings', 'cbxscratingreview' ),
				"icon"        => CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/widget_icons/icon_common.png',
				"category"    => esc_html__( 'CBX 5 Star Rating & Review', 'cbxscratingreview' ),
				"base"        => "cbxscratingreview_postreviews",
				"params"      => array(
					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( "Display order", 'cbxscratingreview' ),
						"param_name"  => "order",
						'value'       => array(
							esc_html__( 'Ascending', 'cbxscratingreview' )  => 'ASC',
							esc_html__( 'Descending', 'cbxscratingreview' ) => 'DESC',
						),
						'std'         => 'DESC',
					),
					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( "Display order by", 'cbxscratingreview' ),
						"param_name"  => "orderby",
						'value'       => array(
							esc_html__( 'Review ID', 'cbxscratingreview' ) => 'id',
							esc_html__( 'Post ID', 'cbxscratingreview' )   => 'post_id',
						),
						'std'         => 'id',
					),

					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( "Select Score", 'cbxscratingreview' ),
						"param_name"  => "score",
						'value'       => $all_scores,
						'std'         => '',
					),
					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( "Show Filter", 'cbxscratingreview' ),
						"param_name"  => "show_filter",
						'value'       => array(
							esc_html__( 'Yes', 'cbxscratingreview' ) => 1,
							esc_html__( 'No', 'cbxscratingreview' )  => 0,
						),
						'std'         => $show_filter_default,
					),
					array(
						"type"        => "textfield",
						"holder"      => "div",
						"class"       => "",
						'admin_label' => true,
						"heading"     => esc_html__( "Per Page", 'cbxscratingreview' ),
						"param_name"  => "perpage",
						"std"         => $perpage_default,
					),
					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( "Show More", 'cbxscratingreview' ),
						"param_name"  => "show_more",
						'value'       => array(
							esc_html__( 'Yes', 'cbxscratingreview' ) => 1,
							esc_html__( 'No', 'cbxscratingreview' )  => 0,
						),
						'std'         => 1,
					),
				)
			) );
		}//end bakery_shortcode_mapping
	}// end class CBXSCRatingReviewPostReviews_VCWidget