<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    CBXPoll
 * @subpackage CBXPoll/admin/partials
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="wrap">
    <div class="cbx-backend_container_header">
        <div class="cbx-backend_wrapper cbx-backend_header_wrapper">
            <div class="menu-heading">
                <img title="5 Star Reviews - Settings" src="<?php echo CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/icon_log.svg'
				?>" alt="5 Star Reviews - Settings" width="32" height="32">
                <h2 class="wp-heading-inline wp-heading-inline-setting">
					<?php esc_html_e( '5 Star Reviews - Settings', 'cbxscratingreview' ); ?>
                </h2>
            </div>
            <div class="setting_tool">
                <a href="#" id="save_settings" class="button button-primary"><img
                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAA+0lEQVRIie2UMUpEMRRFT9RGCyO4BBXcgli5AHEBLkF0C84ipnJaWwttBEGsXYJ7sHBmA8dC//An5OdP5Iugni4vyb03vPDgnxrULfVanaqjz1oXzf5YXe3SXEnWY+AU2KzIdQbcqOvLGBxXCLc5AR7V7T6DmuQpB8CzutcurhUuNGaXwEZm/0GNSW0HuAd2s4pJE1/VQzV9ZXM2qpNc99vnQmpQeNHShBDmutl0Q/KjBldADD0AEZh0iZR6EIF94Kgn5BPwArzNRVs9WCD9CeqoMCrSkZH9Rb+7yd9iMBtAc1oyuBvAYEEjHXbngHyM31pmwC1w8bVcf5Z3dIDGLQz4Au0AAAAASUVORK5CYII="/>
					<?php esc_html_e( 'Save Settings', 'cbxscratingreview' ); ?></a>
                <a href="<?php echo admin_url() . 'admin.php?page=cbxscratingreview-help-support' ?>"
                   class="doc_image"><img title="Helps & Updates" src="<?php echo CBXSCRATINGREVIEW_ROOT_URL . 'assets/images/helps.svg'
					?>" alt="" width="30" height="30"></a>
            </div>

        </div>
    </div>

    <div class="cbx-backend_container cbx-backend-settings-container">
        <div class="cbx-backend_wrapper cbx-backend_setting_wrapper">
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-1">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                <!--<h3><span><?php esc_html_e( 'Settings', 'cbxscratingreview' ); ?></span></h3>-->
                                <div class="inside setting-from-warp">
									<?php
									$ref->setting->show_navigation();
									$ref->setting->show_forms();
									?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>