<?php

/**
 * Template loader for PTPKG Plugin.
 *
 * Only need to specify class properties here.
 *
 */

namespace Ptpkg\lib;

/**
 * The Custom Template Loader.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/lib
 * @author     Ammon Casey <acasey@panda-group.com>
 */
class CustomTemplateLoader extends \Gamajo_Template_Loader
{

    /**
     * Prefix for filter names.
     *
     * @since 1.0.0
     * @type string
     */
    protected $filter_prefix = 'ptpkg';

    /**
     * Directory name where custom templates for this plugin should be found in the theme.
     *
     * @since 1.0.0
     * @type string
     */
    protected $theme_template_directory = 'ptpkg';

    /**
     * Reference to the root directory path of this plugin.
     *
     * @since 1.0.0
     * @type string
     */
    protected $plugin_directory = PTPKG_BASE_DIR;

    /**
     * Directory name where templates are found in this plugin.
     *
     * Can either be a defined constant, or a relative reference from where the subclass lives.
     *
     * e.g. 'templates' or 'includes/templates', etc.
     *
     * @since 1.1.0
     *
     * @var string
     */
    protected $plugin_template_directory = 'templates';

    public function setPluginTemplateDir($dir)
    {
        $this->plugin_template_directory = $dir;
    }
}
