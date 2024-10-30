<?php
	/**
	 * Provides review list item more button
	 *
	 * This file is used to markup frontend review list item more button
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
$layout                    = esc_attr( $cbxscratingreview_setting->get_option( 'layout', 'cbxscratingreview_common_config', 'layout_default' ) );

echo '<p style="' . ( ( $maximum_pages > 1 ) ? 'display:block;' : 'display:none;' ) . '" class="cbxscratingreview_post_more_reviews cbxscratingreview_post_more_reviews_'.$layout. ' cbxscratingreview_post_more_reviews_js" id="cbxscratingreview_post_more_reviews_' . intval( $post_id ) . '"><a class="cbxscratingreview_loadmore" data-busy="0" data-maxpage="' . $maximum_pages . '" data-score="' . $score . '" data-postid="' . intval( $post_id ) . '" data-perpage="' . intval( $perpage ) . '" data-page="' . ( $page + 1 ) . '" data-orderby="' . $orderby . '" data-order="' . $order . '" href="#">' . esc_html__( 'Load more', 'cbxscratingreview' ) . '</a></p>';