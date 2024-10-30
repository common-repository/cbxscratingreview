<?php
	// Prevent direct file access
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Individual Review Display widget for vc
	 *
	 * Class CBXSCRatingReviewEditReview_VCWidget
	 */
	class CBXSCRatingReviewEditReview_VCWidget extends WPBakeryShortCode {
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
				"name"        => esc_html__( "Individual Review Edit", 'cbxscratingreview' ),
				"description" => esc_html__( "Individual Review Edit Widget Settings", 'cbxscratingreview' ),
				"base"        => "cbxscratingreview_editreview",
				"icon"        => CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/widget_icons/icon_common.png',
				"category"    => esc_html__( 'CBX 5 Star Rating & Review', 'cbxscratingreview' ),
				"params"      => array(
					array(
						"type"        => "textfield",
						"holder"      => "div",
						"class"       => "",
						'admin_label' => true,
						"heading"     => esc_html__( "Review ID", 'cbxscratingreview' ),
						'description' => esc_html__( 'Keep default for general single review page use', 'cbxscratingreview' ),
						"param_name"  => "review_id",
						"std"         => 0,
					),
				)
			) );
		}//end bakery_shortcode_mapping
	}// end class CBXSCRatingReviewEditReview_VCWidget