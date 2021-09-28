<?php

class WPBRIDGE_REST_API
{
    private static $_instance = null;

    public function __construct()
    {
        $this->InitRoutes();
    }

    function InitRoutes()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'wpbridge', '/secret', array(
                'methods' => 'POST',
                'callback' => [$this,"Secret_Callback"],
            ));
            register_rest_route( 'wpbridge', '/player-stats', array(
                'methods' => 'POST',
                'callback' => [$this,"Player_Stats_POST_Callback"],
            ));
            
         });
    }

    function UpdateSettings($numActivePlayers)
    {
        global $wpdb;
        $sql = "UPDATE `".WPBRIDGE_SETTINGS_TABLE."` SET `numactiveplayers` = '%d', `updated` = NOW();";
        $wpdb->query(
            $wpdb->prepare(
                $sql,
                $numActivePlayers
            )
        );
    }

    function UpdatePlayer($player)
    {
        global $wpdb;
        $sql = "SELECT * FROM `".WPBRIDGE_PLAYER_STATS_TABLE."` WHERE `steamid` = '%d';";
        $existingPlayer = $wpdb->get_row($wpdb->prepare($sql,$player["SteamId"]));
        $sql = "UPDATE `".WPBRIDGE_PLAYER_STATS_TABLE."` SET ";
        foreach (array_keys($player) as $requestValue) {
            if($requestValue == "SteamId") continue;
            $columnName = strtolower($requestValue);
            if($columnName == "displayname")
            {
                $sql .= "`$columnName` = '" . esc_sql($player[$requestValue]) . "',";
            } else 
            {
                $sql .= "`$columnName` = '" . ((int)$existingPlayer->{$columnName} + (int)($player[$requestValue])) . "',";
            }
        }
        $sql = chop($sql,',') . " WHERE `steamid` = '" . esc_sql($player["SteamId"]) . "';";
        $wpdb->query($sql);
    }

    function InsertPlayer($player)
    {
        global $wpdb;
        $sqlStart = "INSERT INTO `".WPBRIDGE_PLAYER_STATS_TABLE."`(";
        $sqlEnd = "";
        foreach (array_keys($player) as $requestValue) {
            $columnName = strtolower($requestValue);
            $sqlStart .= "`$columnName`,";
            $sqlEnd .= "'" . esc_sql($player[$requestValue]) . "',";
        }
        $sqlStart = chop($sqlStart,',') . ") VALUES (";
        $sqlEnd = chop($sqlEnd,',') . ");";
        $sql = $sqlStart . $sqlEnd;
        $wpdb->query($sql);
    }

    function StorePlayerStats($playersData)
    {
        global $wpdb;
        foreach ($playersData as $player) {
            $sql = "SELECT `steamid` FROM `".WPBRIDGE_PLAYER_STATS_TABLE."` WHERE `steamid` = '%d';";
            if($wpdb->query($wpdb->prepare($sql,$player["SteamId"])))
            {
                $this->UpdatePlayer($player);
            } else {
                $this->InsertPlayer($player);
            }
        }
    }

    function Player_Stats_POST_Callback($req)
    {
        if(!isset($req["Secret"])) return $this->ReturnError(401,"Secret not set");
        if(!isset($req["PlayersData"])) return $this->ReturnError(401,"PlayersData not set");
        if(!isset($req["PlayerCount"])) return $this->ReturnError(401,"PlayerCount not set");
        
        $this->UpdateSettings($req["PlayerCount"]);
        
        if(is_array($req["PlayersData"]) && count($req["PlayersData"]) > 0)
        {
            $this->StorePlayerStats($req["PlayersData"]);
        }

        //TODO: active players and updated needs to be updated
        return $this->ReturnSuccess(200, "Player stats stored.");
    }

    function Secret_Callback($req)
    {
        if(!isset($req["Secret"])) return $this->ReturnError(401,"Secret not set");
        if($req["Secret"] != get_option('wpbridge_secret_field')) return $this->ReturnError(401,"Secret mismatch");
        return $this->ReturnSuccess(200, "Ready");
    }

    














    

    function ReturnSuccess($code,$message)
    {
        return new WP_Rest_Success_Message(
            "success",
            $message,
            array( 'status' => $code)
        );
    }

    function ReturnError($code,$message)
    {
        return new WP_Error(
            'error',
            $message,
            array( 'status' => $code)
        );
    }


    static function instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPBRIDGE_REST_API();
        }
        return self::$_instance;
    }
}
#region Helper Classes
class WP_Rest_Success_Message
{
   public $code,$message,$data;
   public function __construct($_code, $_message,$_data)
   {
      $this->code = $_code;
      $this->message = $_message;
      $this->data = $_data;
   }
}
#endregion
WPBRIDGE_REST_API::instance();