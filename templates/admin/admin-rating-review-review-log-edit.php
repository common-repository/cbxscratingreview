<?php
	/**
	 * Provide a dashboard rating log edit
	 *
	 * This file is used to markup the admin-facing rating log edit
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

	$show_headline = intval( $cbxscratingreview_setting->get_option( 'show_headline', 'cbxscratingreview_common_config', 1 ) );
	$show_comment  = intval( $cbxscratingreview_setting->get_option( 'show_comment', 'cbxscratingreview_common_config', 1 ) );

	$require_headline = intval( $cbxscratingreview_setting->get_option( 'require_headline', 'cbxscratingreview_common_config', 1 ) );
	$require_comment  = intval( $cbxscratingreview_setting->get_option( 'require_comment', 'cbxscratingreview_common_config', 1 ) );


?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<h1>
		<?php echo sprintf( esc_html__( 'Review Log ID: %d', 'cbxscratingreview' ), $log_id ); ?>
	</h1>
	<p><?php echo '<a class="button button-primary button-large" href="' . admin_url( 'admin.php?page=cbxscratingreviewreviewlist' ) . '">' . esc_attr__( 'Back to Review Lists', 'cbxscratingreview' ) . '</a>'; ?></p>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder">

			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3><span><?php esc_html_e( 'Edit Review', 'cbxscratingreview' ); ?></span></h3>
						<div class="inside">
							<?php
								$review_info = null;
								if ( $log_id > 0 ) {
									$review_info = cbxscratingreview_singleReview( $log_id );
									if ( ! is_null( $review_info ) ) {
										$attachment_info = isset( $review_info['attachment'] ) ? maybe_unserialize( $review_info['attachment'] ) : array();

										$post_id  = isset( $review_info['post_id'] ) ? intval( $review_info['post_id'] ) : 0;
										$score    = isset( $review_info['score'] ) ? $review_info['score'] : '';
										$headline = isset( $review_info['headline'] ) ? wp_unslash( $review_info['headline'] ) : '';
										$comment  = isset( $review_info['comment'] ) ? wp_unslash( $review_info['comment'] ) : '';
										$status   = isset( $review_info['status'] ) ? $review_info['status'] : '';

										$status_arr = CBXSCRatingReviewHelper::ReviewStatusOptions();
										$mod_by     = intval( $review_info['mod_by'] );
										if ( $mod_by > 0 ) {
											$mod_by_userdata = get_userdata( $mod_by );
											$date_modified   = date_format( date_create( $review_info['date_modified'] ), 'Y-m-d' );
										}

										$date_created = date_format( date_create( $review_info['date_created'] ), 'Y-m-d' );


									}//end review information
								}
							?>
							<?php if ( ! is_null( $review_info ) ) : ?>
								<div class="cbxscratingreview_mainwrap" id="cbxscratingreview_mainwrap">
									<div class="cbxscratingreview-form-section">
										<div class="cbxscratingreview_global_msg"></div>

										<?php
											do_action( 'cbxscratingreview_review_rating_admineditform_before', $post_id, $log_id, $review_info );
										?>

										<form class="cbxscratingreview-form" method="post" enctype="multipart/form-data" data-busy="0" data-postid="<?php echo intval( $post_id ); ?>">

											<table class="widefat">
												<tbody>
												<tr>
													<td class="row-title">
														<label for="tablecell"><?php esc_html_e( 'Written By', 'cbxscratingreview' ); ?></label>
													</td>
													<td>
														<a target="_blank" href="<?php echo esc_url( get_edit_user_link( intval( $review_info['user_id'] ) ) ) ?>"><?php esc_html_e( $review_info['display_name'] ); ?></a>
													</td>
												</tr>
												<tr class="alternate">
													<td class="row-title">
														<label for="tablecell"><?php esc_html_e( 'Created', 'cbxscratingreview' ); ?></label>
													</td>
													<td>
														<?php esc_attr_e( $date_created ); ?>
													</td>
												</tr>
												<tr>
													<td class="row-title">
														<label for="tablecell"><?php esc_html_e( 'Post', 'cbxscratingreview' ); ?></label>
													</td>
													<td>
														<a target="_blank" href="<?php echo esc_url( get_permalink( intval( $review_info['post_id'] ) ) ) ?>"><?php esc_attr_e( get_the_title( intval( $review_info['post_id'] ) ) ); ?></a>
													</td>
												</tr>
												<?php
													if ( $mod_by > 0 ): ?>
														<tr class="alternate">
															<td class="row-title">
																<label for="tablecell"><?php esc_html_e( 'Last Update', 'cbxscratingreview' ); ?></label>
															</td>
															<td>
																<?php esc_attr_e( $date_modified ); ?>
															</td>
														</tr>
														<tr class="alternate">
															<td class="row-title">
																<label for="tablecell"><?php esc_html_e( 'Last Update By', 'cbxscratingreview' ); ?></label>
															</td>
															<td>
																<a target="_blank" href="<?php echo esc_url( get_edit_user_link( $mod_by ) ) ?>"><?php esc_attr_e( $mod_by_userdata->display_name ); ?></a>
															</td>
														</tr>
													<?php endif; ?>

												</tbody>
											</table>
											<br />
											<?php
												do_action( 'cbxscratingreview_review_rating_admineditform_start', $post_id, $log_id, $review_info );
											?>

											<div class="cbxscratingreview-form-field">
												<label for="cbxscratingreview_rating_score" class=""><?php esc_html_e( 'Your rating', 'cbxscratingreview' ); ?></label>
												<div data-score="<?php echo $score; ?>" class="cbxscratingreview_rating_trigger cbxscratingreview_rating_trigger_<?php echo $post_id; ?>"></div>
												<input type="hidden" name="cbxscratingreview_ratingForm[score]" class="cbxscratingreview-form-field-input cbxscratingreview-form-field-input-hidden cbxscratingreview_rating_score" value="<?php echo $score; ?>" required data-rule-required="true" data-rule-min="0.5" data-rule-max="5" />
											</div>
											<div class="cbxscratingreview-form-field">
												<label for="cbxscratingreview_review_headline" class=""><?php esc_html_e( 'Title', 'cbxscratingreview' ); ?></label>
												<input type="text" name="cbxscratingreview_ratingForm[headline]" id="cbxscratingreview_review_headline" class="cbxscratingreview-form-field-input cbxscratingreview-form-field-input-text cbxscratingreview_review_headline" <?php echo ( $require_headline && $show_headline ) ? 'required' : ''; ?> data-rule-minlength="2" placeholder="<?php esc_html_e( 'One line review', 'cbxscratingreview' ); ?>" value="<?php echo esc_attr( wp_unslash( $headline ) ); ?>" />
											</div>
											<div class="cbxscratingreview-form-field">
												<label for="cbxscratingreview_review_comment" class=""><?php esc_html_e( 'Your Review', 'cbxscratingreview' ); ?></label>

												<?php wp_editor( $comment, 'cbxscratingreview_review_comment', $settings = array(
													'teeny'         => true,
													'media_buttons' => false,
													'textarea_name' => 'cbxscratingreview_ratingForm[comment]',
													'editor_class'  => 'cbxscratingreview-form-field-input cbxscratingreview-form-field-input-vtextarea cbxscratingreview_review_comment',
													'textarea_rows' => 8,
													'quicktags'     => false,
													'tinymce'       => array(
														'init_instance_callback' => 'function(editor) {
							editor.on("change", function(){
								tinymce.triggerSave();
								jQuery("#" + editor.id).valid();
						    });
						}'
													)
												) ); ?>
											</div>


											<?php
												do_action( 'cbxscratingreview_review_rating_admineditform_end', $post_id, $log_id, $review_info );
											?>
											<div class="cbxscratingreview-form-field">

												<label for="cbxscratingreview_review_status"><?php esc_html_e( 'Status', 'cbxscratingreview' ); ?></label>

												<select name="cbxscratingreview_ratingForm[status]">
													<?php
														foreach ( $status_arr as $status_key => $status_name ) { ?>
															<option value="<?php echo $status_key; ?>" <?php if ( $status_key == $status ) {
																echo 'selected="selected"';
															} ?>><?php echo $status_name; ?></option>
														<?php } ?>
												</select>

											</div>
											<input type="hidden" id="cbxscratingreview-review-id" name="cbxscratingreview_ratingForm[log_id]" value="<?php echo $log_id; ?>" /> <input type="hidden" id="cbxscratingreview-post-id" name="cbxscratingreview_ratingForm[post_id]" value="<?php echo $post_id; ?>" />
											<p class="label-cbxscratingreview-submit-processing" style="display: none;"><?php esc_html_e( 'Please wait, we are taking account of your review. Do not close this window.', 'cbxscratingreview' ) ?></p>
											<button type="submit" class="btn btn-primary button button-primary btn-cbxscratingreview-submit cbxscratingreview-submit-style"><?php esc_html_e( 'Submit Edit', 'cbxscratingreview' ); ?></button>

										</form>
										<?php
											do_action( 'cbxscratingreview_review_rating_admineditform_after', $post_id, $log_id, $review_info );
										?>
									</div>
								</div>
							<?php else :
								echo '<div class="notice notice-error inline"><p>' . esc_html__( 'Sorry No data Found', 'cbxscratingreview' ) . '</p></div>';
								?>
							<?php endif; ?>
						</div> <!-- .inside -->
					</div>
				</div>
			</div>
		</div>
		<div class="clear clearfix"></div>
	</div>
</div>