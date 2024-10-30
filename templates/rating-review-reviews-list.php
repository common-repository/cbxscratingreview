<?php
	/**
	 * Provides review list
	 *
	 * This file is used to markup frontend review list and includes sub templates
	 *
	 * @link       http://codeboxr.com
	 * @since      1.0.0
	 *
	 * @package    cbxscratingreview
	 * @subpackage cbxscratingreview/templates
	 */

	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>
<?php
    $cbxscratingreview_setting = new CBXSCRatingReviewSettings();
	do_action( 'cbxscratingreview_review_list_before', $post_reviews );

    $layout                    = esc_attr( $cbxscratingreview_setting->get_option( 'layout', 'cbxscratingreview_common_config', 'layout_default' ) );
?>
	<ul class="<?php echo apply_filters( 'cbxscratingreview_review_list_items_class', 'cbxscratingreview_review_list_items cbxscratingreview_review_list_items_'.$layout.'' ); ?>" id="cbxscratingreview_review_list_items_<?php echo intval( $post_id ); ?>">
		<?php
			if ( sizeof( $post_reviews ) > 0 ) {
				foreach ( $post_reviews as $index => $post_review ) { ?>
					<li id="cbxscratingreview_review_list_item_<?php echo intval( $post_review['id'] ); ?>" class="<?php echo apply_filters( 'cbxscratingreview_review_list_item_class', 'cbxscratingreview_review_list_item' ); ?>">
						<?php
							echo cbxscratingreview_get_template_html( 'rating-review-reviews-list-item.php', array(
									'review_id'   => $post_review['id'],
									'post_review' => $post_review
								)
							);
						?>
					</li>
				<?php }
			} else {
				?>
				<li class="<?php echo apply_filters( 'cbxscratingreview_review_list_item_class_notfound_class', 'cbxscratingreview_review_list_item cbxscratingreview_review_list_item_notfound' ); ?>">
					<p class="no_reviews_found"><?php esc_html_e( 'No reviews yet!', 'cbxscratingreview' ); ?></p>
				</li>
				<?php
			}

		?>
	</ul>
<?php
	do_action( 'cbxscratingreview_review_list_after', $post_reviews );