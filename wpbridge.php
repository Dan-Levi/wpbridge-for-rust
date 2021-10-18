<?php
/**
 * Plugin Name: WPBridge for Rust
 * Plugin URI: https://wpbridge.danlevi.no
 * Description: Integrates your Wordpress site with a Rust server to show player statistics and server information.
 * Version: 1.0.130
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Dan-Levi Tømta
 * Author URI: https://www.danlevi.no
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wpbridge-for-rust
 * Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=T7FNEG2D2ELC8
*/

if( !defined('ABSPATH') ) : exit(); endif;

class WPB_F_R_WPBRIDGE
{
    public $plugin_version = '1.0.130';
    private static $_instance = null;

    public function __construct()
    {
        $this->WPB_F_R_DefineConstants();
        $this->WPB_F_R_CheckVersion();
        $this->WPB_F_R_InitInstall();
        $this->WPB_F_R_InitUnInstall();
        $this->WPB_F_R_InitSettings();
        $this->WPB_F_R_InitRestApi();
        $this->WPB_F_R_InitShortCodes();
        $this->WPB_F_R_InitPublic();
    }

    /**
     * Init public
     */
    public function WPB_F_R_InitPublic()
    {
        require_once WPBRIDGE_PATH . 'public/public.php';
    }

    /**
     * Init Shortcodes
     */
    public function WPB_F_R_InitShortCodes()
    {
        require_once WPBRIDGE_PATH . 'inc/shortcodes.php';
    }

    /**
     * Check version
     */
    public function WPB_F_R_CheckVersion()
    {
        $local_plugin_version = get_option('WPBRIDGE_PLUGIN_VERSION','0.0.0');
        if(version_compare(WPBRIDGE_PLUGIN_VERSION,$local_plugin_version,'>='))
        {
            require_once WPBRIDGE_PATH . 'update.php';
        }
    }

    /**
     * Settings
     */
    public function WPB_F_R_InitSettings()
    {
        require_once WPBRIDGE_PATH . 'inc/settings.php';
    }

    /**
     * Rest API
     */
    public function WPB_F_R_InitRestApi()
    {
        require_once WPBRIDGE_PATH . 'inc/rest_api.php';
    }

    /**
     * Install
     */
    public function WPB_F_R_InitInstall()
    {
        if(is_admin()) {
            require_once WPBRIDGE_PATH . '/install.php';
        }
    }

    /**
     * Uninstall
     */
    public function WPB_F_R_InitUnInstall()
    {
        if(is_admin()) {
            require_once WPBRIDGE_PATH . '/uninstall.php';
        }
    }

    /**
     * Constants
     */
    public function WPB_F_R_DefineConstants()
    {
        define( 'WPBRIDGE_PATH', trailingslashit( plugin_dir_path(__FILE__) ) );
        define( 'WPBRIDGE_URL', trailingslashit( plugins_url('/', __FILE__) ) );
        require_once WPBRIDGE_PATH . 'inc/constants.php';
    }

    /**
     * Singleton
     */
    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE();
        }
        return self::$_instance;
    }
}

WPB_F_R_WPBRIDGE::WPB_F_R_instance();