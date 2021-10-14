<?php

class WPB_F_R_WPBRIDGE_UPDATE
{
    private $_wpdb;
    private $_charset_collate;
    private static $_instance = null;

    public function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->WPB_F_R_UpdatePlayersDataTable();
        $this->WPB_F_R_UpdateSettingsTable();
        $this->WPB_F_R_UpdateLootDataTable();
        $this->WPB_F_R_BumpVersion();
    }

    function WPB_F_R_UpdateLootDataTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `".esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE)."` (
            `id`                        INT(11)         NOT NULL AUTO_INCREMENT,
            `steamid`                   BIGINT(100)     NOT NULL,
            PRIMARY KEY (`id`)
        ) $this->_charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
    
    function WPB_F_R_UpdatePlayersDataTable()
    {
        if($this->WPB_F_R_tableExists(WPBRIDGE_PLAYER_STATS_TABLE))
        {
            $existing_columns = $this->_wpdb->get_col("DESC `".esc_sql(WPBRIDGE_PLAYER_STATS_TABLE)."`",0);
            foreach (WPBRIDGE_PLAYER_STATS as $stat) {
                if(!in_array($stat,$existing_columns))
                {
                    $this->WPB_F_R_addColumn(WPBRIDGE_PLAYER_STATS_TABLE,$stat);
                }
            }
        }
    }

    function WPB_F_R_UpdateSettingsTable()
    {

    }

    function WPB_F_R_BumpVersion()
    {
        update_option('WPBRIDGE_PLUGIN_VERSION',esc_html(WPBRIDGE_PLUGIN_VERSION));
    }

    function WPB_F_R_addColumn($table,$column)
    {
        
            $this->_wpdb->query("ALTER TABLE `".esc_sql($table)."` ADD `".esc_sql($column)."` INT(11) NOT NULL;");
        
    }

    function WPB_F_R_tableExists($table)
    {
        return $this->_wpdb->get_var("SHOW TABLES LIKE '".esc_sql($table)."'") == $table;
    }

    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_UPDATE();
        }
        return self::$_instance;
    }

}
WPB_F_R_WPBRIDGE_UPDATE::WPB_F_R_instance();