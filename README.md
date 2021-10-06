
# WPBridge for Rust

[Visit WPBridge official website](https://wpbridge.danlevi.no/).

## Synopsis

WPBridge for Rust integrates [WordPress](https://wordpress.org/) sites with [Rust](https://rust.facepunch.com/) servers to show player statistics and server information with [shortcodes](https://codex.wordpress.org/Shortcode).

---

# WordPress [shortcodes]
![example of shortcode usage](https://i.imgur.com/5239skX.png)
# becomes
![example of shortcode usage](https://i.imgur.com/ddABEZf.png)

---

## Current features

+ Communication with a [Rust server](https://wiki.facepunch.com/rust/Creating-a-server) [with uMod/Oxide](https://umod.org/games/rust) that has the [WPBridge for WordPress plugin](https://github.com/Dan-Levi/wpbridge-rust) installed and configured.
+ Communicates via [WordPress REST API](https://developer.wordpress.org/rest-api/) and saves the data to database.
+ Implements several [shortcodes](https://codex.wordpress.org/Shortcode).
+ [rust-servers.info's API](https://api.rust-servers.info/) integration.

## How to install

+ Press the green Code button above and Download ZIP.
+ Log in to your WordPress administrator dashboard.
+ Hover the `Plugins` menu item in the sidebar and click on `Add New`.
+ Click `Upload Plugin` then `Choose file` and choose the ZIP archive that you previously downloaded.
+ Click `Install now` and the `Activate` the plugin once it is installed.

## How to configure

+ Click the `WPBridge for Rust` menu item in the sidebar.
+ Click `Generate` to generate your unique secret and then click `Save Settings`.

## Coming soon

+ More shortcodes.
+ More WordPress Elementor templates

## FAQ
+ **Does this plugin have any plugin dependencies?**
  + No.
+ **Why not just communicate directly with database?**
  
  + Some hosts accepts external scripts to query database directly, and some hosts don't.<br>
  By default, remote access to database server is disabled for security reasons on most hosts.

**The upside about this** is that WPBridge doesn't care about the database technology, and shouldn't either.<br>As long as the REST API Endpoint responds correctly **the data that is sent could basically be stored in any kind of database and format.**<br>

<br />

## Elementor templates
[How to use Elementor Templates](https://elementor.com/help/template-library/)
<br />
<br />
`Right Click -> Save to download`
Mystic Gray     | Sunrise Sweets
:---------------|--------------:
[![Mystic Gray](https://i.imgur.com/uWVlyXs.jpg)](https://wpbridge.danlevi.no/ElementorTemplates/WPBridge_Elementor_Template_1.json) | [![Sunrise Sweets](https://i.imgur.com/MNdY2pg.jpg)](https://wpbridge.danlevi.no/ElementorTemplates/WPBridge_Elementor_Template_2.json)

<br />
<br />

## Shortcodes

[How to use shortcodes](https://codex.wordpress.org/Shortcode)

+ `wpbridge_server_REPLACEWITHSERVERSTAT` -> Server statistics
+ `wpbridge_player_info` -> Player count and listing
+ `wpbridge_server_info` -> Online status and last restart
+ `wpbridge_top_REPLACEWITHSTAT` -> Player stats
+ `wpbridge_steam_connect` -> Returns the steam uri to connect to server
+ `wpbridge_progress_num_players` -> returns a progress bar with the number of active players

<br />
<br />
<br />

## Server statistics
---



    [wpbridge_server_REPLACEWITHSERVERSTAT]

`REPLACEWITHSERVERSTAT` The server stat that you want to show.<br>

Available server stats:
+ ip
+ port
+ level
+ identity
+ seed
+ worldsize
+ maxplayers
+ hostname
+ description
+ updated

The shortcode returns the respective server variable requested.

**Example 1:** `[wpbridge_server_ip]` will return the current external ip for your server.<br>
**Example 2:** `[wpbridge_server_identity]` will return the current identity set on your server.<br>
**Example 2:** `[wpbridge_server_description]` will return the current description set on your server.
---

<br />
<br />
<br />

# Player count and listing

    [wpbridge_player_info id="YOUR_SERVER_ID" all="true"]

`id="YOUR_SERVER_ID"` The server id that is generated after you have added your server to [api.rust-servers.info](api.rust-servers.info).<br>
`all="true"` Pass this argument if you want to show a table containing all the active players and their play time.<br>**NOTE:** If this argument is not passed a single string will be returned formatted like this: `Active Players | X online at the moment` which is usefull for a heading for example.


The shortcode returns either a table with player names and play time, or a string with the number of active players.<br>
If there are no active players it returns a string formatted like this: `No Players online at the moment`.<br>
If the argument `all="true"` is passed and there are no players no output will be generated.

**Example:** `[wpbridge_player_info id="YOUR_SERVER_ID"]` - single string output

![Generated output of [wpbridge_player_info id="YOUR_SERVER_ID"]](https://i.imgur.com/hYXrpOu.png)


**Example:** `[wpbridge_player_info id="YOUR_SERVER_ID" all="true"]` - table output

![Generated output of [wpbridge_player_info id="YOUR_SERVER_ID"]](https://i.imgur.com/CZlqkHk.png)

<br />
<br />
<br />

# Online status and last restart

    [wpbridge_server_info id="YOUR_SERVER_ID"]

`id="YOUR_SERVER_ID"` The server id that is generated after you have added your server to [api.rust-servers.info](api.rust-servers.info).

The shortcode returns a string that is formatted like this:<br>`Status: Online. Last restart: X days, Y hrs ago.`

**Example:** `[wpbridge_server_info id="1"]` will return short status data for Rust Server: [Amsterdam 3](https://api.rust-servers.info/status/1).

![Generated output of [wpbridge_server_info id="YOUR_SERVER_ID"]](https://i.imgur.com/QBaRlvV.png)

<br />
<br />
<br />

# Player stats

    [wpbridge_top_REPLACEWITHSTAT num="NUMBEROFPLAYERS" name="false"]

`REPLACEWITHSTAT` The stat that you want to show.<br>
`num="NUMBEROFPLAYERS"` The number of players returned.<br />
`name="false"` **ONLY USED WHEN `num="1"`**

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


The shortcode returns a table with X number of players that have the highest stat that is requested.<br />
If num is provided like this: `num="1"` will return a single string formatted like this: `PLAYERNAME has NUM STAT`<br />
if num is provided like this: `num="1"` and `name="false"` will return only the stat.

**Example 1:** `[wpbridge_top_kills num="5"]` will return a table with the 5 players that has the highest number of kills.

![Generated output of [wpbridge_top_kills num="5"]](https://i.imgur.com/koy1s6U.png)

**Example 2:** `[wpbridge_top_kills num="1"]` will return a single string.

![Generated output of [wpbridge_top_kills num="1"]](https://i.imgur.com/3bbdqE8.png)

**Example 2:** `[wpbridge_top_kills num="1"]` will return **ONLY** the number.

![Generated output of [wpbridge_top_kills num="1" name="false"]](https://i.imgur.com/QZUm5kU.png)

<br />
<br />
<br />

# Steam uri

    [wpbridge_steam_connect]

The shortcode returns steam protocol uri with your server ip and port for use with hyperlink.

**Example:** `[wpbridge_steam_connect]` outputs `steam://connect/YOUR_SERVER_IP:YOUR_SERVER_PORT`

Usage: `<a href="[wpbridge_steam_connect]">Connect to server now</a>`

<br />
<br />
<br />

# Progress bar with the number of active players

    [wpbridge_progress_num_players show_ip_port="true" show_join="true"]

`show_ip_port="true"` If set to `true` shows ip and port above the progress bar.<br>
`show_join="true"` If set to `true` shows a connect button.

The shortcode returns a progress bar.

**Example 1:** `[wpbridge_progress_num_players]` Shows only progress bar

![](https://i.imgur.com/xAmkXhV.png)

**Example 2:** `[wpbridge_progress_num_players show_join="true"]` Shows only join button

![](https://i.imgur.com/b2FTesR.png)

**Example 3:** `[wpbridge_progress_num_players show_ip_port="true" show_join="true"]` Shows both options

![](https://i.imgur.com/oFlp1LT.png)

