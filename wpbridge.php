<?php
/**
 * Plugin Name: WPBridge for Rust
 * Plugin URI: https://wpbridge.danlevi.no
 * Author: Dan-Levi Tømta
 * Author URI: https://www.danlevi.no
 * Version: 0.0.3-alpha
 * Text Domain: wpbridge
 * Description: Enables your Wordpress site to show player stats from Rust server.
*/

if( !defined('ABSPATH') ) : exit(); endif;

class WPBRIDGE
{

    private static $_instance = null;

    public function __construct()
    {
        $this->DefineConstants();
        $this->InitInstall();
        $this->InitUnInstall();
        $this->InitSettings();
        $this->InitRestApi();
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