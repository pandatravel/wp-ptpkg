<?php

/**
 * Handle Booking form requests
 *
 * @link       https://www.pandaonline.com
 * @since      1.0.0
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/lib
 */

namespace Ptpkg\front;

use Ptpkg\lib\common\Api;
use Ptpkg\lib\common\CustomPostTypes;

/**
 * Fired by admin ajax hook
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ptpkg
 * @subpackage Ptpkg/includes
 * @author     Ammon Casey <acasey@panda-group.com>
 */
class BookingForm
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
     *
     * @var string The current version of this plugin.
     */
    private $version;

    /**
     * The namespace to add to the api calls.
     *
     * @var string The namespace to add to the api call
     */
    private $namespace;

    /**
     * The CustomPostType class
     *
     * @var Ptpkg\lib\common\CustomPostTypes
     */
    private $cpt;

    /**
     * Store errors to display if the JWT is wrong
     *
     * @var WP_Error
     */
    private $error = null;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version, CustomPostTypes $cpt, Api $api)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->namespace = $this->plugin_name . '/v'.intval($this->version);
        $this->cpt = $cpt;
        $this->api = $api;
    }

    /**
     * Add the endpoints to the API
     */
    public function add_api_routes()
    {
        register_rest_route($this->namespace, 'package', [
            'methods' => 'POST',
            'callback' => [$this, 'book_package'],
        ]);
    }

    /**
     * Add the endpoints to the API
     */
    public function add_csrf_token()
    {
        if ($this->cpt->is_single_template('single-package-book.php')) {
            echo '<meta name="csrf-token" content="' . wp_create_nonce('wp_rest') . '">';
        }
    }

    /**
     * Filter to hook the rest_pre_dispatch, if the is an error in the request
     * send it, if there is no error just continue with the current request.
     *
     * @param $request
     */
    public function book_package($request)
    {
        $valid = $this->validate_csrf($request);

        if ($valid !== true) {
            return $valid;
        }

        $data = $request->get_params();
        $response = $this->api->get_client()->orders()->create($data);

        return $response;
    }

    /**
     * Validate the csrf-token
     *
     * @param $request
     */
    public function validate_csrf($request)
    {
        /*
         * Validate the HTTP_X_WP_NONCE header, if not present just
         * return an error
         */
        $token = $request->get_header('X-WP-Nonce');

        if (is_null($token)) {
            return new \WP_Error(
                $this->plugin_name . '_no_csrf_token_header',
                __('csrf-token not found.', $this->plugin_name),
                [
                    'status' => 403,
                ]
            );
        }

        if (! wp_verify_nonce($token, 'wp_rest')) {
            return new \WP_Error(
                $this->plugin_name . '_csrf_token_invalid',
                __('csrf-token invalid.', $this->plugin_name),
                [
                    'status' => 403,
                ]
            );
        }

        return true;
    }

    /**
     * Register the JavaScript for the package booking form
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        global $post;
        /**
         *  In backend there is global ajaxurl variable defined by WordPress itself.
         *
         * This variable is not created by WP in frontend. It means that if you want to use AJAX calls in frontend, then you have to define such variable by yourself.
         * Good way to do this is to use wp_localize_script.
         *
         * @link http://wordpress.stackexchange.com/a/190299/90212
         */
        if ($this->cpt->is_single_template('single-package.php') || $this->cpt->is_single_template('single-package-book.php')) {
            $package = $this->api->get_client()->tours()->show_wp($post->ID);
            // wp_localize_script($this->plugin_name . '-app', 'wp_ajax', $wp_ajax);
            // wp_enqueue_script($this->plugin_name . '-data', plugins_url('assets/public/js/ptpkg-data.js', PTPKG_ASSET_DIR), [], $this->version);
            wp_add_inline_script($this->plugin_name . '-app', sprintf('window._ptpkgAPIDataPreload = %s', wp_json_encode($package['data'])), 'before');
        }
    }
}
