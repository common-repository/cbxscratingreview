<?php
	/**
	 * Provides frontend  rating form
	 *
	 * This file is used to markup frontend rating form
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


	$show_headline    = intval( $cbxscratingreview_setting->get_option( 'show_headline', 'cbxscratingreview_common_config', 1 ) );
	$show_comment     = intval( $cbxscratingreview_setting->get_option( 'show_comment', 'cbxscratingreview_common_config', 1 ) );
	$require_headline = intval( $cbxscratingreview_setting->get_option( 'require_headline', 'cbxscratingreview_common_config', 1 ) );
	$require_comment  = intval( $cbxscratingreview_setting->get_option( 'require_comment', 'cbxscratingreview_common_config', 1 ) );

    $layout           = esc_attr( $cbxscratingreview_setting->get_option( 'layout', 'cbxscratingreview_common_config', 'layout_default' ) );


?>

<div class="cbxscratingreview_mainwrap cbxscratingreview_mainwrap_<?php echo esc_attr( $layout ); ?> cbxscratingreview_mainwrap_<?php echo esc_attr( $scope ); ?>">
	<?php
		if ( $title != '' ) {
			echo '<h3 class="cbxscratingreview-title cbxscratingreview-title-reviewform">' . $title . '</h3>';
		}
	?>
	<div class="cbxscratingreview-form-section">
		<div class="cbxscratingreview_global_msg"></div>

		<?php
			do_action( 'cbxscratingreview_review_rating_form_before', $post_id );
		?>

		<form class="cbxscratingreview-form" method="post" enctype="multipart/form-data" data-busy="0" data-postid="<?php echo intval( $post_id ); ?>">
			<?php
				do_action( 'cbxscratingreview_review_rating_form_start', $post_id );
			?>
			<div class="cbxscratingreview-form-field">
				<label for="cbxscratingreview_rating_score" class=""><?php esc_html_e( 'Your rating', 'cbxscratingreview' ); ?></label>
				<div class="cbxscratingreview_rating_trigger cbxscratingreview_rating_trigger_<?php echo $post_id; ?>"></div>
				<input type="hidden" name="cbxscratingreview_ratingForm[score]" class="cbxscratingreview-form-field-input cbxscratingreview-form-field-input-hidden cbxscratingreview_rating_score" value="" required data-rule-required="true" data-rule-min="0.5" data-rule-max="5" />
			</div>
			<?php if ( $show_headline ): ?>
				<div class="cbxscratingreview-form-field">
					<label for="cbxscratingreview_review_headline" class=""><?php esc_html_e( 'Title', 'cbxscratingreview' ); ?></label> <input type="text" name="cbxscratingreview_ratingForm[headline]" id="cbxscratingreview_review_headline" class="cbxscratingreview-form-field-input cbxscratingreview-form-field-input-text cbxscratingreview_review_headline" <?php echo ( $require_headline ) ? 'required' : ''; ?> data-rule-minlength="2" placeholder="<?php esc_html_e( 'One line review', 'cbxscratingreview' ); ?>">
				</div>
			<?php endif; ?>
			<?php if ( $show_comment ): ?>
				<div class="cbxscratingreview-form-field">
					<label for="cbxscratingreview_review_comment" class=""><?php esc_html_e( 'Your Review', 'cbxscratingreview' ); ?></label>

					<?php wp_editor( '', 'cbxscratingreview_review_comment', $settings = array(
						'teeny'         => true,
						'media_buttons' => false,
						'textarea_name' => 'cbxscratingreview_ratingForm[comment]',
						'editor_class'  => 'cbxscratingreview-form-field-input cbxscratingreview-form-field-input-vtextarea cbxscratingreview_review_comment',
						'textarea_rows' => 8,
						'quicktags'     => false,
						'content_css'   => get_stylesheet_directory_uri() . '/editor-styles.css',
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
			<?php endif; ?>
			<?php
				do_action( 'cbxscratingreview_review_rating_form_end', $post_id );
			?>
			<input type="hidden" name="cbxscratingreview_ratingForm[post_id]" value="<?php echo intval( $post_id ); ?>" />
			<p class="label-cbxscratingreview-submit-processing" style="display: none;"><?php esc_html_e( 'Please wait, we are taking account of your review. Do not close this window.', 'cbxscratingreview' ) ?></p>
			<button type="submit" class="btn btn-primary btn-cbxscratingreview-submit cbxscratingreview-submit-style"><?php esc_html_e( 'Submit Rating & Review', 'cbxscratingreview' ); ?></button>

		</form>
		<?php
			do_action( 'cbxscratingreview_review_rating_form_after', $post_id );
		?>
	</div>
</div>