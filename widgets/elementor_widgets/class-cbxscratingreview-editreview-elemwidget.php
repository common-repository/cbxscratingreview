<?php

	use Elementor\Widget_Base;
	use Elementor\Controls_Manager;

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * CBX 5 Star Rating & Review - Edit Review Review Elementor Widget
	 */
	class CBXSCRatingReviewEditReview_ElemWidget extends \Elementor\Widget_Base {

		/**
		 * Retrieve most bookmarked posts widget name.
		 *
		 * @return string Widget name.
		 * @since  1.0.0
		 * @access public
		 *
		 */
		public function get_name() {
			return 'cbxscratingrevieweditreview';
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
			return esc_html__( 'Individual Review Edit', 'cbxscratingreview' );
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
			return 'cbxscratingreview-editreview-icon';
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

			$cbxscratingreview_setting = new \CBXSCRatingReviewSettings();


			$this->start_controls_section(
				'section_cbxscratingreview_editreview',
				array(
					'label' => esc_html__( 'Individual Review Edit Widget Settings', 'cbxscratingreview' ),
				)
			);


			$this->add_control(
				'review_id',
				array(
					'label'       => esc_html__( 'Review ID', 'cbxscratingreview' ),
					'description' => esc_html__( 'Keep default for general single review page use', 'cbxscratingreview' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => 0
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
			$settings = $this->get_settings();

			$attr = array();

			$attr['review_id'] = intval( $settings['review_id'] );

			$attr = apply_filters( 'cbxscratingreview_elementor_shortcode_builder_attr', $attr, $settings, 'editreview' );

			$attr_html = '';

			foreach ( $attr as $key => $value ) {
				$attr_html .= ' ' . $key . '="' . $value . '" ';
			}

			echo do_shortcode( '[cbxscratingreview_editreview ' . $attr_html . ']' );
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
	}//end class CBXSCRatingReviewEditReview_ElemWidget
