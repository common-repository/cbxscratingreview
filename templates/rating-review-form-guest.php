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


	/*$show_headline    = intval( $cbxscratingreview_setting->get_option( 'show_headline', 'cbxscratingreview_common_config', 1 ) );
	$show_comment     = intval( $cbxscratingreview_setting->get_option( 'show_comment', 'cbxscratingreview_common_config', 1 ) );
	$require_headline = intval( $cbxscratingreview_setting->get_option( 'require_headline', 'cbxscratingreview_common_config', 1 ) );
	$require_comment  = intval( $cbxscratingreview_setting->get_option( 'require_comment', 'cbxscratingreview_common_config', 1 ) );*/


?>

<div class="cbxscratingreview_mainwrap cbxscratingreview_mainwrap_guest cbxscratingreview_mainwrap_<?php echo esc_attr( $scope ); ?>">
	<?php
		if ( $title != '' ) {
			echo '<h3 class="cbxscratingreview-title cbxscratingreview-title-reviewform">' . $title . '</h3>';
		}
	?>
	<div class="cbxscratingreview-form-section">
		<div class="cbxscratingreview_global_msg"></div>

		<?php
			do_action( 'cbxscratingreview_review_rating_form_guest_before', $post_id );
		?>

		<p class="cbxscratingreview-form-guest-note"><?php echo apply_filters( 'cbxscratingreview_guest_login_note', __( 'Please <a href="#">login</a> to rate and review', 'cbxscratingreview' ), $post_id ); ?></p>
		<div class="cbxscratingreview-form-guest-login-wrapper">
			<?php

			    $redirect_url = get_permalink( $post_id );
				$login_form_html = wp_login_form( array(
					'redirect' => get_permalink( $post_id ),
					'echo'     => false
				) );

				echo apply_filters( 'cbxscratingreview_guest_login_html', $login_form_html, $post_id, $redirect_url );

			$guest_register_html = '';
			$guest_show_register   = intval($cbxscratingreview_setting->get_option( 'guest_show_register', 'cbxscratingreview_common_config', 1 ));
			if ( $guest_show_register ) {
				if(get_option( 'users_can_register' )){
					$register_url = add_query_arg( 'redirect_to', urlencode( $redirect_url ), wp_registration_url() );
					$guest_register_html .= '<p class="cbxscratingreview-guest-register">'.sprintf(__('No account yet? <a href="%s">Register</a>', 'cbxscratingreview'), $register_url).'</p>';
				}

				echo apply_filters('cbxscratingreview_guest_register_html', $guest_register_html, $redirect_url);

			}
			?>
		</div>
		<?php
			do_action( 'cbxscratingreview_review_rating_form_guest_after', $post_id);
		?>
	</div>
</div>