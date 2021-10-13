<?php

class WPB_F_R_WPBRIDGE_INSTALL
{
    private $_wpdb;
    private $_charset_collate;
    private static $_instance = null;


    public function __construct()
    {
        $this->WPB_F_R_InitDatabase();
        register_activation_hook(WPBRIDGE_PATH . '/wpbridge.php', [$this,'WPB_F_R_CreateSettingsTable']);
        register_activation_hook(WPBRIDGE_PATH . '/wpbridge.php', [$this,'WPB_F_R_PopulateSettingsTable']);
        register_activation_hook(WPBRIDGE_PATH . '/wpbridge.php', [$this,'WPB_F_R_CreatePlayersDataTable']);
    }

    function WPB_F_R_InitDatabase()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        $this->_charset_collate = $wpdb->get_charset_collate();
    }

    function WPB_F_R_CreateSettingsTable()
    {
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $sql = "CREATE TABLE IF NOT EXISTS `".esc_sql(WPBRIDGE_SETTINGS_TABLE)."` (
            id                  INT(11)         NOT NULL AUTO_INCREMENT,
            ip                  VARCHAR(255)    DEFAULT '',
            port                INT(11)         NOT NULL,
            level               VARCHAR(255)    DEFAULT '',
            identity            VARCHAR(255)    DEFAULT '',
            seed                INT(11)         NOT NULL,
            worldsize           INT(11)         NOT NULL,
            maxplayers          INT(11)         NOT NULL,
            hostname            VARCHAR(255)    DEFAULT '',
            description         VARCHAR(255)    DEFAULT '',
            numactiveplayers    INT(11)         NOT NULL,
            updated             datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY (id)
        ) $this->_charset_collate;";
        dbDelta($sql);
    }

    function WPB_F_R_PopulateSettingsTable()
    {
        $this->_wpdb->query("TRUNCATE TABLE `".esc_sql(WPBRIDGE_SETTINGS_TABLE)."`");
        $this->_wpdb->insert( 
            esc_sql(WPBRIDGE_SETTINGS_TABLE), 
            array(
                'numactiveplayers' => 0, 
                'updated' => current_time( 'mysql' ), 
            ) 
        );
    }

    function WPB_F_R_CreatePlayersDataTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `".esc_sql(WPBRIDGE_PLAYER_STATS_TABLE)."` (
            `id`                        INT(11)         NOT NULL AUTO_INCREMENT,
            `steamid`                   BIGINT(100)     NOT NULL,
            `displayname`               VARCHAR(255)    NOT NULL,
            `joins`                     INT(11)         NOT NULL,
            `leaves`                    INT(11)         NOT NULL,
            `deaths`                    INT(11)         NOT NULL,
            `suicides`                  INT(11)         NOT NULL,
            `kills`                     INT(11)         NOT NULL,
            `headshots`                 INT(11)         NOT NULL,
            `wounded`                   INT(11)         NOT NULL,
            `recoveries`                INT(11)         NOT NULL,
            `crafteditems`              INT(11)         NOT NULL,
            `repaireditems`             INT(11)         NOT NULL,
            `explosivesthrown`          INT(11)         NOT NULL,
            `voicebytes`                INT(11)         NOT NULL,
            `hammerhits`                INT(11)         NOT NULL,
            `reloads`                   INT(11)         NOT NULL,
            `shots`                     INT(11)         NOT NULL,
            `collectiblespickedup`      INT(11)         NOT NULL,
            `growablesgathered`         INT(11)         NOT NULL,
            `chats`                     INT(11)         NOT NULL,
            `npckills`                  INT(11)         NOT NULL,
            `meleeattacks`              INT(11)         NOT NULL,
            `mapmarkers`                INT(11)         NOT NULL,
            `respawns`                  INT(11)         NOT NULL,
            `rocketslaunched`           INT(11)         NOT NULL,
            `antihackviolations`        INT(11)         NOT NULL,
            `npcspeaks`                 INT(11)         NOT NULL,
            `researcheditems`           INT(11)         NOT NULL,
            `killedbynpc`               INT(11)         NOT NULL,
            `lootcontainer`             INT(11)         NOT NULL,
            `lootbradheli`              INT(11)         NOT NULL,
            `loothackable`              INT(11)         NOT NULL,
            `lootcontainerunderwater`   INT(11)         NOT NULL,
            PRIMARY KEY (`id`)
        ) $this->_charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_INSTALL();
        }
        return self::$_instance;
    }
}
WPB_F_R_WPBRIDGE_INSTALL::WPB_F_R_instance();