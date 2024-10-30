<?php
	/**
	 * Provides review list item toolbar
	 *
	 * This file is used to markup frontend review list item toolbar
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


?>
<div class="cbxscratingreview_review_list_item_toolbar">
	<?php do_action( 'cbxscratingreview_review_list_item_toolbar_start', $post_review ); ?>

	<?php do_action( 'cbxscratingreview_review_list_item_toolbar_end', $post_review ); ?>
</div>
