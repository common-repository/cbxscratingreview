<?php
	/**
	 * Provide a Admin view for the plugin
	 *
	 * This file is used to markup the admin facing widget form
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

<!-- Custom  Title Field -->
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'cbxscratingreview' ); ?></label>

	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
		   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<!-- Display Limit -->
<p>
	<label for="<?php echo $this->get_field_id( 'limit' ); ?>">
		<?php esc_html_e( 'Display Limit:', "cbxscratingreview" ); ?>
	</label>

	<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>"
		   name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo $limit; ?>" />
</p>

<!-- Order by Selection -->
<p>
	<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php esc_html_e( "Order By", "cbxscratingreview" ) ?>
		<select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
			<option value="post_id" <?php echo ( $orderby == "post_id" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Post ID", "cbxscratingreview" ) ?>     </option>
			<option value="avg_rating" <?php echo ( $orderby == "avg_rating" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Avg Rating", "cbxscratingreview" ) ?> </option>
			<option value="total_count" <?php echo ( $orderby == "total_count" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Rating Count", "cbxscratingreview" ) ?> </option>

		</select> </label>
</p>

<!-- Selection of Ascending or Descending -->
<p>
	<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php esc_html_e( "Order", "cbxscratingreview" ) ?>

		<select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>"
				name="<?php echo $this->get_field_name( 'order' ); ?>">
			<option
				value="asc" <?php echo ( $order == "asc" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Ascending", "cbxscratingreview" ) ?> </option>
			<option
				value="desc" <?php echo ( $order == "desc" ) ? 'selected="selected"' : ''; ?>> <?php esc_html_e( "Descending", "cbxscratingreview" ) ?> </option>
		</select> </label>
</p>
<?php
	$all_post_types = CBXSCRatingReviewHelper::post_types( true );
?>
<p>
	<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php esc_html_e( "Post Type", "cbxscratingreview" ) ?>

		<select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
			<?php
				foreach ( $all_post_types as $post_key => $post_name ) {
					echo '<option value="' . $post_key . '" ' . ( ( $post_key == $type ) ? ' selected="selected" ' : '' ) . '>' . esc_attr( $post_name ) . '</option>';
				}
			?>
		</select> </label>
</p>
<?php
	do_action( 'cbxscratingreviewmrposts_widget_form_admin', $instance, $this );
?>
<input type="hidden" id="<?php echo $this->get_field_id( 'submit' ); ?>" name="<?php echo $this->get_field_name( 'submit' ); ?>" value="1" />
