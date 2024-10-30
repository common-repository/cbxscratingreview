<?php
	/**
	 * Provide a dashboard rating log view
	 *
	 * This file is used to markup the admin-facing rating log view
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
	global $wpdb;
	$log_id = ( isset( $_GET['id'] ) && intval( $_GET['id'] ) > 0 ) ? intval( $_GET['id'] ) : 0;

	$cbxscratingreview_setting = new CBXSCRatingReviewSettings();
	$require_headline          = intval( $cbxscratingreview_setting->get_option( 'require_headline', 'cbxscratingreview_common_config', 1 ) );
	$require_comment           = intval( $cbxscratingreview_setting->get_option( 'require_comment', 'cbxscratingreview_common_config', 1 ) );


?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<div id="cbxscratingreviewloading" style="display:none"></div>
	<h2>
		<?php esc_html_e( 'Review Log Readonly View', 'cbxscratingreview' ); ?>

	</h2>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder">

			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">


					<div class="postbox">
						<div class="inside cbxscratingreview-review-readonly-section">

							<?php

								$review_info = null;
								if ( $log_id > 0 ) {
									$review_info = cbxscratingreview_singleReview( $log_id );

								}

								if ( ! is_null( $review_info ) ): ?>

									<?php
									$post_id   = isset( $review_info['post_id'] ) ? intval( $review_info['post_id'] ) : 0;
									$review_id = isset( $review_info['id'] ) ? intval( $review_info['id'] ) : 0;
									$score     = isset( $review_info['score'] ) ? floatval( $review_info['score'] ) : 0;
									$headline  = isset( $review_info['headline'] ) ? wp_unslash( $review_info['headline'] ) : '';
									$comment   = isset( $review_info['comment'] ) ? wp_unslash( $review_info['comment'] ) : '';


									$date_created = date_format( date_create( $review_info['date_created'] ), 'Y-m-d' );

									$attachment = isset( $review_info['attachment'] ) ? maybe_unserialize( $review_info['attachment'] ) : array();

									$status = isset( $review_info['status'] ) ? $review_info['status'] : '';

									$exprev_status_arr = CBXSCRatingReviewHelper::ReviewStatusOptions();
									if ( isset( $exprev_status_arr[ $status ] ) ) {
										$status = $exprev_status_arr[ $status ];
									}

									$mod_by = intval( $review_info['mod_by'] );

									if ( $mod_by > 0 ) {
										$mod_by_userdata = get_userdata( $mod_by );
										$date_modified   = date_format( date_create( $review_info['date_modified'] ), 'Y-m-d' );
									}


									$post_link  = get_permalink( intval( $review_info['post_id'] ) );
									$post_title = get_the_title( intval( $review_info['post_id'] ) );
									$post_title = ( $post_title == '' ) ? esc_html__( 'Untitled article', 'cbxscratingreview' ) : $post_title;


									do_action( 'cbxscratingreview_review_rating_adminviewform_before', $post_id, $log_id, $review_info );
									?>
									<table class="widefat">
										<?php
											do_action( 'cbxscratingreview_review_rating_adminviewform_start', $post_id, $log_id, $review_info );
										?>
										<tr valign="top">
											<th class="row-title lowpadding_th" scope="row">
												<?php esc_html_e( 'Written By', 'cbxscratingreview' ); ?>
											</th>

											<td class="lowpadding_th">
												<a target="_blank" href="<?php echo esc_url( get_edit_user_link( intval( $review_info['user_id'] ) ) ) ?>"><?php esc_html_e( $review_info['display_name'] ); ?></a>
											</td>
										</tr>
										<tr valign="top" class="alternate">
											<th class="row-title lowpadding_th" scope="row">
												<label for="tablecell"><?php esc_html_e( 'Created', 'cbxscratingreview' ); ?></label>
											</th>

											<td class="lowpadding_th">
												<?php esc_attr_e( $date_created ); ?>
											</td>
										</tr>
										<tr valign="top">
											<th class="row-title lowpadding_th" scope="row">
												<?php esc_html_e( 'Post', 'cbxscratingreview' ); ?>
											</th>

											<td class="lowpadding_th">
												<a target="_blank" href="<?php echo esc_url( $post_link ); ?>"><?php echo esc_html( $post_title ); ?></a>
											</td>
										</tr>
										<tr valign="top" class="alternate">
											<th class="row-title lowpadding_th" scope="row">
												<?php esc_html_e( 'Rating', 'cbxscratingreview' ); ?>
											</th>

											<td class="lowpadding_th">
												<div class="cbxscratingreview_readonlyrating_score_js" data-processed="0" data-score="<?php echo $score; ?>"></div>
											</td>
										</tr>
										<tr valign="top">
											<th class="row-title lowpadding_th" scope="row">
												<?php esc_html_e( 'Headline', 'cbxscratingreview' ); ?>
											</th>
											<td class="lowpadding_th"><?php echo $headline; ?></td>
										</tr>
										<tr valign="top" class="alternate">
											<th class="row-title lowpadding_th" scope="row">
												<?php esc_html_e( 'Comment', 'cbxscratingreview' ); ?>
											</th>
											<td class="lowpadding_th"><?php echo $comment; ?></td>
										</tr>

										<tr valign="top">
											<th class="row-title lowpadding_th" scope="row">
												<?php esc_html_e( 'Status', 'cbxscratingreview' ); ?>
											</th>
											<td class="lowpadding_th"><?php echo $status; ?></td>
										</tr>
										<?php
											if ( $mod_by > 0 ):

												?>

												<tr valign="top" class="alternate">
													<th class="row-title lowpadding_th" scope="row">
														<label for="tablecell"><?php esc_html_e( 'Last Update', 'cbxscratingreview' ); ?></label>
													</th>
													<td class="lowpadding_th">
														<?php esc_attr_e( $date_modified ); ?>
													</td>
												</tr>
												<tr class="alternate">
													<th class="row-title lowpadding_th" scope="row">
														<label for="tablecell"><?php esc_html_e( 'Last Update By', 'cbxscratingreview' ); ?></label>
													</th>
													<td class="lowpadding_th">
														<a target="_blank" href="<?php echo esc_url( get_edit_user_link( $mod_by ) ) ?>"><?php esc_attr_e( $mod_by_userdata->display_name ); ?></a>
													</td>
												</tr>
											<?php endif; ?>
										<?php
											do_action( 'cbxscratingreview_review_rating_adminviewform_end', $post_id, $log_id, $review_info );
										?>

									</table>
									<?php
									do_action( 'cbxscratingreview_review_rating_adminviewform_after', $post_id, $log_id, $review_info );
									?>

									<p>
										<a class="button-primary" target="_blank" title="<?php esc_html_e( 'Edit Review', 'cbxscratingreview' ); ?>"
										   href="<?php echo admin_url( 'admin.php?page=cbxscratingreviewreviewlist&view=addedit&id=' . $log_id ); ?>"><?php esc_html_e( 'Edit Review', 'cbxscratingreview' ); ?></a>
										<?php echo '<a class="button button-secondary button-large" href="' . admin_url( 'admin.php?page=cbxscratingreviewreviewlist' ) . '">' . esc_attr__( 'Back to Review Lists', 'cbxscratingreview' ) . '</a>'; ?>
									</p>
								<?php else :
									echo '<div class="notice notice-error inline"><p>' . esc_html__( 'No data Found', 'cbxscratingreview' ) . '</p></div>';
									?>
								<?php endif; ?>
						</div> <!-- .inside -->
					</div> <!-- .postbox -->
				</div> <!-- .meta-box-sortables .ui-sortable -->
			</div> <!-- post-body-content -->
		</div> <!-- #post-body .metabox-holder -->
		<div class="clear clearfix"></div>
	</div> <!-- #poststuff -->
</div> <!-- .wrap -->