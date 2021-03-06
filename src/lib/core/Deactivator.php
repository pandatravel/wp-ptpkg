<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.pandaonline.com
 * @since      1.0.0
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/includes
 */

namespace Ptpkg\lib\core;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ptpkg
 * @subpackage Ptpkg/includes
 * @author     Ammon Casey <acasey@panda-group.com>
 */
class Deactivator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}
