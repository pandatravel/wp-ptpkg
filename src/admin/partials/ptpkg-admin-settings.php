<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.pandaonline.com
 * @since      1.0.0
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/admin/partials
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

use Carbon\Carbon;
use Ptpkg\admin\Settings;

$settings = new Settings(PTPKG_NAME, PTPKG_VERSION);
$token = get_option(PTPKG_NAME . '_token');

$active_tab = isset($_GET[ 'tab' ]) ? $_GET[ 'tab' ] : 'api_options';
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h2><span class="dashicons dashicons-tickets-alt"></span> PT Package Options</h2>
    <h2 class="nav-tab-wrapper">
        <a href="?post_type=package&page=ptpkg-settings&tab=api_options" class="nav-tab <?php echo $active_tab == 'api_options' ? 'nav-tab-active' : ''; ?>">API Settings</a>
        <a href="?post_type=package&page=ptpkg-settings&tab=authnet_options" class="nav-tab <?php echo $active_tab == 'authnet_options' ? 'nav-tab-active' : ''; ?>">Authnet Settings</a>
    </h2>
    <div id="ptpkg-api-settings" class="container-xl">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-10 col-lg-9">
                <?php if ($active_tab == 'api_options'): ?>
                <div class="card">
                    <div class="card-header bg-white text-primary">
                        <strong class="card-title"><?php echo esc_html(get_admin_page_title()); ?></strong>
                        <span class="card-subtitle mb-2 text-muted"><?php $settings->ptpkg_api_settings_cb() ?></span>
                    </div>

                    <form action="options.php" method="post">
                        <div class="card-body">
                            <?php
                            settings_fields($this->plugin_name);
                            $settings->do_bs_settings_fields($this->plugin_name . '-settings', $this->plugin_name . '_api_settings');
                            ?>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type="submit" class="btn btn-primary" name="ptpkg_settings_submit" value="Save Changes">
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="card-footer">
                        <form action="admin-post.php" method="post">
                            <input type="hidden" name="action" value="ptpkg_authorize_client" />
                            <input type="submit" class="btn btn-success btn-sm float-right" name="ptpkg_authorize_client" value="Authenticate API Client">
                        </form>
                        <?php if (get_option(PTPKG_NAME . '_auth_state') == true): ?>
                        <span class="form-text text-muted"><span class="badge badge-<?php echo($token->hasExpired() ? 'danger' : 'secondary') ?> text-uppercase"><?php echo($token->hasExpired() ? 'Expired' : 'Authenticated') ?></span> Token Expires: <?php echo Carbon::createFromTimestampUTC($token->getExpires())->diffForHumans(); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-header bg-white text-primary">
                        <strong class="card-title">Authnet Credentials</strong>
                        <span class="card-subtitle mb-2 text-muted"><?php $settings->ptpkg_authnet_settings_cb() ?></span>
                    </div>

                    <form action="options.php" method="post">
                        <div class="card-body">
                            <?php
                            settings_fields($this->plugin_name . '_authnet_settings');
                            $settings->do_bs_settings_fields($this->plugin_name . '-settings', $this->plugin_name . '_authnet_settings');
                            ?>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <input type="submit" class="btn btn-primary" name="ptpkg_settings_submit" value="Save Changes">
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

