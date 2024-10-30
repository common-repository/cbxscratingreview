<?php

	use Elementor\Widget_Base;
	use Elementor\Controls_Manager;

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * CBX 5 Star Rating & Review - Post Avg Rating Elementor Widget
	 */
	class CBXSCRatingReviewPostAvgRating_ElemWidget extends \Elementor\Widget_Base {

		/**
		 * Retrieve most bookmarked posts widget name.
		 *
		 * @return string Widget name.
		 * @since  1.0.0
		 * @access public
		 *
		 */
		public function get_name() {
			return 'cbxscratingreviewpostavgrating';
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
			return esc_html__( 'Post\'s Avg Rating', 'cbxscratingreview' );
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
			return 'cbxscratingreview-postavgrating-icon';
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
				'section_cbxscratingreview_postavgrating',
				array(
					'label' => esc_html__( 'Post\'s Avg Rating Widget Settings', 'cbxscratingreview' ),
				)
			);

			$this->add_control(
				'show_score',
				array(
					'label'        => esc_html__( 'Show Score count', 'cbxscratingreview' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'cbxscratingreview' ),
					'label_off'    => esc_html__( 'No', 'cbxscratingreview' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'show_star',
				array(
					'label'        => esc_html__( 'Show Star', 'cbxscratingreview' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'cbxscratingreview' ),
					'label_off'    => esc_html__( 'No', 'cbxscratingreview' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'show_chart',
				array(
					'label'        => esc_html__( 'Show Chart', 'cbxscratingreview' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'cbxscratingreview' ),
					'label_off'    => esc_html__( 'No', 'cbxscratingreview' ),
					'return_value' => 'yes',
					'default'      => 'no',
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
			$attr     = array();

			$attr['show_score'] = $this->yes_no_to_1_0( $settings['show_score'] );
			$attr['show_star']  = $this->yes_no_to_1_0( $settings['show_star'] );
			$attr['show_chart'] = $this->yes_no_to_1_0( $settings['show_chart'] );


			$attr = apply_filters( 'cbxscratingreview_elementor_shortcode_builder_attr', $attr, $settings, 'postavgrating' );

			$attr_html = '';

			foreach ( $attr as $key => $value ) {
				$attr_html .= ' ' . $key . '="' . $value . '" ';
			}

			echo do_shortcode( '[cbxscratingreview_postavgrating ' . $attr_html . ']' );
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
	}//end class CBXSCRatingReviewPostAvgRating_ElemWidget
