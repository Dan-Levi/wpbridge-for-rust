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
        $existingPlayer->joins                  += $player["Joins"];
        $existingPlayer->leaves                 += $player["Leaves"];
        $existingPlayer->deaths                 += $player["Deaths"];
        $existingPlayer->suicides               += $player["Suicides"];
        $existingPlayer->kills                  += $player["Kills"];
        $existingPlayer->headshots              += $player["Headshots"];
        $existingPlayer->wounded                += $player["Wounded"];
        $existingPlayer->recoveries             += $player["Recoveries"];
        $existingPlayer->crafteditems           += $player["CraftedItems"];
        $existingPlayer->repaireditems          += $player["RepairedItems"];
        $existingPlayer->explosivesthrown       += $player["ExplosivesThrown"];
        $existingPlayer->voicebytes             += $player["VoiceBytes"];
        $existingPlayer->hammerhits             += $player["HammerHits"];
        $existingPlayer->reloads                += $player["Reloads"];
        $existingPlayer->shots                  += $player["Shots"];
        $existingPlayer->collectiblespickedup   += $player["CollectiblesPickedUp"];
        $existingPlayer->growablesgathered      += $player["GrowablesGathered"];
        $existingPlayer->chats                  += $player["Chats"];
        $existingPlayer->npckills               += $player["NPCKills"];

        $sql = "
        UPDATE `".WPBRIDGE_PLAYER_STATS_TABLE."` SET 
        `joins` =                       '".$existingPlayer->joins."', 
        `leaves` =                      '".$existingPlayer->leaves."', 
        `deaths` =                      '".$existingPlayer->deaths."', 
        `suicides` =                    '".$existingPlayer->suicides."', 
        `kills` =                       '".$existingPlayer->kills."', 
        `headshots` =                   '".$existingPlayer->headshots."', 
        `wounded` =                     '".$existingPlayer->wounded."', 
        `recoveries` =                  '".$existingPlayer->recoveries."', 
        `crafteditems` =                '".$existingPlayer->crafteditems."', 
        `repaireditems` =               '".$existingPlayer->repaireditems."', 
        `explosivesthrown` =            '".$existingPlayer->explosivesthrown."', 
        `voicebytes` =                  '".$existingPlayer->voicebytes."', 
        `hammerhits` =                  '".$existingPlayer->hammerhits."', 
        `reloads` =                     '".$existingPlayer->reloads."', 
        `shots` =                       '".$existingPlayer->shots."', 
        `collectiblespickedup` =        '".$existingPlayer->collectiblespickedup."', 
        `growablesgathered` =           '".$existingPlayer->growablesgathered."',
        `chats` =                       '".$existingPlayer->chats."',
        `npckills` =                    '".$existingPlayer->npckills."'
        WHERE 
        `steamid` = ".$existingPlayer->steamid.";";

        $wpdb->query($sql);

        
    }

    function InsertPlayer($player)
    {
        global $wpdb;
        $sql = "
        INSERT INTO `".WPBRIDGE_PLAYER_STATS_TABLE."`(
            `steamid`,
            `displayname`,
            `joins`,
            `leaves`,
            `deaths`,
            `suicides`,
            `kills`,
            `headshots`,
            `wounded`,
            `recoveries`,
            `crafteditems`,
            `repaireditems`,
            `explosivesthrown`,
            `voicebytes`,
            `hammerhits`,
            `reloads`,
            `shots`,
            `collectiblespickedup`,
            `growablesgathered`,
            `chats`,
            `npckills`
        ) 
        VALUES 
        (
            '".esc_sql($player["SteamId"])."',
            '".esc_sql($player["DisplayName"])."',
            '".esc_sql($player["Joins"])."',
            '".esc_sql($player["Leaves"])."',
            '".esc_sql($player["Deaths"])."',
            '".esc_sql($player["Suicides"])."',
            '".esc_sql($player["Kills"])."',
            '".esc_sql($player["Headshots"])."',
            '".esc_sql($player["Wounded"])."',
            '".esc_sql($player["Recoveries"])."',
            '".esc_sql($player["CraftedItems"])."',
            '".esc_sql($player["RepairedItems"])."',
            '".esc_sql($player["ExplosivesThrown"])."',
            '".esc_sql($player["VoiceBytes"])."',
            '".esc_sql($player["HammerHits"])."',
            '".esc_sql($player["Reloads"])."',
            '".esc_sql($player["Shots"])."',
            '".esc_sql($player["CollectiblesPickedUp"])."',
            '".esc_sql($player["GrowablesGathered"])."',
            '".esc_sql($player["Chats"])."',
            '".esc_sql($player["NPCKills"])."'
        );";
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