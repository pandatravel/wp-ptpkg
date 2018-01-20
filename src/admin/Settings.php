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

use Ptpkg\lib\Exopite_Template;

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
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @var      string    $plugin_name       The name of this plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name)
    {
        $this->plugin_name = $plugin_name;
        $this->option_name = $plugin_name;
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

        // add_settings_section( $id, $title, $callback, $menu_slug );
        add_settings_section(
            $this->option_name . '_settings_general',
            __('PTPkg Api Authentication', $this->plugin_name),
            [$this, $this->option_name . '_settings_general_cb'],
            $this->plugin_name
        );

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
        add_settings_field(
            $this->option_name . '_client_id',
            __('Api Client ID', $this->plugin_name),
            [$this, $this->option_name . '_client_id_render'],
            $this->plugin_name,
            $this->option_name . '_settings_general',
            ['label_for' => $this->option_name . '_client_id']
        );

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
        add_settings_field(
            $this->option_name . '_client_secret',
            __('Api Client Secret', $this->plugin_name),
            [$this, $this->option_name . '_client_secret_render'],
            $this->plugin_name,
            $this->option_name . '_settings_general',
            ['label_for' => $this->option_name . '_client_secret']
        );

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
        add_settings_field(
            $this->option_name . '_token',
            __('Api Token', $this->plugin_name),
            [$this, $this->option_name . '_token_render'],
            $this->plugin_name,
            $this->option_name . '_settings_general',
            ['label_for' => $this->option_name . '_token']
        );
    }

    /**
     * Render the general section
     *
     * @since  1.0.0
     * @access   public
     */
    public function ptpkg_settings_general_cb()
    {
        echo '<p>' . __('Please enter your api credentials.', 'ptpkg') . '</p>';
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
            'value' => get_option($field),
        ];

        $template = new Exopite_Template;
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
            'id' 	=> $field,
            'name'	=> $field,
            'type'	=> 'text',
            'value'	=> get_option($field),
        ];

        $template = new Exopite_Template;
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
            'id' 	=> $field,
            'name'	=> $field,
            'type'	=> 'text',
            'value'	=> get_option($field),
        ];

        $template = new Exopite_Template;
        $template::$variables_array = $placeholders;
        $template::$filename = PTPKG_TPL_DIR . 'settings/input-field.html';
        echo $template::get_template();
    }

    /**
     * Settings - Validates saved options
     *
     * @since 		1.0.0
     * @param 		array 		$input 			array of submitted plugin options
     * @return 		array 						array of validated plugin options
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
}
