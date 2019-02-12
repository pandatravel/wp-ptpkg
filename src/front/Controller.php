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

use Ptpkg\lib\common\CustomPostTypes;

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
     * The CustomPostType class
     *
     * @var Ptpkg\lib\common\CustomPostTypes
     */
    private $cpt;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version, CustomPostTypes $cpt)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->cpt = $cpt;
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

        if ($this->cpt->is_single_template('single-package.php') || $this->cpt->is_single_template('single-package-book.php')) {
            if ($this->cpt->is_api()) {
                $query_args = [
                    'family' => 'Roboto:300,400,500,700|Material+Icons',
                    'subset' => 'latin,latin-ext',
                ];

                wp_enqueue_style($this->plugin_name . '-public', plugins_url('assets/public/css/ptpkg-public.css', PTPKG_ASSET_DIR), [], $this->version, 'all');
                wp_enqueue_style($this->plugin_name . '-google-fonts', add_query_arg($query_args, '//fonts.googleapis.com/css'), [], $this->version, 'all');
            }
        }
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


        if ($this->cpt->is_single_template('single-package.php') || $this->cpt->is_single_template('single-package-book.php')) {
            if ($this->cpt->is_api()) {
                $acceptjs = WP_DEBUG ? 'https://jstest.authorize.net/v1/Accept.js' : 'https://js.authorize.net/v1/Accept.js';

                // wp_enqueue_script($this->plugin_name . '-public', plugins_url('assets/public/js/ptpkg-public.js', PTPKG_ASSET_DIR), ['jquery'], $this->version, true);
                wp_enqueue_script($this->plugin_name . '-app', plugins_url('assets/public/js/ptpkg-app.js', PTPKG_ASSET_DIR), ['jquery'], $this->version, true);
                wp_register_script('AcceptJS', $acceptjs, null, null, true);
                wp_enqueue_script('AcceptJS');
            }
        }
    }
}
