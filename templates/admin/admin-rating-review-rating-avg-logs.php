<?php
	/**
	 * Provide a dashboard rating log avgs listing
	 *
	 * This file is used to markup the admin-facing rating avg log listing
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
	$cbxscratingreview_rating_avg_logs = new CBXSCRatingReviewRatingAvgLog_List_Table();

	//Fetch, prepare, sort, and filter CBXSCRatingReviewRatingAvgLog data
	$cbxscratingreview_rating_avg_logs->prepare_items();
?>

<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Average Log Manager', 'cbxscratingreview' ); ?>
	</h1>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<div class="inside">
							<form id="cbxscratingreview_rating_avg_logs" method="post">
								<?php $cbxscratingreview_rating_avg_logs->views(); ?>

								<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
								<?php $cbxscratingreview_rating_avg_logs->search_box( esc_html__( 'Search Rating Avg Log', 'cbxscratingreview' ), 'experienceratingavglogsearch' ); ?>

								<?php $cbxscratingreview_rating_avg_logs->display() ?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear clearfix"></div>
	</div>
</div>