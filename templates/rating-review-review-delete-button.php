<?php
	/**
	 * Provides review delete button
	 *
	 * This file is used to markup frontend review delete button
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
<span class="cbxscratingreview_review_list_item_toolbar_item cbxscratingreview_review_list_item_toolbar_item_deletebutton">
	<a href="#" class="cbxscratingreview-review-delete" data-busy="0" data-reviewid="<?php echo intval( $post_review['id'] ) ?>" data-postid="<?php echo intval( $post_review['post_id'] ) ?>" title="<?php esc_html_e( 'Click to delete this review', 'cbxscratingreview' ); ?>"><?php esc_html_e( 'Delete Review', 'cbxscratingreview' ); ?></a>
</span>