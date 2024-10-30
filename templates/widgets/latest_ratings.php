<?php
	/**
	 * Provides Latest Rating templates
	 *
	 * This file is used to markup latest ratings widget/shortcode/display
	 *
	 * @link       http://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    cbxscratingreview
	 * @subpackage cbxscratingreview/templates/widgets
	 */

	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	//post data needs to be in variable $data_posts
?>
<?php
	do_action( 'cbxscratingreviewlratings_list_before' );
?>
	<div class="cbxscratingreviewlratings_list_wrap cbxscratingreviewlratings_list_wrap_<?php echo esc_attr( $scope ); ?>">
		<?php
			if ( $title != '' ) {
				echo '<h3 class="cbxscratingreview-title cbxscratingreview-title-lratings">' . $title . '</h3>';
			}
		?>
		<ul class="cbxscratingreviewlratings_list">
			<?php
				if ( is_array( $data_posts ) && sizeof( $data_posts ) > 0 ) {
					foreach ( $data_posts as $index => $single_review ) {
						$post_id   = intval( $single_review['post_id'] );
						$user_id   = intval( $single_review['user_id'] );
						$user_info = get_userdata( $user_id );

						$post_title        = get_the_title( $post_id );
						$post_link         = get_permalink( $post_id );
						$user_display_name = $user_info->display_name;
						?>
						<li>
							<?php
								do_action( 'cbxscratingreviewlratings_list_item_before', $single_review );
							?>
							<?php
								echo '<span><a href="' . apply_filters( 'cbxscratingreview_reviewer_posts_url', esc_url( get_author_posts_url( $user_id ) ), $single_review ) . '">' . apply_filters( 'cbxscratingreview_reviewer_name', esc_attr( $user_display_name ), $single_review ) . '</a>' . esc_html__( ' rated ', 'cbxscratingreview' ) . '</span>';

								echo '<span data-processed="0" data-score="' . floatval( $single_review['score'] ) . '" class="cbxscratingreview_readonlyrating cbxscratingreview_readonlyrating_score cbxscratingreview_readonlyrating_score_js"></span>';
								echo '<span class="cbxscratingreview_readonlyrating cbxscratingreview_readonlyrating_info">' . number_format_i18n( $single_review['score'], 1 ) . '/' . number_format_i18n( 5 ) . '</span>';
							?>
							<a href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_attr( $post_title ); ?></a>
							<?php
								do_action( 'cbxscratingreviewlratings_list_item_after', $single_review );
							?>
						</li>
					<?php }
				} else {
					echo '<li>' . esc_html__( 'No item found', 'cbxscratingreview' ) . '</li>';
				}
			?>
		</ul>
	</div>
<?php
	do_action( 'cbxscratingreviewlratings_list_after' );