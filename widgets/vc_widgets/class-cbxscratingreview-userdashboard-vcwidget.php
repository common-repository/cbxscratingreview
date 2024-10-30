<?php
	// Prevent direct file access
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * User Dashboard widget for vc
	 *
	 * Class CBXSCRatingReviewUserDashboard_VCWidget
	 */
	class CBXSCRatingReviewUserDashboard_VCWidget extends WPBakeryShortCode {
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
				"name"        => esc_html__( "User Dashboard", 'cbxscratingreview' ),
				"description" => esc_html__( "User Dashboard Widget Settings", 'cbxscratingreview' ),
				"base"        => "cbxscratingreview_userdashboard",
				"icon"        => CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/widget_icons/icon_common.png',
				"category"    => esc_html__( 'CBX 5 Star Rating & Review', 'cbxscratingreview' ),
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
							esc_html__( 'Review ID', 'cbxscratingreview' )     => 'id',
							esc_html__( 'Post ID', 'cbxscratingreview' )       => 'post_id',
							esc_html__( 'Post Type', 'cbxscratingreview' )     => 'post_type',
							esc_html__( 'Review Score', 'cbxscratingreview' )  => 'score',
							esc_html__( 'Review Status', 'cbxscratingreview' ) => 'status',
						),
						'std'         => 'id',
					),
					array(
						"type"        => "textfield",
						"holder"      => "div",
						"class"       => "",
						'admin_label' => true,
						"heading"     => esc_html__( "Per Pages", 'cbxscratingreview' ),
						'description' => esc_html__( 'Display reviews per page', 'cbxscratingreview' ),
						"param_name"  => "perpage",
						"std"         => 20,
					),
				)
			) );
		}//end bakery_shortcode_mapping
	}// end class CBXSCRatingReviewUserDashboard_VCWidget