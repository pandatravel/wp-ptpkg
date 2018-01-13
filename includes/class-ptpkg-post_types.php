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
class Ptpkg_Post_Types
{
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
            'name'               => esc_html__('Packages', 'ptpkg'),
            'singular_name'      => esc_html__('Package', 'ptpkg'),
            'menu_name'          => esc_html__('Packages', 'ptpkg'),
            'parent_item_colon'  => esc_html__('Parent Package', 'ptpkg'),
            'all_items'          => esc_html__('All Packages', 'ptpkg'),
            'add_new'            => esc_html__('Add New', 'ptpkg'),
            'add_new_item'       => esc_html__('Add New Package', 'ptpkg'),
            'edit_item'          => esc_html__('Edit Package', 'ptpkg'),
            'new_item'           => esc_html__('New Package', 'ptpkg'),
            'view_item'          => esc_html__('View Package ', 'ptpkg'),
            'search_items'       => esc_html__('Search Package', 'ptpkg'),
            'not_found'          => esc_html__('Not Found', 'ptpkg'),
            'not_found_in_trash' => esc_html__('Not found in Trash', 'ptpkg'),
        ];

        $args = [
            'labels'             => $labels,
            'description'        => esc_html__('Packages', 'ptpkg'),
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
                'slug' => esc_attr__($this->plugin_name, $this->plugin_name) . '/' . esc_attr__('package', 'ptpkg'),
                'with_front' => false
            ],
        ];

        register_post_type($slug, $args);
    }
}
