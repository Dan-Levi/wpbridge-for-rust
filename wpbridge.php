<?php
/**
 * Plugin Name: WPBridge for Rust
 * Plugin URI: https://wpbridge.danlevi.no
 * Author: Dan-Levi Tømta
 * Author URI: https://www.danlevi.no
 * Version: 0.0.4-alpha
 * Text Domain: wpbridge
 * Description: Integrates your Wordpress site with a Rust server to show player statistics and server information.
*/

if( !defined('ABSPATH') ) : exit(); endif;

class WPBRIDGE
{
    public $plugin_version = '0.0.4';
    private static $_instance = null;

    public function __construct()
    {
        $this->DefineConstants();
        $this->CheckVersion();
        $this->InitInstall();
        $this->InitUnInstall();
        $this->InitSettings();
        $this->InitRestApi();
    }

    /**
     * Check version
     */
    public function CheckVersion()
    {
        $plugin_version = get_option('WPBRIDGE_PLUGIN_VERSION',0);
        if(WPBRIDGE_PLUGIN_VERSION > $plugin_version)
        {
            define('WPBRIDGE_NEEDS_UPGRADE',1);
        }
    }

    /**
     * Settings
     */
    public function InitSettings()
    {
        require_once WPBRIDGE_PATH . 'inc/settings.php';
    }

    /**
     * Rest API
     */
    public function InitRestApi()
    {
        require_once WPBRIDGE_PATH . 'inc/rest_api.php';
    }

    /**
     * Install
     */
    public function InitInstall()
    {
        if(is_admin()) {
            require_once WPBRIDGE_PATH . '/install.php';
        }
    }

    /**
     * Uninstall
     */
    public function InitUnInstall()
    {
        if(is_admin()) {
            require_once WPBRIDGE_PATH . '/uninstall.php';
        }
    }

    /**
     * Constants
     */
    public function DefineConstants()
    {
        global $wpdb;
        define( 'WPBRIDGE_PLUGIN_VERSION', $this->plugin_version);
        define( 'WPBRIDGE_PATH', trailingslashit( plugin_dir_path(__FILE__) ) );
        define( 'WPBRIDGE_URL', trailingslashit( plugins_url('/', __FILE__) ) );
        define( 'WPBRIDGE_SETTINGS_TABLE', $wpdb->prefix . 'wpbridge_settings' );
        define( 'WPBRIDGE_PLAYER_STATS_TABLE', $wpdb->prefix . 'wpbridge_player_stats' );
    }

    /**
     * Singleton
     */
    static function instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPBRIDGE();
        }
        return self::$_instance;
    }
}

WPBRIDGE::instance();