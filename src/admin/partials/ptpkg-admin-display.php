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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <form action="options.php" method="post">
        <?php
            settings_fields($this->plugin_name);
            do_settings_sections($this->plugin_name);
            submit_button();
        ?>
    </form>
</div>

