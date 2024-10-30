<?php

	use Elementor\Widget_Base;
	use Elementor\Controls_Manager;

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * CBX 5 Star Rating & Review - Most Rated Elementor Widget
	 */
	class CBXSCRatingReviewMRPosts_ElemWidget extends \Elementor\Widget_Base {

		/**
		 * Retrieve most bookmarked posts widget name.
		 *
		 * @return string Widget name.
		 * @since  1.0.0
		 * @access public
		 *
		 */
		public function get_name() {
			return 'cbxscratingreviewmrposts';
		}

		/**
		 * Retrieve most bookmarked posts widget title.
		 *
		 * @return string Widget title.
		 * @since  1.0.0
		 * @access public
		 *
		 */
		public function get_title() {
			return esc_html__( 'Most Rated Posts', 'cbxscratingreview' );
		}

		/**
		 * Get widget categories.
		 *
		 * Retrieve the widget categories.
		 *
		 * @return array Widget categories.
		 * @since  1.0.10
		 * @access public
		 *
		 */
		public function get_categories() {
			return array( 'cbxscratingreview' );
		}

		/**
		 * Retrieve most bookmarked posts widget icon.
		 *
		 * @return string Widget icon.
		 * @since  1.0.0
		 * @access public
		 *
		 */
		public function get_icon() {
			return 'cbxscratingreview-mrposts-icon';
		}

		/**
		 * Register most bookmarked posts widget controls.
		 *
		 * Adds different input fields to allow the user to change and customize the widget settings.
		 *
		 * @since  1.0.0
		 * @access protected
		 */
		protected function _register_controls() {

			$this->start_controls_section(
				'section_cbxscratingreview_mrposts',
				array(
					'label' => esc_html__( 'Most Rated Posts Widget Settings', 'cbxscratingreview' ),
				)
			);

			$this->add_control(
				'title',
				array(
					'label'       => esc_html__( 'Title', 'cbxscratingreview' ),
					'description' => esc_html__( 'Keep empty to hide', 'cbxscratingreview' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => esc_html__( 'Most Rated Posts', 'cbxscratingreview' ),
				)
			);

			$this->add_control(
				'order',
				array(
					'label'       => esc_html__( 'Display order', 'cbxscratingreview' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'default'     => 'desc',
					'placeholder' => esc_html__( 'Select order', 'cbxscratingreview' ),
					'options'     => array(
						'asc'  => esc_html__( 'Ascending', 'cbxscratingreview' ),
						'desc' => esc_html__( 'Descending', 'cbxscratingreview' ),
					)
				)
			);

			$this->add_control(
				'orderby',
				array(
					'label'       => esc_html__( 'Display order by', 'cbxscratingreview' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'default'     => 'avg_rating',
					'placeholder' => esc_html__( 'Select order by', 'cbxscratingreview' ),
					'options'     => array(
						'avg_rating'  => esc_html__( 'Avg Rating', 'cbxscratingreview' ),
						'total_count' => esc_html__( 'Total Ratings', 'cbxscratingreview' ),
						'post_id'     => esc_html__( 'Post ID', 'cbxscratingreview' ),
					)
				)
			);

			$this->add_control(
				'limit',
				array(
					'label'   => esc_html__( 'Limit', 'cbxscratingreview' ),
					'type'    => \Elementor\Controls_Manager::NUMBER,
					'default' => 10,
					'min'     => 1,
					'step'    => 1
				)
			);

			//$object_types = \CBXWPBookmarkHelper::object_types( true );
			$all_post_types = \CBXSCRatingReviewHelper::post_types( true );

			$this->add_control(
				'type',
				array(
					'label'       => esc_html__( 'Post type', 'cbxscratingreview' ),
					'type'        => \Elementor\Controls_Manager::SELECT2,
					'default'     => 'post',
					'placeholder' => esc_html__( 'Select post type', 'cbxscratingreview' ),
					'options'     => $all_post_types,
					'multiple'    => false,
					'label_block' => true

				)
			);


			$this->end_controls_section();
		}//end _register_controls


		/**
		 * Convert yes/no to boolean on/off
		 *
		 * @param string $value
		 *
		 * @return string
		 */
		public static function yes_no_to_on_off( $value = '' ) {
			if ( $value === 'yes' ) {
				return 'on';
			}

			return 'off';
		}//end yes_no_to_on_off

		/**
		 * Convert yes/no switch to boolean 1/0
		 *
		 * @param string $value
		 *
		 * @return int
		 */
		public static function yes_no_to_1_0( $value = '' ) {
			if ( $value === 'yes' ) {
				return 1;
			}

			return 0;
		}//end yes_no_to_1_0

		/**
		 * Render most bookmarked posts widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @since  1.0.0
		 * @access protected
		 */
		protected function render() {
			/*if ( ! class_exists( 'CBXWPBookmark_Settings_API' ) ) {
				require_once plugin_dir_path( dirname(dirname( __FILE__ ) )) . 'includes/class-cbxwpbookmark-setting.php';
			}

			$settings_api = new \CBXWPBookmark_Settings_API();*/

			$settings = $this->get_settings();

			$attr = array();

			//$type = $settings['type'];
			/*if ( is_array( $type ) ) {
				$type = array_filter( $type );
				$type = implode( ',', $type );
			}*/


			$attr['title']   = sanitize_text_field( $settings['title'] );
			$attr['order']   = sanitize_text_field( $settings['order'] );
			$attr['orderby'] = sanitize_text_field( $settings['orderby'] );
			$attr['limit']   = intval( $settings['limit'] );
			$attr['type']    = sanitize_text_field( $settings['type'] );
			$attr['scope']   = 'elementor';


			$attr = apply_filters( 'cbxscratingreview_elementor_shortcode_builder_attr', $attr, $settings, 'cbxscratingreviewmrposts' );

			$attr_html = '';

			foreach ( $attr as $key => $value ) {
				$attr_html .= ' ' . $key . '="' . $value . '" ';
			}

			echo do_shortcode( '[cbxscratingreviewmrposts ' . $attr_html . ']' );
		}//end render

		/**
		 * Render most bookmarked posts widget output in the editor.
		 *
		 * Written as a Backbone JavaScript template and used to generate the live preview.
		 *
		 * @since  1.0.0
		 * @access protected
		 */
		protected function _content_template() {
		}//end _content_template
	}//end class CBXSCRatingReviewLRating_ElemWidget
