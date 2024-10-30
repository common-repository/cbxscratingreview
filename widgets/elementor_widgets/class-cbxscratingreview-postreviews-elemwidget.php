<?php

	use Elementor\Widget_Base;
	use Elementor\Controls_Manager;

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	/**
	 * CBX 5 Star Rating & Review - Post's Reviews Elementor Widget
	 */
	class CBXSCRatingReviewPostReviews_ElemWidget extends \Elementor\Widget_Base {

		/**
		 * Retrieve most bookmarked posts widget name.
		 *
		 * @return string Widget name.
		 * @since  1.0.0
		 * @access public
		 *
		 */
		public function get_name() {
			return 'cbxscratingreviewpostreviews';
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
			return esc_html__( 'Post\'s Reviews', 'cbxscratingreview' );
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
			return 'cbxscratingreview-postreviews-icon';
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
			$perpage_default           = intval( $cbxscratingreview_setting->get_option( 'default_per_page', 'cbxscratingreview_common_config', 10 ) );
			$show_filter_default       = intval( $cbxscratingreview_setting->get_option( 'show_review_filter', 'cbxscratingreview_common_config', 1 ) );
			$enable_positive_critical  = intval( $cbxscratingreview_setting->get_option( 'enable_positive_critical', 'cbxscratingreview_common_config', 1 ) );


			$this->start_controls_section(
				'section_cbxscratingreview_postreviews',
				array(
					'label' => esc_html__( 'Post\'s Reviews Widget Settings', 'cbxscratingreview' ),
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
					'default'     => 'id',
					'placeholder' => esc_html__( 'Select order by', 'cbxscratingreview' ),
					'options'     => array(
						'id'      => esc_html__( 'Review ID', 'cbxscratingreview' ),
						'post_id' => esc_html__( 'Post ID', 'cbxscratingreview' ),
						//'total_count'   => esc_html__( 'Total Ratings', 'cbxscratingreview' ),
					)
				)
			);

			$all_scores = \CBXSCRatingReviewHelper::get_score_filters();

			$this->add_control(
				'score',
				array(
					'label'       => esc_html__( 'Select Score', 'cbxscratingreview' ),
					'type'        => \Elementor\Controls_Manager::SELECT,
					'default'     => '',
					'placeholder' => esc_html__( 'Select Score', 'cbxscratingreview' ),
					'options'     => $all_scores,
					'multiple'    => false,
					'label_block' => true

				)
			);

			$this->add_control(
				'show_filter',
				array(
					'label'        => esc_html__( 'Show Filter', 'cbxscratingreview' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'cbxscratingreview' ),
					'label_off'    => esc_html__( 'No', 'cbxscratingreview' ),
					'return_value' => 'yes',
					'default'      => ( $show_filter_default ) ? 'yes' : 'no',
				)
			);

			$this->add_control(
				'perpage',
				array(
					'label'   => esc_html__( 'Per Page', 'cbxscratingreview' ),
					'type'    => \Elementor\Controls_Manager::NUMBER,
					'default' => $perpage_default,
					'min'     => 1,
					'step'    => 1
				)
			);

			$this->add_control(
				'show_more',
				array(
					'label'        => esc_html__( 'Show More', 'cbxscratingreview' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'cbxscratingreview' ),
					'label_off'    => esc_html__( 'No', 'cbxscratingreview' ),
					'return_value' => 'yes',
					'default'      => 'yes',
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


			$attr['order']       = sanitize_text_field( $settings['order'] );
			$attr['orderby']     = sanitize_text_field( $settings['orderby'] );
			$attr['show_filter'] = $this->yes_no_to_1_0( $settings['show_filter'] );
			$attr['score']       = sanitize_text_field( $settings['score'] );
			$attr['perpage']     = intval( $settings['perpage'] );
			$attr['show_more']   = $this->yes_no_to_1_0( $settings['show_more'] );


			$attr = apply_filters( 'cbxscratingreview_elementor_shortcode_builder_attr', $attr, $settings, 'postreviews' );

			$attr_html = '';

			foreach ( $attr as $key => $value ) {
				$attr_html .= ' ' . $key . '="' . $value . '" ';
			}

			echo do_shortcode( '[cbxscratingreview_postreviews ' . $attr_html . ']' );
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
	}//end class CBXSCRatingReviewPostReviews_ElemWidget
