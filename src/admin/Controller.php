<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.pandaonline.com
 * @since      1.0.0
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/admin
 */

namespace Ptpkg\admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/admin
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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /*
    |--------------------------------------------------------------------------
    | Meta Boxes
    |--------------------------------------------------------------------------
    |
    | Adds meta boxes for package custom post type
    |
    */

    /**
     * Add meta box
     *
     * @param post $post The post object
     * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
     */
    public function package_add_meta_boxes($post)
    {
        add_meta_box('package-meta', __('Package Details', $this->plugin_name), [$this, "package_build_meta_box"], "package", "normal", "high");
    }

    /**
     * Build custom field meta box
     *
     * @param post $post The post object
     */
    public function package_build_meta_box($post)
    {
        // Nonce field to validate form request came from current site
        wp_nonce_field(basename(__FILE__), 'package_meta_box_nonce');

        $custom = get_post_custom($post->ID);

        // Get the data if it's already been entered
        $teaser = get_post_meta($post->ID, 'package_teaser', true);
        $price = get_post_meta($post->ID, 'package_price', true);
        $location = get_post_meta($post->ID, 'package_location', true);

        echo '<div class="inside">';
        echo '<p><h3>' . _e('Teaser:', $this->plugin_name) . '</h3><input type="text" name="teaser" value="' . esc_textarea($teaser) . '" class="widefat"></p>';
        echo '<p><h3>' . _e('Price:', $this->plugin_name) . '</h3><input type="text" name="price" value="' . esc_textarea($price) . '" class="widefat"></p>';
        echo '<p><h3>' . _e('Location:', $this->plugin_name) . '</h3><input type="text" name="location" value="' . esc_textarea($location) . '" class="widefat"></p>';
        echo '</div>';
    }

    /**
     * Store custom field meta box data
     *
     * @param int $post_id The post ID.
     * @link https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
     */
    public function package_save_meta($post_id)
    {
        // verify taxonomies meta box nonce
        if (!isset($_POST['package_meta_box_nonce']) || !wp_verify_nonce($_POST['package_meta_box_nonce'], basename(__FILE__))) {
            return;
        }
        // return if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        // Check the user's permissions.
        if (! current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_REQUEST['teaser'])) {
            update_post_meta($post_id, "package_teaser", sanitize_text_field($_POST["teaser"]));
        }
        if (isset($_REQUEST['price'])) {
            update_post_meta($post_id, "package_price", sanitize_text_field($_POST["price"]));
        }
        if (isset($_REQUEST['location'])) {
            update_post_meta($post_id, "package_location", sanitize_text_field($_POST["location"]));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Register meta fields with rest api
    |--------------------------------------------------------------------------
    |
    | Adds meta fields for package custom post type
    |
    */

    /**
     * Show package meta in Api
     *
     * @param int $post_id The post ID.
     * @link https://torquemag.io/2015/07/working-with-post-meta-data-using-the-wordpress-rest-api/
     */
    public function package_rest_meta_fields()
    {
        $register_rest_field_args = [
            'get_callback' => [$this, 'get_package_meta_api'],
            'update_callback' => [$this, 'update_package_meta_api'],
            'schema' => null,
        ];

        register_rest_field('package', 'package_teaser', $register_rest_field_args);
        register_rest_field('package', 'package_price', $register_rest_field_args);
        register_rest_field('package', 'package_location', $register_rest_field_args);
    }

    /**
     * Get package meta via Api
     *
     * @param array $post The post ID.
     * @param string $field_name
     * @param $request
     * @link https://torquemag.io/2015/07/working-with-post-meta-data-using-the-wordpress-rest-api/
     */
    public function get_package_meta_api($post, $field_name, $request)
    {
        return get_post_meta($post['id'], $field_name, $request);
    }

    /**
     * Update package meta via Api
     *
     * @param strign $value
     * @param Obj $post The post object.
     * @param string $field_name
     * @link https://torquemag.io/2015/07/working-with-post-meta-data-using-the-wordpress-rest-api/
     */
    public function update_package_meta_api($value, $post, $field_name)
    {
        return update_post_meta($post->ID, $field_name, sanitize_text_field($value));
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Post Type List Columns
    |--------------------------------------------------------------------------
    |
    | Add/Remove/Reorder custom post type list columns (package)
    |
    */

    // Modify columns in packages list in admin area
    public function packages_list_edit_columns($columns)
    {

        // Remove unnecessary columns
        unset(
            $columns['author'],
            $columns['tags']
        );

        // Rename title and add ID and Address
        $columns['title'] = __('Package Name', $this->plugin_name);
        $columns['package_id'] = __('ID', $this->plugin_name);

        /*
         * Rearrange column order
         *
         * Now define a new order. you need to look up the column
         * names in the HTML of the admin interface HTML of the table header.
         *
         *     "cb" is the "select all" checkbox.
         *     "title" is the title column.
         *     "date" is the date column.
         *     "icl_translations" comes from a plugin (in this case, WPML).
         *
         * change the order of the names to change the order of the columns.
         *
         * @link http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
         */
        $customOrder = ['cb', 'title', 'package_id', 'icl_translations', 'date'];

        /*
         * return a new column array to wordpress.
         * order is the exactly like you set in $customOrder.
         */
        foreach ($customOrder as $column_name) {
            $rearranged[$column_name] = $columns[$column_name];
        }

        return $rearranged;
    }

    // Populate new columns in packages list in admin area
    public function packages_list_custom_columns($column)
    {
        global $post;
        $custom = get_post_custom();

        // Populate column form meta
        switch ($column) {
            case "package_id":
                echo $custom["package_id"][0];
                break;

        }
    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/admin/css/ptpkg-admin.css', [], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/admin/js/ptpkg-admin.js', [ 'jquery' ], $this->version, false);
    }

    /**
     * Returns plugin for settings page
     *
     * @since       1.0.0
     * @return      string    $plugin_name       The name of this plugin
     */
    public function get_plugin()
    {
        return $this->plugin_name;
    }
}
