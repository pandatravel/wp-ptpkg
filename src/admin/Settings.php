<?php

/**
 * Controls settings of plugin
 *
 * @link       https://www.pandaonline.com
 * @since      1.0.0
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/admin
 */

namespace Ptpkg\admin;

use Ptpkg\lib\common\Api;
use Ptpkg\lib\common\ExopiteTemplate;

/**
 * The admin-settings functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/admin
 * @author     Ammon Casey <acasey@panda-group.com>
 */
class Settings
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
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $option_name    The ID of this plugin.
     */
    private $option_name;

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
     * @var      string    $plugin_name       The name of this plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->option_name = $plugin_name;
        $this->version = $version;
    }

    /*
    |--------------------------------------------------------------------------
    | Options Page
    |--------------------------------------------------------------------------
    |
    | Add options page to plugin
    |
    */

    /**
     * Register the options page.
     *
     * @since    1.0.0
     * @access   public
     */
    public function ptpkg_options_page()
    {
        add_options_page(
            __('PT Packages Settings', $this->plugin_name),
            __('PT Packages', $this->plugin_name),
            'manage_options',
            $this->plugin_name,
            [$this, 'display_options_page']
         );
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function ptpkg_settings_page()
    {
        global $settings_page;
        /**
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         * add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
         *
         * @link https://codex.wordpress.org/Function_Reference/add_options_page
         */
        $settings_page = add_submenu_page(
            'edit.php?post_type=package',
            __('PT Packages Settings', $this->plugin_name),
            __('API Settings', $this->plugin_name),
            'manage_options',
            $this->plugin_name,
            [$this, 'display_settings_page']
        );
    }

    /**
     * Render the options page for plugin
     *
     * @since  1.0.0
     * @access   public
     */
    public function display_options_page()
    {
        include_once 'partials/ptpkg-admin-display.php';
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_settings_page()
    {
        include_once('partials/' . $this->plugin_name . '-admin-settings.php');
    }

    /**
     * Creates settings sections with fields etc.
     *
     * @since    1.0.0
     * @access   public
     */
    public function ptpkg_settings_init()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        // register_setting( $option_group, $option_name, $settings_sanitize_callback );
        register_setting($this->plugin_name, $this->option_name . '_client_id', [$this, 'validate']);
        register_setting($this->plugin_name, $this->option_name . '_client_secret', [$this, 'validate']);
        register_setting($this->plugin_name, $this->option_name . '_token', [$this, 'validate']);
        register_setting($this->plugin_name, $this->option_name . '_auth_state');

        if (empty(get_option($this->option_name . '_token'))) {
            update_option($this->plugin_name . '_auth_state', false, true);
        }

        // add_settings_section( $id, $title, $callback, $menu_slug );
        add_settings_section(
            $this->option_name . '_api_settings',
            __('PTPkg Api Authentication', $this->plugin_name),
            [$this, $this->option_name . '_api_settings_cb'],
            $this->plugin_name
        );

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
        add_settings_field(
            $this->option_name . '_client_id',
            __('Api Client ID', $this->plugin_name),
            [$this, $this->option_name . '_client_id_render'],
            $this->plugin_name,
            $this->option_name . '_api_settings',
            [
                'label_for' => $this->option_name . '_client_id',
                'class' => 'form-control',
            ]
        );

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
        add_settings_field(
            $this->option_name . '_client_secret',
            __('Api Client Secret', $this->plugin_name),
            [$this, $this->option_name . '_client_secret_render'],
            $this->plugin_name,
            $this->option_name . '_api_settings',
            ['label_for' => $this->option_name . '_client_secret']
        );

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
        add_settings_field(
            $this->option_name . '_token',
            __('Api Token', $this->plugin_name),
            [$this, $this->option_name . '_token_render'],
            $this->plugin_name,
            $this->option_name . '_api_settings',
            ['label_for' => $this->option_name . '_token']
        );
    }

    /**
     * Render the general section
     *
     * @since  1.0.0
     * @access   public
     */
    public function ptpkg_api_settings_cb()
    {
        echo '<p>' . __('Please enter your api credentials from <a href="' . PTPKG_URL . '/settings/tokens">ptpgk.dev</a>', 'ptpkg') . '</p>';
    }

    /**
     * Render the text input field for client_id option
     *
     * @since  1.0.0
     * @access   public
     */
    public function ptpkg_client_id_render()
    {
        $field = $this->option_name . '_client_id';
        $placeholders = [
            'id'    => $field,
            'name'  => $field,
            'type'  => 'text',
            'class' => 'form-control regular-text',
            'value' => get_option($field),
        ];

        $template = new ExopiteTemplate;
        $template::$variables_array = $placeholders;
        $template::$filename = PTPKG_TPL_DIR . 'settings/input-field.html';
        echo $template::get_template();
    }

    /**
     * Render the text input field for client_id option
     *
     * @since  1.0.0
     * @access   public
     */
    public function ptpkg_client_secret_render()
    {
        $field = $this->option_name . '_client_secret';
        $placeholders = [
            'id'    => $field,
            'name'  => $field,
            'type'  => 'password',
            'class' => 'form-control regular-text',
            'value' => get_option($field),
        ];

        $template = new ExopiteTemplate;
        $template::$variables_array = $placeholders;
        $template::$filename = PTPKG_TPL_DIR . 'settings/input-field.html';
        echo $template::get_template();
    }

    /**
     * Render the text input field for token option
     *
     * @since  1.0.0
     * @access   public
     */
    public function ptpkg_token_render()
    {
        $field = $this->option_name . '_token';
        $placeholders = [
            'id'       => $field,
            'name'     => $field,
            'rows'     => '5',
            'class'    => 'form-control regular-text',
            'property' => 'readonly',
            'value' => get_option($field),
        ];

        $template = new ExopiteTemplate;
        $template::$variables_array = $placeholders;
        $template::$filename = PTPKG_TPL_DIR . 'settings/textarea-field.html';
        echo $template::get_template();
    }

    /**
     * Settings - Validates saved options
     *
     * @since       1.0.0
     * @param       array       $input          array of submitted plugin options
     * @return      array                       array of validated plugin options
     */
    public function settings_sanitize($input)
    {

        // Initialize the new array that will hold the sanitize values
        $new_input = [];

        if (isset($input)) {
            // Loop through the input and sanitize each of the values
            if (is_array($input)) {
                foreach ($input as $key => $val) {
                    $new_input[ $key ] = sanitize_text_field($val);
                }
            } else {
                return sanitize_text_field($input);
            }
        }

        return $new_input;
    } // sanitize()

    public function do_bs_settings_fields($page, $section)
    {
        global $wp_settings_fields;

        if (! isset($wp_settings_fields[$page][$section])) {
            return;
        }

        foreach ((array) $wp_settings_fields[$page][$section] as $field) {
            $class = '';

            if (! empty($field['args']['class'])) {
                $class = ' class="' . esc_attr($field['args']['class']) . '"';
            }

            echo '<div class="form-group row">';

            if (! empty($field['args']['label_for'])) {
                echo '<label class="col-sm-3 col-form-label text-muted" for="' . esc_attr($field['args']['label_for']) . '">' . $field['title'] . '</label>';
            } else {
                echo '<label class="col-sm-3 col-form-label text-muted">' . $field['title'] . '</label>';
            }

            echo '<div class="col-sm-9">';
            call_user_func($field['callback'], $field['args']);
            echo '</div></div>';
        }
    }

    /**
     * Authorize API Client
     *
     * @since    1.0.0
     */
    public function ptpkg_handle_authorize_client($hook)
    {
        // Get an instance of the ptpkg api
        $api = new Api();
        $access_token = $api->authenticateClientCredentials();
        update_option($this->option_name . '_token', $access_token, true);
        update_option($this->option_name . '_auth_state', true, true);

        #redirect back to page
        $redirect_url = get_bloginfo('url') . "/wp-admin/edit.php?post_type=package&page=ptpkg&status=success";
        header("Location: ".$redirect_url);
        exit;
    }

    /**
     * Store accessToken callback
     *
     * @since    1.0.0
     */
    public function handleStoreAccessToken($token)
    {
        update_option($this->option_name . '_token', $token, true);
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook)
    {
        global $settings_page;
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

        if ($hook == $settings_page) {
            wp_enqueue_style($this->plugin_name . '-styles', plugins_url('/../../assets/admin/css/ptpkg-admin.css', __FILE__), [], $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook)
    {
        global $settings_page;
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

        if ($hook == $settings_page) {
            wp_enqueue_script($this->plugin_name . '-scripts', plugins_url('/../../assets/admin/js/ptpkg-admin.js', __FILE__), [ 'jquery' ], $this->version, false);
        }
    }
}
