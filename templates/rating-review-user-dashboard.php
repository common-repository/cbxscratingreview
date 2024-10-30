<?php
	/**
	 * Provides frontend user dashboard
	 *
	 * This file is used to markup the frontend user dashboard html
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

	do_action( 'cbxscratingreview_user_dashboard_before' );
    ?>
<div id="cbxscratingreview_reviewer_dashboard">


    <?php
	if ( is_user_logged_in() ) {
		if ( function_exists( 'cbxscratingreview_ReviewsByUser' ) ) {
			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;


			$cbxitems_page = isset( $_GET['cbxrpaged'] ) ? intval( $_GET['cbxrpaged'] ) : 1;
			$user_reviews  = cbxscratingreview_ReviewsByUser( $user_id, $perpage, $cbxitems_page, '', $orderby, $order );


			if ( sizeof( $user_reviews ) == 0 ) {
				$user_dashboard_html .= '<div class="alert alert-info" role="alert">' . esc_html__( 'Sorry, no reviews found.', 'cbxscratingreview' ) . '</div>';
			} else {


				$single_review_view_id   = intval( $cbxscratingreview_setting->get_option( 'single_review_view_id', 'cbxscratingreview_tools', 0 ) );
				$single_review_edit_id   = intval( $cbxscratingreview_setting->get_option( 'single_review_edit_id', 'cbxscratingreview_tools', 0 ) );
				$review_userdashboard_id = intval( $cbxscratingreview_setting->get_option( 'review_userdashboard_id', 'cbxscratingreview_tools', 0 ) );
				$allow_review_delete     = intval( $cbxscratingreview_setting->get_option( 'allow_review_delete', 'cbxscratingreview_common_config', 1 ) );

				$single_review_view_url   = get_permalink( intval( $single_review_view_id ) );
				$single_review_edit_url   = get_permalink( intval( $single_review_edit_id ) );
				$review_userdashboard_url = get_permalink( intval( $review_userdashboard_id ) );

				$review_statuses = CBXSCRatingReviewHelper::ReviewStatusOptions();


				$reviewsTotal = cbxscratingreview_totalReviewsCountByUser( $user_id, '', '' );
				?>


				<h2><?php esc_html_e( 'Your Reviews & Ratings', 'cbxscratingreview' ); ?></h2>

				<p class="cbxscratingreview_dashboard_total">
					<strong><?php esc_html_e( 'Total Reviews' ); ?></strong>: <?php echo intval( $reviewsTotal ); ?>
				</p>
				<div class="table-responsive">
					<table class="table table-hover table-bordered table-striped cbxscratingreview_table">
						<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Post', 'cbxscratingreview' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Rating', 'cbxscratingreview' ) ?></th>
							<th scope="col"><?php esc_html_e( 'Headline', 'cbxscratingreview' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Comment', 'cbxscratingreview' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Status', 'cbxscratingreview' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Action', 'cbxscratingreview' ); ?></th>
						</tr>
						</thead>
						<tbody>

						<?php
							foreach ( $user_reviews as $user_review ) {

								$extraparams = maybe_unserialize( $user_review['extraparams'] );
								$status_key  = $user_review['status'];

								?>
								<tr>
									<th scope="row">
										<?php
											$post_id    = intval( $user_review['post_id'] );
											$post_title = get_the_title( $post_id );
											$post_title = ( $post_title == '' ) ? esc_html__( 'Untitled Article', 'cbxscratingreview' ) : $post_title;
											$post_link  = get_permalink( $post_id );

											echo '<a target="_blank" href="' . esc_url( $post_link ) . '" target="_blank">' . $post_title . '</a>';
										?>

									</th>
									<td>
										<?php
											echo number_format_i18n( floatval( $user_review['score'] ), 1 );
										?>
									</td>

									<td>
										<?php
											$headline = wp_unslash( $user_review['headline'] );
											if ( strlen( $headline ) > 25 ) {
												$headline = substr( $headline, 0, 25 ) . '...';
											}

											$headline = ( $headline != '' ) ? $headline : esc_html__( 'View Review', 'cbxscratingreview' );

											if ( $status_key == 1 && function_exists( 'cbxscratingreview_review_permalink' ) ) {
												$review_view_link = cbxscratingreview_review_permalink( $user_review['id'] );
												echo '<a title="' . esc_html__( 'View Review', 'cbxscratingreview' ) . '" target="_blank" href="' . esc_url( $review_view_link ) . '" target="_blank">' . esc_html( $headline ) . '</a>';
											} else {
												echo $headline;
											}
										?>
									</td>
									<td>
										<?php
											$comment = wp_unslash( $user_review['comment'] );
											if ( strlen( $comment ) > 25 ) {
												$comment = substr( $comment, 0, 25 ) . '...';
											}

											echo $comment;

										?>
									</td>
									<td>
										<?php
											$status_key = intval( $user_review['status'] );
											echo isset( $review_statuses[ $status_key ] ) ? $review_statuses[ $status_key ] : esc_html__( 'Unknown', 'cbxscratingreview' );
										?>

									</td>


									<td>
										<?php
											$status_key = intval( $user_review['status'] );


											//only pending and published can be edited
											if ( $status_key == 0 || $status_key == 1 ) {

												$review_edit_link = add_query_arg( 'review_id', $user_review['id'], $single_review_edit_url );
												echo '  <a href="' . esc_url( $review_edit_link ) . '" target="_blank">' . esc_html__( 'Edit', 'cbxscratingreview' ) . '</a>';
											}

											if ( intval( $allow_review_delete ) == 1 ) {
												//allow review delete
											}

											//only published can be viewed
											/*if ( $status_key == 1 ) {

												$review_view_link = add_query_arg( 'review_id', $user_review['id'], $single_review_view_url );
												echo '  <a href="' . esc_url( $review_edit_link ) . '" target="_blank">' . esc_html__( 'View', 'cbxscratingreview' ) . '</a>';
											}*/

										?>
									</td>
								</tr>
								<?php
							}
						?>


						</tbody>
					</table>
				</div>

				<?php


				if ( function_exists( 'cbxscratingreview_paginate_links_as_bootstrap' ) ) {
					echo cbxscratingreview_paginate_links_as_bootstrap( array(
						'format'    => '?cbxrpaged=%#%',
						'total'     => ceil( $reviewsTotal / $perpage ),
						'current'   => max( 1, $cbxitems_page ),
						'nav_class' => 'postnav',

					) );
				}
			}

		}
	}
	else {
		?>
        <p class="cbxscratingreview-form-guest-note"><?php echo apply_filters( 'cbxscratingreview_guest_login_note_dashboard', __( 'Please <a href="#">login</a> to access your ratings & reviews dashboard', 'cbxscratingreview' )); ?></p>
        <div class="cbxscratingreview-form-guest-login-wrapper">
			<?php

            global $post;
            $post_id = $post->ID;
			$redirect_url = get_permalink( $post_id);
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

	}
	?>
</div>
    <?php

	do_action( 'cbxscratingreview_user_dashboard_after' );
