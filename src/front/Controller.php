<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.pandaonline.com
 * @since      1.0.0
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/font
 */

namespace Ptpkg\front;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/public
 * @author     Ammon Casey <acasey@panda-group.com>
 */
class Controller
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function locate_template($template, $settings, $page_type)
    {
        $theme_files = [
            $page_type . '-' . $settings['custom_post_type'] . '.php',
            $this->plugin_name . DIRECTORY_SEPARATOR . $page_type . '-' . $settings['custom_post_type'] . '.php',
        ];

        $exists_in_theme = locate_template($theme_files, false);

        if ($exists_in_theme != '') {

            // Try to locate in theme first
            return $template;
        } else {

            // Try to locate in plugin base folder,
            // try to locate in plugin $settings['templates'] folder,
            // return $template if non of above exist
            $locations = [
                join(DIRECTORY_SEPARATOR, [ WP_PLUGIN_DIR, $this->plugin_name, '' ]),
                join(DIRECTORY_SEPARATOR, [ WP_PLUGIN_DIR, $this->plugin_name, $settings['templates_dir'], '' ]), //plugin $settings['templates'] folder
            ];

            foreach ($locations as $location) {
                if (file_exists($location . $theme_files[0])) {
                    return $location . $theme_files[0];
                }
            }

            return $template;
        }
    }

    public function get_custom_post_type_templates($template)
    {
        global $post;

        $settings = [
            'custom_post_type' => 'exopite-portfolio',
            'templates_dir' => 'templates',
        ];

        if ($settings['custom_post_type'] == get_post_type() && is_single()) {
            return $this->locate_template($template, $settings, 'single');
        }

        return $template;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ptpkg_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ptpkg_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/public/css/ptpkg-public.css', [], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ptpkg_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ptpkg_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/public/js/ptpkg-public.js', [ 'jquery' ], $this->version, false);
    }
}
