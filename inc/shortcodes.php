<?php

class WPB_F_R_WPBRIDGE_SHORTCODES
{
    private static $_instance = null;
    private $_wpdb = null;

    public function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        add_action( 'init', [$this,"WPB_F_R_InitShortCodes"] );
    }

    function WPB_F_R_InitShortCodes()
    {
        add_shortcode("wpbridge_player_info", [$this,"WPB_F_R_RustServerAPIPlayerInfoShortCodeFunc"]);
        add_shortcode("wpbridge_server_info", [$this,"WPB_F_R_RustServerAPIServerInfoShortCodeFunc"]);
        
        add_shortcode("wpbridge_steam_connect", [$this,"WPB_F_R_SteamConnectShortCodeFunc"]);

        //Progress_lines
        add_shortcode("wpbridge_progress_num_players", [$this, "WPB_F_R_ProgressLineNumPlayers"]);

        
        foreach (WPBRIDGE_PLAYER_STATS as $playerStat)
        {
            add_shortcode(esc_html("wpbridge_total_$playerStat"), [$this,"WPB_F_R_TotalStatShortCodeFunc"]);
            add_shortcode(esc_html("wpbridge_top_$playerStat"), [$this,"WPB_F_R_TopPlayerShortCodeFunc"]);
        }
        foreach (WPBRIDGE_SERVER_STATS as $serverStat)
        {
            add_shortcode(esc_html("wpbridge_server_$serverStat"), [$this,"WPB_F_R_ServerStatShortCodeFunc"]);
        }
    }

    function WPB_F_R_ProgressLineNumPlayers($atts, $content = null, $tag = '')
    {
        $markup = "";
        $queryResult = $this->_wpdb->get_results("SELECT `ip`,`port`,`hostname`,`maxplayers`,`numactiveplayers` FROM `" . esc_sql(WPBRIDGE_SETTINGS_TABLE) . "` WHERE id = 1;");
        if(is_array($queryResult) && count($queryResult) == 1)
        {
            $data = $queryResult[0];
            $percentage = (int)($data->numactiveplayers * 100 / $data->maxplayers);
            $markup = '
            <div class="wpbridge-widget-container wpbridge-progressbar">';

            if(isset($atts["show_ip_port"]) && $atts["show_ip_port"] == "true")
            {
                $markup .= '<span class="elementor-title">' . esc_html($data->ip) . ':' . $data->port . '</span>';
            }

            $markup .='
                
                <div class="elementor-progress-wrapper" role="progressbar" aria-valuemin="0" aria-valuemax="' . esc_html($data->maxplayers) . '" aria-valuenow="' . esc_html($data->numactiveplayers) . '" aria-valuetext="' . __("Active players", "wpbridge-for-rust") . '">
                    <div class="elementor-progress-bar" data-max="' . esc_html($data->maxplayers) . '" style="width: ' . esc_html($percentage) . '%;">
                        <span class="elementor-progress-text">' . __("Active players", "wpbridge-for-rust") . ' (' . $data->numactiveplayers . '/' . $data->maxplayers . ')' . '</span>
                    </div>
                </div>';

                
            if(isset($atts["show_join"]) && $atts["show_join"] == "true")
            {
            $markup .= '
                <div class="wpbridge-button-wrapper">
                    <a href="http://steam://connect/' . $data->ip . ':' . $data->port . '" class="elementor-button-link elementor-button elementor-size-sm" role="button">
                        <span class="elementor-button-content-wrapper">
                            <span class="elementor-button-text">Join now</span>
                        </span>
                    </a>
                </div>
            ';
            }


            $markup .= '
            </div>
            ';


        }
        return $markup;
    }

    function WPB_F_R_RustServerAPIPlayerInfoShortCodeFunc($atts, $content = null, $tag = '')
    {
        if(!isset($atts['id'])) return '<strong>You have to provide server id:</strong><br> [wpbridge_player_info id="ID_GOES_HERE"]';
        if(!isset($atts['all'])) return '<span class="wpbridge-shortcode rust-server-api-player-count" data-id="'.esc_html($atts['id']).'"></span>';
        return '<span class="wpbridge-shortcode rust-server-api-player-list" data-id="'.esc_html($atts['id']).'"></span>';
    }

    function WPB_F_R_RustServerAPIServerInfoShortCodeFunc($atts, $content = null, $tag = '')
    {
        if(!isset($atts['id']) || $atts['id'] == "") return '<strong>You have to provide server id:</strong><br> [wpbridge_server_info id="ID_GOES_HERE"]';
        $id = $atts['id'];
        return '<strong><div id="header-rust-server-api-server-status" data-id="'.esc_html($id).'">Status: # Last restart: # days, # hrs ago.</div></strong>';
    }

    function WPB_F_R_SteamConnectShortCodeFunc()
    {
        $result = $this->_wpdb->get_results("SELECT `ip`,`port` FROM `" . esc_sql(WPBRIDGE_SETTINGS_TABLE) . "` WHERE id = 1;");
        if(!is_array($result)) return "[SteamConnectShortCodeFunc] -> The shortcode produced an error NOT_ARRAY_EXCEPTION";
        if(!isset($result[0]->ip) || !isset($result[0]->port)) "[SteamConnectShortCodeFunc] -> The shortcode produced an error WPBRIDGE_DATABASE_COLUMN_MISSING_EXCEPTION";
        return "steam://connect/".esc_html($result[0]->ip).":".esc_html($result[0]->port);
    }

    function WPB_F_R_ServerStatShortCodeFunc($atts, $content = null, $tag = '')
    {
        $stat = str_replace("wpbridge_server_","",$tag);
        if(!in_array($stat,WPBRIDGE_SERVER_STATS)) return "[ServerStatShortCodeFunc] -> The shortcode produced an error WPBRIDGE_SERVER_STAT_MISSING_EXCEPTION";

        $result = $this->_wpdb->get_results("SELECT `" . esc_sql($stat) . "` FROM `" . esc_sql(WPBRIDGE_SETTINGS_TABLE) . "` WHERE id = 1;");
        if(!is_array($result)) return "[ServerStatShortCodeFunc] -> The shortcode produced an error NOT_ARRAY_EXCEPTION";
        if(!isset($result[0]->$stat)) "[ServerStatShortCodeFunc] -> The shortcode produced an error WPBRIDGE_DATABASE_COLUMN_MISSING_EXCEPTION";
        return $result[0]->$stat;
    }

    function WPB_F_R_TotalStatShortCodeFunc($atts, $content = null, $tag = '')
    {
        $stat = str_replace("wpbridge_total_","",$tag);
        $queryResult = $this->_wpdb->get_results(" SELECT SUM(`" . esc_sql($stat) . "`) AS `" . esc_sql($stat) . "` FROM `" . esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) . "` ;");
        if(is_array($queryResult) && count($queryResult) === 1)
        {
            return $queryResult[0]->$stat;
        }
        
        return "#";
    }
    
    function WPB_F_R_TopPlayerShortCodeFunc($atts, $content = null, $tag = '')
    {
        $stat = str_replace("wpbridge_top_","",$tag);
        $num = 1;
        if(isset($atts['num']) && (int)$atts['num'] > 0) $num = $atts['num'];
        
        $topPlayers = $this->_wpdb->get_results("SELECT `displayname`,`" . esc_sql($stat) . "` FROM `" . WPBRIDGE_PLAYER_STATS_TABLE . "` ORDER BY " . esc_sql($stat) . " DESC LIMIT " . esc_sql($num) . ";");
        if(!$topPlayers || count($topPlayers) == 0) return "No data";

        if($num == 1)
        {
            $player = $topPlayers[0];
            if(isset($atts["name"]) && $atts["name"] == "false")
            {
                return $player->$stat;
            }
            return $player->displayname . " has " . $player->$stat . " " . $stat;
        }
        
        $markup = "
        <span class='wpbridge-shortcode'>
            <table>
                <tbody>";
                
        foreach ($topPlayers as $player) {
            $markup .= "
                    <tr>
                        <td>". esc_html($player->displayname) ."</td>
                        <td>". esc_html($player->$stat) ."</td>
                    </tr>";
        }

            $markup .= "
                </tbody>
            </table>
        </span>
        ";

        return $markup;
    }
    
    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_SHORTCODES();
        }
        return self::$_instance;
    }
}
WPB_F_R_WPBRIDGE_SHORTCODES::WPB_F_R_instance();
