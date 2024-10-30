<?php
	/**
	 * Provides review list filter box
	 *
	 * This file is used to markup frontend review list filter box
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

	$enable_positive_critical = intval( $cbxscratingreview_setting->get_option( 'enable_positive_critical', 'cbxscratingreview_common_config', 1 ) );
    $layout                    = esc_attr( $cbxscratingreview_setting->get_option( 'layout', 'cbxscratingreview_common_config', 'layout_default' ) );

	$all_reviews_link = get_permalink();

	$orderby = get_query_var( 'orderby', 'id' );
	$order   = get_query_var( 'order', 'DESC' );
	$score   = isset( $_GET['score'] ) ? intval( $_GET['score'] ) : '';

	$style = ( $total_reviews == 0 ) ? ' display: none;' : '';

?>

<div class="clearfix cbxscratingreview_clearfix clear"></div>
<div style="<?php echo $style; ?>" class="cbxscratingreview_search_wrapper cbxscratingreview_search_wrapper_<?php echo $layout;?>  cbxscratingreview_search_wrapper_js" id="cbxscratingreview_search_wrapper_<?php echo intval( $post_id ); ?>" data-busy="0" data-postid="<?php echo intval( $post_id ); ?>" data-perpage="<?php echo intval( $perpage ); ?>" data-orderby="<?php echo $orderby; ?>" data-order="<?php echo $order; ?>">
	<div class="form-inline" action="">
		<label class="sr-only" for="score"><?php esc_html_e( 'Score', 'cbxscratingreview' ); ?></label> <select class="custom-select form-control cbxscratingreview_search_filter_score cbxscratingreview_search_filter_js" name="score">
			<?php

				$score_vals = CBXSCRatingReviewHelper::get_score_filters();
				foreach ( $score_vals as $score_val => $score_label ) {
					echo '<option ' . selected( $score, $score_val, false ) . ' value="' . $score_val . '">' . $score_label . '</option>';
				}
			?>
		</select> <label class="sr-only" for="search_reviews_orderby"><?php esc_html_e( 'Order By', 'cbxscratingreview' ); ?></label> <select class="custom-select form-control cbxscratingreview_search_filter_orderby cbxscratingreview_search_filter_js" name="orderby">
			<option <?php selected( $orderby, 'id' ); ?> value="id"><?php esc_html_e( 'Most Recent', 'cbxscratingreview' ); ?></option>
			<option <?php selected( $orderby, 'score' ); ?> value="score"><?php esc_html_e( 'Rating Score', 'cbxscratingreview' ); ?></option>
		</select> <label class="sr-only" for="search_archive_order"><?php esc_html_e( 'Order', 'cbxscratingreview' ); ?></label> <select class="custom-select form-control cbxscratingreview_search_filter_order cbxscratingreview_search_filter_js" name="order">
			<option <?php selected( $order, 'DESC' ); ?> value="DESC"><?php esc_html_e( 'DESC', 'cbxscratingreview' ); ?></option>
			<option <?php selected( $order, 'ASC' ); ?> value="ASC"><?php esc_html_e( 'ASC', 'cbxscratingreview' ); ?></option>
		</select>
		<div class="clearfix cbxscratingreview_clearfix clear"></div>
	</div>
</div>
<div class="clearfix cbxscratingreview_clearfix clear"></div>

