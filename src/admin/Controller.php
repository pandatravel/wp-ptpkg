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

use Ptpkg\lib\Exopite_Template;

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
        $teaser = get_post_meta($post->ID, 'package-teaser', true);
        $banner = get_post_meta($post->ID, 'package-banner', true);
        $price = get_post_meta($post->ID, 'package-price', true);
        $location = get_post_meta($post->ID, 'package-location', true);
        $seoAd = get_post_meta($post->ID, 'package-seo-ad', true);
        $seoContent = get_post_meta($post->ID, 'package-seo-content', true);

        echo '<div>';
        echo $this->banner_image_upload_field(get_post_meta($post->ID, 'package-banner', true));
        echo '<p><h3>' . _e('Teaser:', $this->plugin_name) . '</h3><input type="text" name="teaser" value="' . esc_textarea($teaser) . '" class="widefat"></p>';
        echo '<p><h3>' . _e('Price:', $this->plugin_name) . '</h3><input type="text" name="price" value="' . esc_textarea($price) . '" class="widefat"></p>';
        echo '<p><h3>' . _e('Location:', $this->plugin_name) . '</h3><input type="text" name="location" value="' . esc_textarea($location) . '" class="widefat"></p>';
        echo '<p><h3>' . _e('SEO Ad:', $this->plugin_name) . '</h3><input type="text" name="seo_ad" value="' . esc_textarea($seoAd) . '" class="widefat"></p>';
        echo '<p><h3>' . _e('SEO Content:', $this->plugin_name) . '</h3><input type="text" name="seo_content" value="' . esc_textarea($seoContent) . '" class="widefat"></p>';
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

        if (isset($_REQUEST['banner_image'])) {
            // $banner_id = (int) $_POST["banner_image"];
            update_post_meta($post_id, "package-banner", (int) $_POST["banner_image"]);
        }
        if (isset($_REQUEST['teaser'])) {
            update_post_meta($post_id, "package-teaser", sanitize_text_field($_POST["teaser"]));
        }
        if (isset($_REQUEST['price'])) {
            update_post_meta($post_id, "package-price", sanitize_text_field($_POST["price"]));
        }
        if (isset($_REQUEST['location'])) {
            update_post_meta($post_id, "package-location", sanitize_text_field($_POST["location"]));
        }
        if (isset($_REQUEST['seo_ad'])) {
            update_post_meta($post_id, "package-seo-ad", sanitize_text_field($_POST["seo_ad"]));
        }
        if (isset($_REQUEST['seo_content'])) {
            update_post_meta($post_id, "package-seo-content", sanitize_text_field($_POST["seo_content"]));
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

        register_rest_field('package', 'package-banner', $register_rest_field_args);
        register_rest_field('package', 'package-teaser', $register_rest_field_args);
        register_rest_field('package', 'package-price', $register_rest_field_args);
        register_rest_field('package', 'package-location', $register_rest_field_args);
        register_rest_field('package', 'package-seo-ad', $register_rest_field_args);
        register_rest_field('package', 'package-seo-content', $register_rest_field_args);
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
     * @param string $name Name of option or name of post custom field.
     * @param string $value Optional Attachment ID
     * @return string HTML of the Upload Button
     */
    public function metabox_field($name, $value = '', $type = 'text')
    {
        $placeholders = [
            'label' => _e(ucfirst($name), $this->plugin_name),
            'id'    => $name,
            'name'  => $name,
            'type'  => $type,
            'value' => esc_textarea($value),
        ];

        $template = new Exopite_Template;
        $template::$variables_array = $placeholders;
        $template::$filename = PTPKG_TPL_DIR . 'metabox/input-field.html';
        echo $template::get_template();
    }

    /*
     * @param string $name Name of option or name of post custom field.
     * @param string $value Optional Attachment ID
     * @return string HTML of the Upload Button
     */
    public function banner_image_upload_field($banner = '')
    {
        global $content_width, $_wp_additional_image_sizes;
        $old_content_width = $content_width;
        $content_width = 300;
        $content_hight = 96;

        if ($banner && get_post($banner)) {
            if (! isset($_wp_additional_image_sizes['post-thumbnail'])) {
                $thumbnail_html = wp_get_attachment_image($banner, [ $content_width, $content_hight ]);
            } else {
                $thumbnail_html = wp_get_attachment_image($banner, 'post-thumbnail');
            }
            if (! empty($thumbnail_html)) {
                $content = $thumbnail_html;
                $content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_banner_image_button" >' . esc_html__('Remove banner image', 'text-domain') . '</a></p>';
                $content .= '<input type="hidden" id="upload_banner_image" name="banner_image" value="' . esc_attr($banner) . '" />';
            }
            $content_width = $old_content_width;
        } else {
            $content = '<img src="" style="width:' . esc_attr($content_width) . 'px;height:auto;border:0;display:none;" />';
            $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__('Set banner image', 'text-domain') . '" href="javascript:;" id="upload_banner_image_button" id="set-banner-image" class="btn btn-primary" data-uploader_title="' . esc_attr__('Choose an image', 'text-domain') . '" data-uploader_button_text="' . esc_attr__('Set banner image', 'text-domain') . '">' . esc_html__('Set banner image', 'text-domain') . '</a></p>';
            $content .= '<input type="hidden" id="upload_banner_image" name="banner_image" value="" />';
        }
        return $content;
    }

    /*
     * @param string $name Name of option or name of post custom field.
     * @param string $value Optional Attachment ID
     * @return string HTML of the Upload Button
     */
    public function banner_image_uploader_field($name, $value = '')
    {
        $image = ' button">Upload image';
        $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
        $display = 'none'; // display state ot the "Remove image" button

        if ($image_attributes = wp_get_attachment_image_src($value, $image_size)) {

            // $image_attributes[0] - image URL
            // $image_attributes[1] - image width
            // $image_attributes[2] - image height

            $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
            $display = 'inline-block';
        }

        return '
        <div>
            <a href="#" class="ptpkg_upload_image_button' . $image . '</a>
            <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
            <a href="#" class="ptpkg_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a>
        </div>';
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

        wp_enqueue_style($this->plugin_name . '-styles', plugins_url('/../../assets/admin/css/ptpkg-admin.css', __FILE__), [], $this->version, 'all');
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

        wp_enqueue_script($this->plugin_name . '-scripts', plugins_url('/../../assets/admin/js/ptpkg-admin.js', __FILE__), [ 'jquery' ], $this->version, false);
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
