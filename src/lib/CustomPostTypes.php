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

use Doctrine\Common\Inflector\Inflector;

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
     * The custom post type
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $cpt    The Type
     */
    private $cpt;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     */
    public function __construct($plugin_name, $cpt)
    {
        $this->plugin_name = $plugin_name;
        $this->cpt = $cpt;
    }

    /**
     * Create post type "packages"
     *
     * @link https://codex.wordpress.org/Function_Reference/register_post_type
     */
    public function create_custom_post_type()
    {
        $cpt_plural = Inflector::pluralize($this->cpt);

        // custom post type
        $slug = $this->cpt;
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
            'name'               => esc_html__(ucfirst($cpt_plural), $this->plugin_name),
            'singular_name'      => esc_html__(ucfirst($this->cpt), $this->plugin_name),
            'menu_name'          => esc_html__(ucfirst($cpt_plural), $this->plugin_name),
            'parent_item_colon'  => esc_html__('Parent ' . ucfirst($this->cpt), $this->plugin_name),
            'all_items'          => esc_html__('All ' . ucfirst($cpt_plural), $this->plugin_name),
            'add_new'            => esc_html__('Add New', $this->plugin_name),
            'add_new_item'       => esc_html__('Add New ' . ucfirst($this->cpt), $this->plugin_name),
            'edit_item'          => esc_html__('Edit ' . ucfirst($this->cpt), $this->plugin_name),
            'new_item'           => esc_html__('New ' . ucfirst($this->cpt), $this->plugin_name),
            'view_item'          => esc_html__('View '  . ucfirst($this->cpt), $this->plugin_name),
            'search_items'       => esc_html__('Search ' . ucfirst($this->cpt), $this->plugin_name),
            'not_found'          => esc_html__('Not Found', $this->plugin_name),
            'not_found_in_trash' => esc_html__('Not found in Trash', $this->plugin_name),
        ];

        $args = [
            'labels'             => $labels,
            'description'        => esc_html__(ucfirst($cpt_plural), $this->plugin_name),
            'public'             => true,
            'publicly_queryable' => true,
            'exclude_from_search'=> false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'rest_base'          => $cpt_plural,
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
                'slug' => esc_attr__($this->cpt, $this->plugin_name),
                'with_front' => false
            ],
        ];

        register_post_type($slug, $args);
    }

    public function locate_template($template, $settings, $page_type)
    {
        $theme_files = [
            $page_type . '-' . $settings['custom_post_type'] . '.php',
            $this->plugin_name . DIRECTORY_SEPARATOR . $page_type . '-' . $settings['custom_post_type'] . '.php',
        ];

        $theme_template = locate_template($theme_files, false);

        if ($theme_template != '') {
            // Try to locate in theme first
            return $theme_template;
        } else {

            // Try to locate in plugin base folder,
            // try to locate in plugin $settings['templates'] folder,
            // return $template if none of above exist
            $locations = [
                join(DIRECTORY_SEPARATOR, [ PTPKG_BASE_DIR, '' ]),
                join(DIRECTORY_SEPARATOR, [ PTPKG_BASE_DIR, $settings['templates_dir'], '' ]), //plugin $settings['templates'] folder
            ];

            foreach ($locations as $location) {
                if (file_exists($location . $theme_files[0])) {
                    return apply_filters('ptpkg_locate_template', $location . $theme_files[0]);
                }
            }

            return $template;
        }
    }

    public function get_custom_post_type_templates($template)
    {
        global $post;
        $settings = [
            'custom_post_type' => $this->cpt,
            'templates_dir' => 'templates',
        ];

        if ($settings['custom_post_type'] == get_post_type() && is_single()) {
            return $this->locate_template($template, $settings, 'single');
        }

        return $template;
    }

    public function custom_post_type_template_loader($template)
    {
        $loader = new CustomTemplateLoader;
        return $loader->get_template_part('single', $this->cpt);
    }

    /**
     * Override acrive template location for custom post type
     *
     * If the archive template file not exist in the theme folder, then use  the plugin template.
     * In this case, file can be overridden inside the [child] theme.
     *
     * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/archive_template
     * @link http://wordpress.stackexchange.com/a/116025/90212
     */
    public function get_custom_post_type_archive_template()
    {
        global $post;
        $templates_dir = 'templates';

        if (is_post_type_archive($this->cpt)) {
            $theme_files = [
                'archive-' . $this->cpt . '.php',
                $this->plugin_name . '/archive-' . $this->cpt . '.php'
            ];
            $archive_template = locate_template($theme_files, false);
            if ($archive_template != '') {
                // Try to locate in theme first
                return $archive_template;
            } else {
                // Try to locate in plugin templates folder
                if (file_exists(PTPKG_BASE_DIR . '/' . $templates_dir . '/archive-' . $this->cpt . '.php')) {
                    return PTPKG_BASE_DIR . '/' . $templates_dir . '/archive-' . $this->cpt . '.php';
                } elseif (file_exists(PTPKG_BASE_DIR . '/archive-' . $this->cpt . '.php')) {
                    // Try to locate in plugin base folder
                    return PTPKG_BASE_DIR . '/archive-' . $this->cpt . '.php';
                } else {
                    return null;
                }
            }
        }

        return $archive_template;
    }
}
