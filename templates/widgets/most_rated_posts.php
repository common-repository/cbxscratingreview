<?php
	/**
	 * Provides most rated post templates
	 *
	 * This file is used to markup most rated posts widget/shortcode/display
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
	do_action( 'cbxscratingreviewmrposts_list_before' );
?>
	<div class="cbxscratingreviewmrposts_list cbxscratingreviewmrposts_list_<?php echo esc_attr( $scope ); ?>">
		<?php
			if ( $title != '' ) {
				echo '<h3 class="cbxscratingreview-title cbxscratingreview-title-mrposts">' . $title . '</h3>';
			}
		?>
		<ul class="cbxscratingreviewmrposts_list cbxscratingreviewmrposts_<?php echo esc_attr( $scope ); ?>">
			<?php
				if ( is_array( $data_posts ) && sizeof( $data_posts ) > 0 ) {
					foreach ( $data_posts as $index => $single_post ) { ?>
						<li>
							<?php
								do_action( 'cbxscratingreviewmrposts_list_item_before', $single_post );
							?>
							<?php
								echo '<span data-processed="0" data-score="' . floatval( $single_post['avg_rating'] ) . '" class="cbxscratingreview_readonlyrating cbxscratingreview_readonlyrating_score cbxscratingreview_readonlyrating_score_js"></span>';
							?>
							<span class="cbxscratingreview_readonlyrating cbxscratingreview_readonlyrating_info">
							<?php echo number_format_i18n( $single_post['avg_rating'], 2 ) . '/' . number_format_i18n( 5 ); ?> (<?php echo intval( $single_post['total_count'] ); ?> <?php echo ( $single_post['total_count'] == 0 ) ? esc_html__( 'Review', 'cbxscratingreview' ) : _n( 'Review', 'Reviews', $single_post['total_count'], 'cbxscratingreview' ); ?>)
	</span> <a href="<?php echo esc_url( get_permalink( intval( $single_post['post_id'] ) ) ); ?>"><?php echo get_the_title( intval( $single_post['post_id'] ) ); ?></a>
							<?php
								do_action( 'cbxscratingreviewmrposts_list_item_after', $single_post );
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
	do_action( 'cbxscratingreviewmrposts_list_after' );
