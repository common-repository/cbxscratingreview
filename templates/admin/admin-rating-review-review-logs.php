<?php
	/**
	 * Provide a dashboard rating log listing
	 *
	 * This file is used to markup the admin-facing rating log listing
	 *
	 * @link       http://codeboxr.com
	 * @since      1.0.7
	 *
	 * @package    cbxscratingreview
	 * @subpackage cbxscratingreview/templates/admin
	 */

	if ( ! defined( 'WPINC' ) ) {
		die;
	}
?>

<?php
	$cbxscratingreview_review_logs = new CBXSCRatingReviewLog_List_Table();

	//Fetch, prepare, sort, and filter CBXSCRatingReviewLog data
	$cbxscratingreview_review_logs->prepare_items();
?>

<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Rating Log Manager', 'cbxscratingreview' ); ?>
	</h1>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<div class="inside">
							<form id="cbxscratingreview_review_logs" method="post">
								<?php $cbxscratingreview_review_logs->views(); ?>

								<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
								<?php $cbxscratingreview_review_logs->search_box( esc_html__( 'Search Review Log', 'cbxscratingreview' ), 'cbxscratingreviewlogsearch' ); ?>

								<?php $cbxscratingreview_review_logs->display() ?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear clearfix"></div>
	</div>
</div>