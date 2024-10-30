<?php
	// Prevent direct file access
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Post's Avg Rating widget for vc
	 *
	 * Class CBXSCRatingReviewPostAvgRating_VCWidget
	 */
	class CBXSCRatingReviewPostAvgRating_VCWidget extends WPBakeryShortCode {
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
			// Map the block with vc_map()
			vc_map( array(
				"name"        => esc_html__( 'Post Avg Rating Widget', 'cbxscratingreview' ),
				"description" => esc_html__( 'Post Avg Rating Widget Settings', 'cbxscratingreview' ),
				"icon"        => CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/widget_icons/icon_common.png',
				"category"    => esc_html__( 'CBX 5 Star Rating & Review', 'cbxscratingreview' ),
				"base"        => "cbxscratingreview_postavgrating",
				"params"      => array(
					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( "Show Score", 'cbxscratingreview' ),
						"param_name"  => "show_score",
						'value'       => array(
							esc_html__( 'Yes', 'cbxscratingreview' ) => 1,
							esc_html__( 'No', 'cbxscratingreview' )  => 0,
						),
						'std'         => 1,
					),
					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( "Show Star", 'cbxscratingreview' ),
						"param_name"  => "show_star",
						'value'       => array(
							esc_html__( 'Yes', 'cbxscratingreview' ) => 1,
							esc_html__( 'No', 'cbxscratingreview' )  => 0,
						),
						'std'         => 1,
					),
					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( "Show Chart", 'cbxscratingreview' ),
						"param_name"  => "show_chart",
						'value'       => array(
							esc_html__( 'Yes', 'cbxscratingreview' ) => 1,
							esc_html__( 'No', 'cbxscratingreview' )  => 0,
						),
						'std'         => 0,
					),
				)
			) );
		}//end bakery_shortcode_mapping
	}// end class CBXSCRatingReviewPostAvgRating_VCWidget