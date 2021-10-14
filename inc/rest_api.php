<?php

class WPB_F_R_WPBRIDGE_REST_API
{
    private static $_instance = null;

    public function __construct()
    {
        $this->WPB_F_R_InitRoutes();
    }

    function WPB_F_R_InitRoutes()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'wpbridge', '/secret', array(
                'methods' => 'POST',
                'callback' => [$this,"WPB_F_R_Secret_Callback"],
                'permission_callback' => '__return_true'
            ));
            register_rest_route( 'wpbridge', '/player-stats', array(
                'methods' => 'POST',
                'callback' => [$this,"WPB_F_R_Player_Stats_POST_Callback"],
                'permission_callback' => '__return_true'
            ));
            
         });
    }

    

    function WPB_F_R_UpdateSettings($serverInfo)
    {
        if(!isset($serverInfo["Ip"])) return $this->WPB_F_R_ReturnError(401,"IP not set");
        if(!isset($serverInfo["Port"])) return $this->WPB_F_R_ReturnError(401,"server.port not set");
        if(!isset($serverInfo["Level"])) return $this->WPB_F_R_ReturnError(401,"server.level not set");
        if(!isset($serverInfo["Identity"])) return $this->WPB_F_R_ReturnError(401,"server.identity not set");
        if(!isset($serverInfo["Seed"])) return $this->WPB_F_R_ReturnError(401,"server.seed not set");
        if(!isset($serverInfo["WorldSize"])) return $this->WPB_F_R_ReturnError(401,"server.worldsize not set");
        if(!isset($serverInfo["MaxPlayers"])) return $this->WPB_F_R_ReturnError(401,"server.maxplayers not set");
        if(!isset($serverInfo["HostName"])) return $this->WPB_F_R_ReturnError(401,"server.hostname not set");
        if(!isset($serverInfo["Description"])) return $this->WPB_F_R_ReturnError(401,"server.description not set");
        if(!isset($serverInfo["PlayerCount"])) return $this->WPB_F_R_ReturnError(401,"PlayerCount not set");
        $serverIp = str_replace("\n", "", $serverInfo["Ip"]);
        $description = str_replace("\\n","<br>",$serverInfo["Description"]);

        global $wpdb;
        $sql = "UPDATE `".WPBRIDGE_SETTINGS_TABLE."` SET 
                `ip`                = '%s',
                `port`              = '%d',
                `level`             = '%s',
                `identity`          = '%s',
                `seed`              = '%d',
                `worldsize`         = '%d',
                `maxplayers`        = '%d',
                `hostname`          = '%s',
                `description`       = '%s',
                `numactiveplayers`  = '%d',
                `updated` = NOW();";
        $wpdb->query(
            $wpdb->prepare(
                $sql,
                esc_sql($serverIp),
                esc_sql($serverInfo["Port"]),
                esc_sql($serverInfo["Level"]),
                esc_sql($serverInfo["Identity"]),
                esc_sql($serverInfo["Seed"]),
                esc_sql($serverInfo["WorldSize"]),
                esc_sql($serverInfo["MaxPlayers"]),
                esc_sql($serverInfo["HostName"]),
                esc_sql($description),
                esc_sql($serverInfo["PlayerCount"]),
            )
        );
    }

    function WPB_F_R_UpdatePlayer($player)
    {
        global $wpdb;
        $sql = "SELECT * FROM `". esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) ."` WHERE `steamid` = '%d';";
        $existingPlayer = $wpdb->get_row($wpdb->prepare($sql, $player["SteamId"] ));
        $sql = "UPDATE `". esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) ."` SET ";
        foreach (array_keys($player) as $requestValue) {
            if($requestValue == "LootedItems")
            {

                $lootStats = $player[$requestValue];
                if(count($lootStats) > 0) $this->WPB_F_R_StorePlayerLoot($player["SteamId"],$lootStats);
                continue;
            }
            if($requestValue == "SteamId")
            {
                continue;
            }
         
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
        try {
            $wpdb->query($sql);
        } catch (Exception $err) {
            return new WP_Error(
                'error',
                "Failed to update player statistics. Is Wordpress plugin outdated?",
                array( 'status' => '401')
            );
            die();
        }
    }

    function WPB_F_R_StorePlayerLoot($steamId, $items)
    {
        // die(print_r($items));
        global $wpdb;
        if($wpdb->get_var("SHOW TABLES LIKE '".esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE)."'") == WPBRIDGE_PLAYER_LOOT_TABLE);
        {
            $existing_columns = $wpdb->get_col("DESC `".esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE)."`",0);
            foreach ($items as $item) {
                if(!in_array($item["Name"],$existing_columns))
                {
                    $wpdb->query("ALTER TABLE `".esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE)."` ADD `".esc_sql(strtolower(trim($item["Name"])))."` INT(11) NOT NULL;");
                }
            }
            
            $sql = "SELECT `steamid` FROM `". esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) ."` WHERE `steamid` = '%d';";
            $queryResultPlayerExistsInStatsTable = $wpdb->query($wpdb->prepare($sql,$steamId));
            if($queryResultPlayerExistsInStatsTable)
            {
                $sql = "SELECT * FROM `". esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE) ."` WHERE `steamid` = '%d';";
                $queryResultPlayerExistsInLootTable = $wpdb->get_row($wpdb->prepare($sql,$steamId));
                
                if($queryResultPlayerExistsInLootTable)
                {
                    $sql = "UPDATE `". esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE) ."` SET ";
                    foreach ($items as $item) {
                        $sql .= "`" . esc_sql($item["Name"]) . "` = '" . ((int)$queryResultPlayerExistsInLootTable->{$item["Name"]} + (int)($item["Amount"])) . "',";
                    }
                    $sql = chop($sql,',') . " WHERE `steamid` = '" . esc_sql($steamId) . "';";
                    $wpdb->query($sql);
                } else {
                    $sqlStart = "INSERT INTO `".WPBRIDGE_PLAYER_LOOT_TABLE."`(";
                    $sqlStart .= "`steamid`,";
                    $sqlEnd = "'" . esc_sql($steamId) . "',";
                    foreach ($items as $item) {
                        $columnName = strtolower(trim($item["Name"]));
                        $sqlStart .= "`" . esc_sql($columnName) . "`,";
                        $sqlEnd .= "'" . esc_sql($item["Amount"]) . "',";
                    }
                    $sqlStart = chop($sqlStart,',') . ") VALUES (";
                    $sqlEnd = chop($sqlEnd,',') . ");";
                    $sql = $sqlStart . $sqlEnd;
                    $wpdb->query($sql);
                }
            }
        }
    }

    function WPB_F_R_InsertPlayer($player)
    {
        global $wpdb;
        $sqlStart = "INSERT INTO `". esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) ."`(";
        $sqlEnd = "";
        foreach (array_keys($player) as $requestValue) {


            if($requestValue == "LootedItems")
            {

                $lootStats = $player[$requestValue];
                if(count($lootStats) > 0) $this->WPB_F_R_StorePlayerLoot($player["SteamId"],$lootStats);
                continue;
            }


            $columnName = strtolower($requestValue);
            $sqlStart .= "`" . esc_sql($columnName) . "`,";
            $sqlEnd .= "'" . esc_sql($player[$requestValue]) . "',";
        }
        $sqlStart = chop($sqlStart,',') . ") VALUES (";
        $sqlEnd = chop($sqlEnd,',') . ");";
        $sql = $sqlStart . $sqlEnd;
        $wpdb->query($sql);
    }

    function WPB_F_R_StorePlayerStats($playersData)
    {
        global $wpdb;
        foreach ($playersData as $player) {
            $sql = "SELECT `steamid` FROM `". esc_sql(WPBRIDGE_PLAYER_STATS_TABLE) ."` WHERE `steamid` = '%d';";
            if($wpdb->query($wpdb->prepare($sql,$player["SteamId"])))
            {
                $this->WPB_F_R_UpdatePlayer($player);
            } else {
                $this->WPB_F_R_InsertPlayer($player);
            }
        }
    }

    function WPB_F_R_Player_Stats_POST_Callback($req)
    {
        if(!isset($req["Secret"])) return $this->WPB_F_R_ReturnError(401,"Secret not set");
        if($req["Secret"] != get_option('wpbridge_secret_field')) return $this->WPB_F_R_ReturnError(401,"Secret mismatch");
        if(!isset($req["PlayersData"])) return $this->WPB_F_R_ReturnError(401,"PlayersData not set");
        if(!isset($req["ServerInfo"]) || !is_array($req["ServerInfo"])) return $this->WPB_F_R_ReturnError(401,"ServerInfo not set");
        
        $this->WPB_F_R_UpdateSettings($req["ServerInfo"]);
        
        if(is_array($req["PlayersData"]) && count($req["PlayersData"]) > 0)
        {
            $this->WPB_F_R_StorePlayerStats($req["PlayersData"]);
        }

        //TODO: active players and updated needs to be updated
        return $this->WPB_F_R_ReturnSuccess(200, "Player and server stats stored.");
    }

    function WPB_F_R_Secret_Callback($req)
    {
        if(!isset($req["Secret"])) return $this->WPB_F_R_ReturnError(401,"Secret not set");
        if($req["Secret"] != get_option('wpbridge_secret_field')) return $this->WPB_F_R_ReturnError(401,"Secret mismatch");
        if(!isset($req["ServerInfo"]) || !is_array($req["ServerInfo"])) return $this->WPB_F_R_ReturnError(401,"ServerInfo not set");
        $this->WPB_F_R_UpdateSettings($req["ServerInfo"]);
        return $this->WPB_F_R_ReturnSuccess(200, "Ready");
    }

    function WPB_F_R_ReturnSuccess($code,$message)
    {
        return new WPB_F_R_WP_Rest_Success_Message(
            "success",
            $message,
            array( 'status' => $code)
        );
    }

    function WPB_F_R_ReturnError($code,$message)
    {
        return new WP_Error(
            'error',
            $message,
            array( 'status' => $code)
        );
    }


    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_REST_API();
        }
        return self::$_instance;
    }
}
#region Helper Classes
class WPB_F_R_WP_Rest_Success_Message
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
WPB_F_R_WPBRIDGE_REST_API::WPB_F_R_instance();