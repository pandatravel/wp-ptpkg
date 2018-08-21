<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.pandaonline.com
 * @since      1.0.0
 *
 * @package    Ptpkg
 * @subpackage Ptpkg/lib
 */

namespace Ptpkg\lib\common;

use Ammonkc\Ptpkg\Client;
use Ptpkg\admin\Settings;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ptpkg
 * @subpackage Ptpkg/lib
 * @author     Ammon Casey <acasey@panda-group.com>
 */
class Api
{

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The ptpkg api client
     *
     * @since    1.0.0
     * @access   protected
     * @var      Ptpkg\Client    $client    The http client
     */
    protected $client;

    /**
     * The ptpkg oauth2 client id
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $client_id    The ptpkg api client id
     */
    protected $client_id;

    /**
     * The ptpkg oauth2 client secret
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $client_secret    The ptpkg api client secret
     */
    protected $client_secret;

    /**
     * The ptpkg oauth2 access token
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $token    The access token
     */
    protected $token;

    /**
     * The ptpkg api options
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $api_options
     */
    protected $api_options = [];

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = PTPKG_NAME;
        $this->client_id = get_option($this->plugin_name . '_client_id');
        $this->client_secret = get_option($this->plugin_name . '_client_secret');
        $this->token = get_option($this->plugin_name . '_token');
        if (WP_DEBUG) {
            $this->api_options = [
                'base_uri' => 'https://ptpkg.dev/',
                'verify' => false,
            ];
        }
        $this->client = new Client($this->api_options);

        if ($this->token) {
            $this->_authenticate();
        }
    }

    /**
     * The api client
     *
     * @since     1.0.0
     * @return    Ammonkc\Ptpkg\Client    The client
     */
    private function _authenticate()
    {
        return $this->client->authenticate($this->client_id, $this->client_secret, $this->token, Client::OAUTH_CLIENT_CREDENTIALS, new Settings($this->plugin_name, PTPKG_VERSION));
    }

    /**
     * The api client
     *
     * @since     1.0.0
     * @return    Ammonkc\Ptpkg\Client    The client
     */
    public function authenticateClientCredentials()
    {
        return $this->client->authenticateClientCredentials($this->client_id, $this->client_secret);
    }

    /**
     * The api client
     *
     * @since     1.0.0
     * @return    Ammonkc\Ptpkg\Client    The client
     */
    public function get_client()
    {
        return $this->client;
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
}
