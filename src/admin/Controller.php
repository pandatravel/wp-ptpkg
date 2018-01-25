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
        add_meta_box('package-seo-ad-meta', __('Package SEO Ad', $this->plugin_name), [$this, "package_seo_ad_build_meta_box"], "package", "normal", "high");
        add_meta_box('package-seo-content-meta', __('Package SEO Content', $this->plugin_name), [$this, "package_seo_content_build_meta_box"], "package", "normal", "high");
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

        $metafields = '<div>';
        $metafields .= $this->banner_image_upload_field(get_post_meta($post->ID, 'package-banner', true));
        $metafields .= $this->metabox_textarea_field('teaser', get_post_meta($post->ID, 'package-teaser', true));
        $metafields .= $this->metabox_input_field('price', get_post_meta($post->ID, 'package-price', true));
        $metafields .= $this->metabox_input_field('location', get_post_meta($post->ID, 'package-location', true));
        $metafields .= '</div>';

        echo $metafields;
    }

    /**
     * Build custom field meta box
     *
     * @param post $post The post object
     */
    public function package_seo_ad_build_meta_box($post)
    {
        $metafields = '<div>';
        $metafields .= $this->metabox_wysiwyg_field('seo_ad', get_post_meta($post->ID, 'package-seo-ad', true), 'SEO Ad');
        $metafields .= '</div>';

        echo $metafields;
    }

    /**
     * Build custom field meta box
     *
     * @param post $post The post object
     */
    public function package_seo_content_build_meta_box($post)
    {
        $metafields = '<div>';
        $metafields .= $this->metabox_wysiwyg_field('seo_content', get_post_meta($post->ID, 'package-seo-content', true), 'SEO Content');
        $metafields .= '</div>';

        echo $metafields;
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
            update_post_meta($post_id, "package-seo-ad", $_POST["seo_ad"]);
        }
        if (isset($_REQUEST['seo_content'])) {
            update_post_meta($post_id, "package-seo-content", $_POST["seo_content"]);
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
            'get_callback'    => [$this, 'get_package_rest_meta_field'],
            'update_callback' => [$this, 'update_package_rest_meta_field'],
            'schema'          => null,
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
    public function get_package_rest_meta_field($post, $field_name, $request)
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
    public function update_package_rest_meta_field($value, $post, $field_name)
    {
        return update_post_meta($post->ID, $field_name, sanitize_text_field($value));
    }

    /*
     * @param string $name Name of option or name of post custom field.
     * @param string $value Optional Attachment ID
     * @return string HTML of the Upload Button
     */
    public function metabox_input_field($name, $value = '', $label = null, $type = 'text', $class = 'form-control')
    {
        $id = $this->plugin_name . '-' . $name;
        $placeholders = [
            'id'    => $id,
            'name'  => $name,
            'type'  => $type,
            'class' => $class,
            'value' => esc_textarea($value),
            'label' => ucfirst(($label?:$name)),
        ];

        $template = new Exopite_Template;
        $template::$variables_array = $placeholders;
        $template::$filename = PTPKG_TPL_DIR . 'metabox/input-field.html';
        return $template::get_template();
    }

    /*
     * @param string $name
     * @param string $label
     * @param string $value Optional
     *
     * @return string HTML of the Upload Button
     */
    public function metabox_textarea_field($name, $value = '', $label = null, $class = 'form-control')
    {
        $id = $this->plugin_name . '-' . $name;
        $placeholders = [
            'id'    => $id,
            'name'  => $name,
            'class' => $class,
            'value' => esc_textarea($value),
            'label' => ucfirst(($label?:$name)),
            'rows'  => 3,
        ];

        $template = new Exopite_Template;
        $template::$variables_array = $placeholders;
        $template::$filename = PTPKG_TPL_DIR . 'metabox/textarea-field.html';
        return $template::get_template();
    }

    /*
     * @param string $name
     * @param string $label
     * @param string $value Optional
     *
     * @return string HTML of the Upload Button
     */
    public function metabox_wysiwyg_field($name, $value = '')
    {
        $id = $this->plugin_name . '-' . $name;
        return wp_editor($value, $id, [
                        'wpautop'       =>  true,
                        'media_buttons' =>  false,
                        'textarea_name' =>  $name,
                        'textarea_rows' =>  5,
                        'teeny'         =>  true
                ]);
    }

    /*
     * @param string $banner Optional Attachment ID
     *
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
                $thumbnail_html = wp_get_attachment_image($banner, [ $content_width, $content_hight ], false, ['style' => 'width:100%;height:auto;']);
            } else {
                $thumbnail_html = wp_get_attachment_image($banner, 'post-thumbnail', false, ['style' => 'width:100%;height:auto;']);
            }
            if (! empty($thumbnail_html)) {
                $content = $thumbnail_html;
                $content .= '<p class="hide-if-no-js"><a href="javascript:;" id="remove_banner_image_button" >' . esc_html__('Remove banner image', 'text-domain') . '</a></p>';
                $content .= '<input type="hidden" id="upload_banner_image" name="banner_image" value="' . esc_attr($banner) . '" />';
            }
            $content_width = $old_content_width;
        } else {
            $content = '<img src="" style="width:100%;height:auto;border:0;display:none;" />';
            $content .= '<p class="hide-if-no-js"><a title="' . esc_attr__('Set banner image', 'text-domain') . '" href="javascript:;" id="upload_banner_image_button" id="set-banner-image" class="btn btn-primary btn-link" data-uploader_title="' . esc_attr__('Choose an image', 'text-domain') . '" data-uploader_button_text="' . esc_attr__('Set banner image', 'text-domain') . '">' . esc_html__('Set banner image', 'text-domain') . '</a></p>';
            $content .= '<input type="hidden" id="upload_banner_image" name="banner_image" value="" />';
        }
        return $content;
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
    public function enqueue_styles($hook)
    {
        global $post;
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

        if ($hook == 'post-new.php' || $hook == 'post.php') {
            if ($post->post_type == 'package') {
                wp_enqueue_style($this->plugin_name . '-styles', plugins_url('/../../assets/admin/css/ptpkg-admin.css', __FILE__), [], $this->version, 'all');
            }
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook)
    {
        global $post;
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

        if ($hook == 'post-new.php' || $hook == 'post.php') {
            if ($post->post_type == 'package') {
                wp_enqueue_script($this->plugin_name . '-scripts', plugins_url('/../../assets/admin/js/ptpkg-admin.js', __FILE__), [ 'jquery' ], $this->version, false);
            }
        }
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
