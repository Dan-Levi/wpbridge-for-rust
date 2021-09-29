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
            add_shortcode("wpbridge_server_info", [$this,"ServerInfoShortCodeFunc"]);
            add_shortcode("wpbridge_top_$playerStat", [$this,"TopPlayerShortCodeFunc"]);
        }
    }

    function ServerInfoShortCodeFunc($atts, $content = null, $tag = '')
    {
        if(!isset($atts['id']) || $atts['id'] == "") return '<strong>You have to provide server id:</strong><br> [wpbridge_server_info id="ID_GOES_HERE"]';
        $id = $atts['id'];
        return '<div id="header-server-status" data-id="'.$id.'">Status: # Last restart: # days, # hrs ago.</div>';
    }
    
    function TopPlayerShortCodeFunc($atts, $content = null, $tag = '')
    {
        $stat = str_replace("wpbridge_top_","",$tag);
        $num = 1;
        if(isset($atts['num']) && (int)$atts['num'] > 0) $num = $atts['num'];
        
        $topPlayers = $this->_wpdb->get_results("SELECT `displayname`,`" . esc_sql($stat) . "` FROM `" . WPBRIDGE_PLAYER_STATS_TABLE . "` ORDER BY '" . esc_sql($stat) . "' DESC LIMIT " . esc_sql($num) . ";");
        if(!$topPlayers || count($topPlayers) == 0) return "No data";
        $markup = '
        <div class="wpbridge_top_widget">
            <h2>Top ' . $num . ' ' . ucfirst($stat) . '</h2>
            <table class="aligncenter">
                <tr>
                    <th>DisplayName</th>
                    <th>Count</th>
                </tr>
                <tr>
        ';
        foreach ($topPlayers as $player) {
            $markup .= "<td>$player->displayname</td>";
            $markup .= "<td>".$player->$stat."</td>";
        }
        return $markup .= '</tr></table></div>';
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
