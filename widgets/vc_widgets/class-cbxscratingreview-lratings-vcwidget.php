<?php
	// Prevent direct file access
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}


	/**
	 * Latest Ratings widget for vc
	 *
	 * Class CBXSCRatingReviewLRatings_VCWidget
	 */
	class CBXSCRatingReviewLRatings_VCWidget extends WPBakeryShortCode {
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
			$all_post_types = array_flip( CBXSCRatingReviewHelper::post_types( true ) );


			// Map the block with vc_map()
			vc_map( array(
				"name"        => esc_html__( 'Latest Ratings Widget', 'cbxscratingreview' ),
				"description" => esc_html__( 'Latest Ratings Widget Settings', 'cbxscratingreview' ),
				"icon"        => CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/widget_icons/icon_common.png',
				"category"    => esc_html__( 'CBX 5 Star Rating & Review', 'cbxscratingreview' ),
				"base"        => "cbxscratingreviewlratings",
				"params"      => array(
					array(
						"type"        => "textfield",
						"holder"      => "div",
						"class"       => "",
						'admin_label' => true,
						"heading"     => esc_html__( 'Title', 'cbxscratingreview' ),
						'description' => esc_html__( 'Keep empty to hide', 'cbxscratingreview' ),
						"param_name"  => "title",
						"std"         => esc_html__( 'Latest Ratings', 'cbxscratingreview' ),
					),
					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( 'Display order', 'cbxscratingreview' ),
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
						"heading"     => esc_html__( 'Display order by', 'cbxscratingreview' ),
						"param_name"  => "orderby",
						'value'       => array(
							esc_html__( 'Review id', 'cbxscratingreview' )          => 'id',
							esc_html__( 'Post ID', 'cbxscratingreview' )            => 'post_id',
							esc_html__( 'Rating Score Value', 'cbxscratingreview' ) => 'score',
						),
						'std'         => 'id',
					),
					array(
						"type"        => "textfield",
						"holder"      => "div",
						"class"       => "",
						'admin_label' => true,
						"heading"     => esc_html__( 'Limit', 'cbxscratingreview' ),
						"param_name"  => "limit",
						"std"         => 10,
					),

					array(
						"type"        => "dropdown",
						'admin_label' => true,
						"heading"     => esc_html__( 'Post type', 'cbxscratingreview' ),
						"param_name"  => "type",
						'value'       => $all_post_types,
						'std'         => 'post',
					)
				)
			) );
		}//end bakery_shortcode_mapping
	}// end class CBXSCRatingReviewLRatings_VCWidget