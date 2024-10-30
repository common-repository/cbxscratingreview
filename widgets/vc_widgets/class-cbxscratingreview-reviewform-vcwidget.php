<?php
	// Prevent direct file access
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Review Form widget for vc
	 *
	 * Class CBXSCRatingReviewReviewForm_VCWidget
	 */
	class CBXSCRatingReviewReviewForm_VCWidget extends WPBakeryShortCode {
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
				"name"        => esc_html__( "Review Form Widget", 'cbxscratingreview' ),
				"description" => esc_html__( "Review Form Widget Settings", 'cbxscratingreview' ),
				"icon"        => CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/widget_icons/icon_common.png',
				"category"    => esc_html__( 'CBX 5 Star Rating & Review', 'cbxscratingreview' ),
				"base"        => "cbxscratingreview_reviewform",
				"params"      => array(
					array(
						"type"        => "textfield",
						"holder"      => "div",
						"class"       => "",
						'admin_label' => true,
						"heading"     => esc_html__( "Title", 'cbxscratingreview' ),
						'description' => esc_html__( 'Keep empty to hide', 'cbxscratingreview' ),
						"param_name"  => "title",
						"std"         => '',
					),
					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( 'Show Guest Login Form', 'cbxscratingreview' ),
						"param_name"  => "guest_login",
						'value'       => array(
							esc_html__( 'Yes', 'cbxscratingreview' ) => 1,
							esc_html__( 'No', 'cbxscratingreview' )  => 0,
						),
						'std'         => 1,
					),
				)
			) );
		}//end bakery_shortcode_mapping
	}// end class CBXSCRatingReviewReviewForm_VCWidget