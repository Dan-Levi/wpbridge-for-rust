
# WPBridge for Wordpress

## Synopsis

WPBridge integrates your Wordpress site with a Rust server to show player statistics and server information.

![Wordpress template using Elementor and WPBridge](https://i.imgur.com/026hN54.png)

## Current features

+ Communication with Rust server via WP REST API.
+ shortcodes.
+ api.rust-servers.info integration.

## How to install

+ Press the green Code button above and Download ZIP.
+ Log in to your Wordpress administrator dashboard.
+ Hover the `Plugins` menu item in the sidebar and click on `Add New`.
+ Click `Upload Plugin` then `Choose file` and choose the ZIP archive that you previously downloaded.
+ Click `Install now` and the `Activate` the plugin once it is installed.

## How to configure

+ Click the `WPBridge for Rust` menu item in the sidebar.
+ Click `Generate` to generate your unique secret and then click `Save Settings`.

## Coming soon

+ More shortcodes.

## FAQ
+ **Does this plugin have any plugin dependencies?**
  + No.
+ **Why not just communicate directly with database?**
  
  + Some hosts accepts external scripts to query database directly, and some hosts don't.<br>
  By default, remote access to database server is disabled for security reasons on most hosts.

**The upside about this** is that WPBridge doesn't care about the database technology, and shouldn't either.<br>As long as the REST API Endpoint responds correctly **the data that is sent could basically be stored in any kind of database and format.**<br>

## Available shortcodes

---

    [wpbridge_player_info id="YOUR_SERVER_ID" all="true"]

`id="YOUR_SERVER_ID"` The server id that is generated after you have added your server to [api.rust-servers.info](api.rust-servers.info).<br>
`all="true"` Pass this argument if you want to show a table containing all the active players and their play time.<br>**NOTE:** If this argument is not passed a single string will be returned formatted like this: `Active Players | X online at the moment` which is usefull for a heading for example.


The shortcode returns either a table with player names and play time, or a string with the number of active players.<br>
If there are no active players it returns a string formatted like this: `No Players online at the moment`.

**Example:** `[wpbridge_player_info id="YOUR_SERVER_ID"]`

![Generated output of [wpbridge_player_info id="YOUR_SERVER_ID"]](https://i.imgur.com/hYXrpOu.png)


**Example:** `[wpbridge_player_info id="YOUR_SERVER_ID" all="true"]`

![Generated output of [wpbridge_player_info id="YOUR_SERVER_ID"]](https://i.imgur.com/CZlqkHk.png)

---

    [wpbridge_server_info id="YOUR_SERVER_ID"]

`id="YOUR_SERVER_ID"` The server id that is generated after you have added your server to [api.rust-servers.info](api.rust-servers.info).

The shortcode returns a string that is formatted like this:<br>`Status: Online. Last restart: X days, Y hrs ago.`

**Example:** `[wpbridge_server_info id="1"]` will return short status data for Rust Server: [Amsterdam 3](https://api.rust-servers.info/status/1).

![Generated output of [wpbridge_server_info id="YOUR_SERVER_ID"]](https://i.imgur.com/QBaRlvV.png)


---

    [wpbridge_top_REPLACEWITHSTAT num="NUMBEROFPLAYERS"]

`REPLACEWITHSTAT` The stat that you want to show.<br>
`num="NUMBEROFPLAYERS"` The number of players returned.

Available stats:
+ joins            
+ leaves               
+ deaths               
+ suicides             
+ kills                
+ headshots            
+ wounded              
+ recoveries           
+ crafteditems         
+ repaireditems        
+ explosivesthrown     
+ voicebytes           
+ hammerhits           
+ reloads              
+ shots                
+ collectiblespickedup 
+ growablesgathered    
+ chats                
+ npckills             
+ meleeattacks         
+ mapmarkers           
+ respawns             
+ rocketslaunched      
+ antihackviolations   
+ npcspeaks            
+ researcheditems  


The shortcode returns a table with X number of players that have the highest stat that is requested.

**Example:** `[wpbridge_top_kills num="5"]` will return a table with the 5 players that has the highest number of kills.

![Generated output of [wpbridge_top_kills num="5"]](https://i.imgur.com/koy1s6U.png)