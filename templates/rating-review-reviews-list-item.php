<?php
	/**
	 * Provides review list item
	 *
	 * This file is used to markup frontend review list item
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
	$post_id    = intval( $post_review['post_id'] );
	$post_title = get_the_title( $post_id );

	$avatar_style = apply_filters( 'cbxscratingreview_reviewer_avatar_style', '', $post_review );

?>
<?php
	$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
?>
<?php
	do_action( 'cbxscratingreview_review_list_item_before', $post_review );
?>
<div itemprop="review" itemscope itemtype="http://schema.org/Review">
		<span style="display: none;" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
			<span itemprop="name"><?php echo esc_attr( $post_title ); ?></span>
		</span>

	<p class="cbxscratingreview_review_list_item_user">
		<a itemprop="author" itemscope itemtype="http://schema.org/Person" class="cbxscratingreview_review_list_item_user_name" href="<?php echo esc_url( apply_filters( 'cbxscratingreview_reviewer_posts_url', get_author_posts_url( intval( $post_review['user_id'] ) ), $post_review ) ); ?>">
			<span class="cbxscratingreview_review_list_item_user_img" style="<?php echo $avatar_style; ?>">
				<?php echo apply_filters( 'cbxscratingreview_reviewer_avatar', get_avatar( $post_review['user_email'] ), $post_review ); ?>
			</span> <span class="cbxscratingreview_review_list_item_username" itemprop="name"><?php echo apply_filters( 'cbxscratingreview_reviewer_name', wp_unslash( $post_review['display_name'] ), $post_review ); ?></span> </a>

		<?php
			$review_create_date       = $post_review['date_created'];
			$review_create_date_human = CBXSCRatingReviewHelper::dateReadableFormat( $review_create_date )

		?>
		<a title="<?php echo sprintf( esc_html__( 'Review Created on: %s ', 'cbxscratingreview' ), $review_create_date ); ?>" class="cbxscratingreview_review_list_item_date" href="#cbxscratingreview_review_list_item_<?php echo intval( $post_review['id'] ); ?>">
			<?php
				echo $review_create_date_human;
			?>
		</a> <span class="clearfix cbxscratingreview_clearfix clear"></span>
	</p>
	<div class="clearfix cbxscratingreview_clearfix clear"></div>
	<p class="cbxscratingreview_review_list_item_star_score_headline">
		<span class="cbxscratingreview_review_list_item_score" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" title="<?php echo sprintf( esc_html__( 'Rated %s out of 5', 'cbxscratingreview' ), $post_review['score'] ); ?>"><meta itemprop="worstRating" content="1"><i itemprop="ratingValue"><?php echo number_format_i18n( $post_review['score'], 1 ) . '</i>/<i itemprop="bestRating">' . number_format_i18n( 5 ); ?></i></span>
		<span data-processed="0" data-score="<?php echo floatval( $post_review['score'] ); ?>" class="cbxscratingreview_review_list_item_star cbxscratingreview_readonlyrating_score_js"></span>

		<span class="cbxscratingreview_review_list_item_headline"><?php echo esc_attr( wp_unslash( $post_review['headline'] ) ); ?></span>
	</p>
	<?php
		do_action( 'cbxscratingreview_review_list_item_before_comment', $post_review );
	?>
	<div class="cbxscratingreview_review_list_item_comment">
		<p class="cbxscratingreview_review_list_item_heading"><?php esc_html_e( 'Review', 'cbxscratingreview' ); ?></p>
		<div class="cbxscratingreview_review_list_item_comment_text" itemprop="description"><?php echo wpautop( wp_unslash( $post_review['comment'] ) ); ?></div>
	</div>
	<?php
		do_action( 'cbxscratingreview_review_list_item_after_comment', $post_review );

		do_action( 'cbxscratingreview_review_list_item_after', $post_review );

	?>
</div>
