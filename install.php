<?php

class WPBRIDGE_INSTALL
{
    private $_wpdb;
    private $_charset_collate;
    private static $_instance = null;


    public function __construct()
    {
        $this->InitDatabase();
        register_activation_hook(WPBRIDGE_PATH . '/wpbridge.php', [$this,'CreateSettingsTable']);
        register_activation_hook(WPBRIDGE_PATH . '/wpbridge.php', [$this,'PopulateSettingsTable']);
        register_activation_hook(WPBRIDGE_PATH . '/wpbridge.php', [$this,'CreatePlayersDataTable']);
    }

    function InitDatabase()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_charset_collate = $wpdb->get_charset_collate();
    }

    function CreateSettingsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `".WPBRIDGE_SETTINGS_TABLE."` (
            id                  INT(11)     NOT NULL AUTO_INCREMENT,
            numactiveplayers    INT(11)     NOT NULL,
            updated             datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY (id)
        ) $this->_charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    function PopulateSettingsTable()
    {
        $this->_wpdb->query("TRUNCATE TABLE `".WPBRIDGE_SETTINGS_TABLE."`");
        $this->_wpdb->insert( 
            WPBRIDGE_SETTINGS_TABLE, 
            array(
                'numactiveplayers' => 0, 
                'updated' => current_time( 'mysql' ), 
            ) 
        );
    }

    function CreatePlayersDataTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `".WPBRIDGE_PLAYER_STATS_TABLE."` (
            `id`                    INT(11)         NOT NULL AUTO_INCREMENT,
            `steamid`               BIGINT(100)     NOT NULL,
            `displayname`           VARCHAR(255)    NOT NULL,
            `joins`                 INT(11)         NOT NULL,
            `leaves`                INT(11)         NOT NULL,
            `deaths`                INT(11)         NOT NULL,
            `suicides`              INT(11)         NOT NULL,
            `kills`                 INT(11)         NOT NULL,
            `headshots`             INT(11)         NOT NULL,
            `wounded`               INT(11)         NOT NULL,
            `recoveries`            INT(11)         NOT NULL,
            `crafteditems`          INT(11)         NOT NULL,
            `repaireditems`         INT(11)         NOT NULL,
            `explosivesthrown`      INT(11)         NOT NULL,
            `voicebytes`            INT(11)         NOT NULL,
            `hammerhits`            INT(11)         NOT NULL,
            `reloads`               INT(11)         NOT NULL,
            `shots`                 INT(11)         NOT NULL,
            `collectiblespickedup`  INT(11)         NOT NULL,
            `growablesgathered`     INT(11)         NOT NULL,
            `chats`                 INT(11)         NOT NULL,
            `npckills`              INT(11)         NOT NULL,
            `meleeattacks`          INT(11)         NOT NULL,
            `mapmarkers`            INT(11)         NOT NULL,
            `respawns`              INT(11)         NOT NULL,
            `rocketslaunched`       INT(11)         NOT NULL,
            `antihackviolations`    INT(11)         NOT NULL,
            PRIMARY KEY (`id`)
        ) $this->_charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    static function instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPBRIDGE_INSTALL();
        }
        return self::$_instance;
    }
}
WPBRIDGE_INSTALL::instance();