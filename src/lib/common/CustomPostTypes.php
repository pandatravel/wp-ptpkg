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

namespace Ptpkg\lib\common;

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
     * The custom post type Endpoint
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $endpoint    The Endpoint
     */
    private $endpoint;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     */
    public function __construct($plugin_name, $cpt, $endpoint)
    {
        $this->plugin_name = $plugin_name;
        $this->cpt = $cpt;
        $this->endpoint = $endpoint;
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

    /**
     * add a book endpoint
     * package/{slug}/book
     *
     * @since    1.0.0
     */
    public function add_custom_endpoint()
    {
        add_rewrite_endpoint($this->endpoint, EP_PERMALINK);
    }

    /**
     * add custom query var for endpoint
     *
     * @since    1.0.0
     * @param    string    $vars       The vars
     */
    public function add_custom_query_vars_filter($vars)
    {
        $vars[] = $this->endpoint;
        return $vars;
    }

    /**
     * set custom permalink filter
     *
     * @since    1.0.0
     */
    public function set_custom_permalink_filter()
    {
        $template_file = apply_filters('ptpkg_cpt_single_templates', 'single-' . $this->cpt . '.php');

        if ($this->is_single_template($template_file)) {
            add_filter('post_type_link', [$this, 'custom_endpoint_permalink'], 10, 3);
            return;
        }
    }

    /**
     * custom permalink filter
     *
     * @since    1.0.0
     * @param    string    $permalink  The permalink
     * @param    string    $post       The vars
     * @param    string    $leavename
     */
    public function custom_endpoint_permalink($permalink, $post, $leavename)
    {
        if ($post->post_type != $this->cpt && ! is_single()) {
            return $permalink;
        }
        if (! get_post_meta($post->ID, 'package-api', true)) {
            return $permalink;
        }

        return rtrim($permalink, "/") . DIRECTORY_SEPARATOR . $this->endpoint . DIRECTORY_SEPARATOR;
    }

    /**
     * locate_template
     *
     * @since    1.0.0
     * @param    template  $template       The template
     * @param    string    $templates_dir  The plugin template dir
     * @param    string    $page_type      The page_type
     * @param    string    $endpoint       The endpoint
     */
    public function locate_template($template, $templates_dir = 'templates', $page_type = 'single', $endpoint = false)
    {
        $template_file = $page_type . '-' . $this->cpt . ($endpoint ? '-' . $this->endpoint : '') . '.php';

        $theme_files = [
            $template_file,
            $this->plugin_name . DIRECTORY_SEPARATOR . $template_file,
        ];

        $theme_template = locate_template($theme_files, false);

        if (! empty($theme_template) && ! $this->is_api()) {
            return $theme_template;
        } else {
            $locations = [
                join(DIRECTORY_SEPARATOR, [ rtrim(PTPKG_BASE_DIR, "/"), '' ]),
                join(DIRECTORY_SEPARATOR, [ rtrim(PTPKG_BASE_DIR, "/"), $templates_dir, '' ]), //plugin $templates_dir folder
            ];

            foreach ($locations as $location) {
                if (file_exists($location . $template_file)) {
                    return apply_filters('ptpkg_locate_template', $location . $template_file);
                }
            }
        }

        return $template;
    }

    /**
     * get the post type template from the plugin
     *
     * @since    1.0.0
     * @param    string    $template       The name of the plugin.
     */
    public function get_custom_post_type_templates($template)
    {
        global $post, $wp_query;
        $templates_dir = 'templates';

        if ($this->cpt == get_post_type() && is_single()) {
            $endpoint = isset($wp_query->query[$this->endpoint]);
            return $this->locate_template($template, $templates_dir, 'single', $endpoint);
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

    /**
     * check if the file is the current single template
     *
     * @since    1.0.0
     */
    public function is_single_template($template_file = null)
    {
        global $template;

        if (is_null($template_file)) {
            $template_file = 'single-' . $this->cpt . '.php';
        }

        if ($template_file != basename($template)) {
            return false;
        }

        $theme_locations = [
            join(DIRECTORY_SEPARATOR, [ get_stylesheet_directory(), '' ]),
            join(DIRECTORY_SEPARATOR, [ get_stylesheet_directory(), $this->plugin_name, '' ]),
        ];

        $locations = [
            join(DIRECTORY_SEPARATOR, [ rtrim(PTPKG_BASE_DIR, "/"), '' ]),
            join(DIRECTORY_SEPARATOR, [ rtrim(PTPKG_BASE_DIR, "/"), 'templates', '' ]),
        ];

        foreach ($theme_locations as $location) {
            if (file_exists($location . $template_file)) {
                if (! $this->is_api()) {
                    return ($location . $template_file == $template);
                }
            }
        }

        foreach ($locations as $location) {
            if (file_exists($location . $template_file)) {
                return ($location . $template_file == $template);
            }
        }
    }

    /**
     * check if the cpt is api enabled
     *
     * @since    1.0.0
     */
    public function is_api()
    {
        global $post;

        $api = (bool) get_post_meta($post->ID, 'package-api', true);

        return $api;
    }
}
