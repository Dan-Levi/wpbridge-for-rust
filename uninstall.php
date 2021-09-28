<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) return;
class WPBRIDGE_UNINSTALL
{
    private static $_instance = null;

    public function __construct()
    {
        $this->DropDatabaseTables();
    }

    function DropDatabaseTables()
    {
        global $wpdb;
        $sql = "DROP TABLE IF EXISTS `".$wpdb->prefix . 'wpbridge_settings'."`;";
        $wpdb->query($sql);
        $sql = "DROP TABLE IF EXISTS `".$wpdb->prefix . 'wpbridge_player_stats'."`;";
        $wpdb->query($sql);
    }

    function DeleteOptions()
    {
        delete_option('wpbridge_secret');
    }

    static function instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPBRIDGE_UNINSTALL();
        }
        return self::$_instance;
    }
}

WPBRIDGE_UNINSTALL::instance();