<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.pandaonline.com
 * @since      1.0.0
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/src
 */

namespace Ptpkg\lib\core;

use Ptpkg\admin\Controller as AdminController;
use Ptpkg\admin\Settings;
use Ptpkg\front\BookingForm;
use Ptpkg\front\Controller as FrontController;
use Ptpkg\lib\common\Api;
use Ptpkg\lib\common\CustomPostTypes;
use Ptpkg\lib\common\JwtAuth;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ptpkg
 * @subpackage Ptpkg/src
 * @author     Ammon Casey <acasey@panda-group.com>
 */
class Init
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Ptpkg_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = PTPKG_NAME;
        if (defined('PTPKG_VERSION')) {
            $this->version = PTPKG_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->loader = new Loader();

        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Ptpkg_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Internationalization();
        $plugin_i18n->set_domain($this->get_plugin_name());

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new AdminController($this->get_plugin_name(), $this->get_version());
        $plugin_settings = new Settings($this->get_plugin_name(), $this->get_version());
        $plugin_post_types = new CustomPostTypes($this->get_plugin_name(), 'package', 'book');

        /**
         * register our ptpkg_api_settings_init to the admin_init action hook
         */
        // $this->loader->add_action('admin_menu', $plugin_settings, 'ptpkg_options_page');
        $this->loader->add_action('admin_menu', $plugin_settings, $this->plugin_name . '_settings_page');
        $this->loader->add_action('admin_init', $plugin_settings, $this->plugin_name . '_settings_init');
        $this->loader->add_action('admin_post_ptpkg_authorize_client', $plugin_settings, 'ptpkg_handle_authorize_client');

        /**
         * Add metabox and register custom fields
         *
         * @link https://code.tutsplus.com/articles/rock-solid-wordpress-30-themes-using-custom-post-types--net-12093
         */
        $this->loader->add_action('admin_init', $plugin_admin, 'package_add_meta_boxes');
        $this->loader->add_action('post_submitbox_misc_actions', $plugin_admin, 'package_build_api_checkbox');
        $this->loader->add_action('save_post', $plugin_admin, 'package_save_meta');
        $this->loader->add_action('rest_api_init', $plugin_admin, 'package_rest_meta_fields');
        $this->loader->add_action('init', $plugin_admin, 'add_categories_to_attachments');
        // $this->loader->add_filter('is_protected_meta', $plugin_admin, 'pacakge_seo_protected_meta', 10, 2);

        /**
         * Change list columns in customers list
         *
         * manage_<custom post type>_<type>_columns (posts, pages <- type of {custom} post type)
         */
        $this->loader->add_filter('manage_packages_posts_columns', $plugin_admin, 'packages_list_edit_columns');
        // manage_<type>_custom_column, type: pages or posts
        $this->loader->add_action('manage_pages_custom_column', $plugin_admin, 'packages_list_custom_columns', 10, 2);

        /**
         * The problem with the initial activation code is that when the activation hook runs, it's after the init hook has run,
         * so hooking into init from the activation hook won't do anything.
         * You don't need to register the CPT within the activation function unless you need rewrite rules to be added
         * via flush_rewrite_rules() on activation. In that case, you'll want to register the CPT normally, via the
         * loader on the init hook, and also re-register it within the activation function and
         * call flush_rewrite_rules() to add the CPT rewrite rules.
         *
         * @link https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/261
         */
        $this->loader->add_action('init', $plugin_post_types, 'create_custom_post_type');

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 10, 1);
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts', 10, 1);
        $this->loader->add_action('admin_enqueue_scripts', $plugin_settings, 'enqueue_styles', 10, 1);
        $this->loader->add_action('admin_enqueue_scripts', $plugin_settings, 'enqueue_scripts', 10, 1);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $api = new Api();
        $plugin_post_types = new CustomPostTypes($this->get_plugin_name(), 'package', 'book');
        $plugin_public = new FrontController($this->get_plugin_name(), $this->get_version(), $plugin_post_types);
        $plugin_booking_form = new BookingForm($this->get_plugin_name(), $this->get_version(), $plugin_post_types, $api);
        $plugin_jwt_auth = new JwtAuth($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('init', $plugin_post_types, 'add_custom_endpoint');
        $this->loader->add_action('wp_head', $plugin_post_types, 'set_custom_permalink_filter');
        $this->loader->add_filter('query_vars', $plugin_post_types, 'add_custom_query_vars_filter');
        $this->loader->add_filter('template_include', $plugin_post_types, 'get_custom_post_type_templates');

        $this->loader->add_action('rest_api_init', $plugin_jwt_auth, 'add_api_routes');
        $this->loader->add_filter('rest_api_init', $plugin_jwt_auth, 'add_cors_support');
        $this->loader->add_filter('determine_current_user', $plugin_jwt_auth, 'determine_current_user', 10);
        $this->loader->add_filter('rest_pre_dispatch', $plugin_jwt_auth, 'rest_pre_dispatch', 10, 2);

        // $this->loader->add_action('admin_post_ptpkg_booking_form', $plugin_booking_form, 'handle_booking_form');
        // $this->loader->add_action('admin_post_ptpkg_booking_form', $plugin_booking_form, 'handle_booking_form');
        // $this->loader->add_action('wp_ajax_nopriv_ptpkg_booking_form', $plugin_booking_form, 'handle_booking_form');
        // $this->loader->add_action('wp_ajax_ptpkg_booking_form', $plugin_booking_form, 'handle_booking_form');
        $this->loader->add_action('rest_api_init', $plugin_booking_form, 'add_api_routes');
        $this->loader->add_action('wp_head', $plugin_booking_form, 'add_csrf_token');
        $this->loader->add_action('wp_head', $plugin_booking_form, 'add_authnet_key');

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_booking_form, 'enqueue_scripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Ptpkg_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
