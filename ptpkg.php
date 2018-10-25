<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.pandaonline.com
 * @since             1.0.0
 * @package           Ptpkg
 *
 * @wordpress-plugin
 * Plugin Name:       PT Packages
 * Plugin URI:        https://github.com/pandatravel/wp-ptpkg
 * Description:       This plugin connects to panda's tour packages api
 * Version:           1.0.1
 * Author:            Ammon Casey
 * Author URI:        https://github.com/ammonkc
 * License:           UNLICENSED
 * License URI:       https://raw.githubusercontent.com/pandatravel/wp-ptpkg/master/LICENSE
 * Text Domain:       ptpkg
 * Domain Path:       /languages
 */

namespace Ptpkg;

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die('No script kiddies please!');
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PTPKG_VERSION', '1.0.1');
define('PTPKG_TEXTDOMAIN', 'ptpkg');
define('PTPKG_NAME', 'ptpkg');
define('PTPKG_URL', 'https://ptpkg.com');
define('PTPKG_PLUGIN_ROOT', plugin_dir_path(__FILE__));
define('PTPKG_BASE_DIR', plugin_dir_path(__FILE__));
define('PTPKG_TPL_DIR', PTPKG_BASE_DIR . 'templates/');
define('PTPKG_ASSET_DIR', PTPKG_BASE_DIR . 'assets/');

// We load Composer's autoload file
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ptpkg-activator.php
 */
function activate_ptpkg()
{
    lib\core\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ptpkg-deactivator.php
 */
function deactivate_ptpkg()
{
    lib\core\Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_ptpkg');
register_deactivation_hook(__FILE__, 'deactivate_ptpkg');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ptpkg()
{
    $plugin = new lib\core\Init();
    $plugin->run();
}
run_ptpkg();
