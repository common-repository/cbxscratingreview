<?php
	/**
	 * Provides review read only rating
	 *
	 * This file is used to markup frontend review read only rating
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
	do_action( 'cbxscratingreview_user_rating_readonly_before', $post_review_by_user );
?>

	<div class="cbxscratingreview_template_user_rating_readonly">
		<span data-processed="0" data-score="<?php echo floatval( $post_review_by_user['score'] ); ?>" class="cbxscratingreview_readonlyrating cbxscratingreview_readonlyrating_score  cbxscratingreview_readonlyrating_score_js"></span> <span class="cbxscratingreview_readonlyrating cbxscratingreview_readonlyrating_info">
		<?php esc_html_e( 'You rated this ', 'cbxscratingreview' );
			echo number_format_i18n( $post_review_by_user['score'], 1 ) . '/' . number_format_i18n( 5 ); ?>
	</span>
	</div>

<?php
	do_action( 'cbxscratingreview_user_rating_readonly_after', $post_review_by_user );