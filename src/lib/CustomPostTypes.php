<?php

/**
 * Register custom post type
 *
 * @link       http://pandaonline.com
 * @since      1.0.0
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/includes
 */

namespace Ptpkg\lib;

/**
 * The Custom Post Type functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/lib
 * @author     Ammon Casey <acasey@panda-group.com>
 */
class CustomPostTypes
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
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     */
    public function __construct($plugin_name)
    {
        $this->plugin_name = $plugin_name;
    }

    /**
     * Create post type "packages"
     *
     * @link https://codex.wordpress.org/Function_Reference/register_post_type
     */
    public function create_custom_post_type()
    {
        // custom post type
        $slug = 'package';
        $has_archive = true;
        $hierarchical = false;
        $supports = [
            'title',
            'editor',
            'thumbnail',
            'revisions',
        ];
        $taxonomies = [
            'category',
            'post_tag'
        ];

        $labels = [
            'name'               => esc_html__('Packages', $this->plugin_name),
            'singular_name'      => esc_html__('Package', $this->plugin_name),
            'menu_name'          => esc_html__('Packages', $this->plugin_name),
            'parent_item_colon'  => esc_html__('Parent Package', $this->plugin_name),
            'all_items'          => esc_html__('All Packages', $this->plugin_name),
            'add_new'            => esc_html__('Add New', $this->plugin_name),
            'add_new_item'       => esc_html__('Add New Package', $this->plugin_name),
            'edit_item'          => esc_html__('Edit Package', $this->plugin_name),
            'new_item'           => esc_html__('New Package', $this->plugin_name),
            'view_item'          => esc_html__('View Package ', $this->plugin_name),
            'search_items'       => esc_html__('Search Package', $this->plugin_name),
            'not_found'          => esc_html__('Not Found', $this->plugin_name),
            'not_found_in_trash' => esc_html__('Not found in Trash', $this->plugin_name),
        ];

        $args = [
            'labels'             => $labels,
            'description'        => esc_html__('Packages', $this->plugin_name),
            'public'             => true,
            'publicly_queryable' => true,
            'exclude_from_search'=> false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'rest_base'          => 'packages',
            'query_var'          => true,
            'show_in_admin_bar'  => true,
            'capability_type'    => 'post',
            'has_archive'        => $has_archive,
            'hierarchical'       => $hierarchical,
            'supports'           => $supports,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-tickets',
            'taxonomies'         => $taxonomies,
            /* Add $this->plugin_name as translatable in the permalink structure,
               to avoid conflicts with other plugins which may use package as well. */
            'rewrite' => [
                'slug' => esc_attr__('package', $this->plugin_name),
                'with_front' => false
            ],
        ];

        register_post_type($slug, $args);
    }
}
