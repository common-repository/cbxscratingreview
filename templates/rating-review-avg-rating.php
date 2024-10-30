<?php
	/**
	 * Provides avg rating
	 *
	 * This file is used to markup the avg rating html
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


	do_action( 'cbxscratingreview_avg_rating_before', $avg_rating_info );

	$show_chart_wrapper_class = ( $show_chart ) ? 'cbxscratingreview_template_avg_rating_readonly_chart' : '';
?>
<?php if ( $show_star || $show_score ): ?>
	<div class="cbxscratingreview_template_avg_rating_readonly <?php echo esc_attr( $show_chart_wrapper_class ); ?>">

		<?php if ( $show_star ): ?>
			<span data-processed="0" data-score="<?php echo floatval( $avg_rating_info['avg_rating'] ); ?>" class="cbxscratingreview_readonlyrating cbxscratingreview_readonlyrating_score cbxscratingreview_readonlyrating_score_js"></span>
		<?php endif; ?>

		<?php if ( $show_score ): ?>
			<span class="cbxscratingreview_readonlyrating cbxscratingreview_readonlyrating_info" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
				<span style="display: none;" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
					<span itemprop="name"><?php echo get_the_title( $avg_rating_info['post_id'] ); ?></span>
				</span>

				<meta itemprop="worstRating" content="1">
				<i itemprop="ratingValue"><?php echo number_format_i18n( $avg_rating_info['avg_rating'], 2 ) . '</i>/<i itemprop="bestRating">' . number_format_i18n( 5 ); ?></i> (<i itemprop="ratingCount"><?php echo intval( $avg_rating_info['total_count'] ); ?></i> <?php echo ( $avg_rating_info['total_count'] == 0 ) ? esc_html__( ' Review', 'cbxscratingreview' ) : _n( 'Review', 'Reviews', $avg_rating_info['total_count'], 'cbxscratingreview' ); ?>)
	    </span>
		<?php endif; ?>
	</div>
<?php endif; ?>
<?php if ( $show_chart ): ?>
	<div class="cbxscratingreview_template_avg_rating_readonly_chart">
		<?php


			$chart_html         = '<div class="' . apply_filters( 'cbxscratingreview_template_avg_rating_chart_class', 'cbxscratingreview_template_avg_rating_chart', $avg_rating_info ) . '">';
			$rating_stat_scores = $avg_rating_info['rating_stat_scores'];
			for ( $score = 5; $score > 0; $score -- ) {
				$rating_stat_score = isset( $rating_stat_scores[ $score ] ) ? $rating_stat_scores[ $score ] : array(
					'count'   => 0,
					'percent' => 0
				);

				$chart_html        .= '<p>';
				$chart_html        .= '<span title="' . sprintf( esc_html__( '%s %%, %s Reviews', 'cbxscratingreview' ), number_format_i18n( $rating_stat_score['percent'], 2 ), number_format_i18n( intval( $rating_stat_score['count'] ), 0 ) ) . '" style="width: ' . $rating_stat_score['percent'] . '%;" class="cbxscratingreview_template_avg_rating_chart_graph cbxscratingreview_template_avg_rating_chart_graph_' . $score . '">';
				$chart_html        .= '<i class="cbxscratingreview_template_avg_rating_chart_score cbxscratingreview_template_avg_rating_chart_score_' . $score . '">' . intval( $score ) . '</i>';
				if ( intval( $rating_stat_score['count'] ) > 0 ) {
					$chart_html .= '<i class="cbxscratingreview_template_avg_rating_chart_stat cbxscratingreview_template_avg_rating_chart_stat_' . $score . '">' . sprintf( esc_html__( '%s %% (%s Reviews)', 'cbxscratingreview' ), number_format_i18n( $rating_stat_score['percent'], 0 ), number_format_i18n( intval( $rating_stat_score['count'] ), 0 ) ) . '</i>';
					$chart_html .= '<i class="cbxscratingreview_template_avg_rating_chart_stat cbxscratingreview_template_avg_rating_chart_stat_mobile cbxscratingreview_template_avg_rating_chart_stat_' . $score . '">' . sprintf( esc_html__( '%s %%', 'cbxscratingreview' ), number_format_i18n( $rating_stat_score['percent'], 0 ) ) . '</i>';
				}
				$chart_html .= '</p>';
			}

			$chart_html .= '</div>';

			echo apply_filters( 'cbxscratingreview_template_avg_rating_chart', $chart_html, $avg_rating_info );

		?>
	</div>
<?php endif; ?>
<?php
	do_action( 'cbxscratingreview_avg_rating_after', $avg_rating_info );
