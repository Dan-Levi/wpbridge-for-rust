<?php

class WPBRIDGE_SHORTCODES
{
    private static $_instance = null;
    private $_wpdb = null;

    public function __construct()
    {
        global $wpdb;
        $this->_wpdb = $wpdb;
        add_action( 'init', [$this,"InitShortCodes"] );
    }

    function InitShortCodes()
    {
        foreach (WPBRIDGE_PLAYER_STATS as $playerStat) {
            add_shortcode("wpbridge_player_info", [$this,"RustServerAPIPlayerInfoShortCodeFunc"]);
            add_shortcode("wpbridge_server_info", [$this,"RustServerAPIServerInfoShortCodeFunc"]);
            add_shortcode("wpbridge_top_$playerStat", [$this,"TopPlayerShortCodeFunc"]);
        }
    }

    function RustServerAPIPlayerInfoShortCodeFunc($atts, $content = null, $tag = '')
    {
        if(!isset($atts['id'])) return '<strong>You have to provide server id:</strong><br> [wpbridge_player_info id="ID_GOES_HERE"]';
        if(!isset($atts['all'])) return '<span class="wpbridge-shortcode rust-server-api-player-count" data-id="'.$atts['id'].'"></span>';
        return '<span class="wpbridge-shortcode rust-server-api-player-list" data-id="'.$atts['id'].'"></span>';
    }

    function RustServerAPIServerInfoShortCodeFunc($atts, $content = null, $tag = '')
    {
        if(!isset($atts['id']) || $atts['id'] == "") return '<strong>You have to provide server id:</strong><br> [wpbridge_server_info id="ID_GOES_HERE"]';
        $id = $atts['id'];
        return '<strong><div id="header-rust-server-api-server-status" data-id="'.$id.'">Status: # Last restart: # days, # hrs ago.</div></strong>';
    }
    
    function TopPlayerShortCodeFunc($atts, $content = null, $tag = '')
    {
        $stat = str_replace("wpbridge_top_","",$tag);
        $num = 1;
        if(isset($atts['num']) && (int)$atts['num'] > 0) $num = $atts['num'];
        
        $topPlayers = $this->_wpdb->get_results("SELECT `displayname`,`" . esc_sql($stat) . "` FROM `" . WPBRIDGE_PLAYER_STATS_TABLE . "` ORDER BY " . esc_sql($stat) . " DESC LIMIT " . esc_sql($num) . ";");
        if(!$topPlayers || count($topPlayers) == 0) return "No data";
        
        $markup = "
        <span class='wpbridge-shortcode'>
            <table>
                <tbody>";
                
        foreach ($topPlayers as $player) {
            $markup .= "
                    <tr>
                        <td>". $player->displayname ."</td>
                        <td>". $player->$stat ."</td>
                    </tr>";
        }

            $markup .= "
                </tbody>
            </table>
        </span>
        ";

        return $markup;
    }
    
    static function instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPBRIDGE_SHORTCODES();
        }
        return self::$_instance;
    }
}
WPBRIDGE_SHORTCODES::instance();
