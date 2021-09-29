
# WPBridge for Wordpress

## Synopsis

WPBridge integrates your Wordpress site with a Rust server to show player statistics and server information.

## Current features

+ Communication with Rust server via WP REST API
+ shortcodes
+ api.rust-servers.info integration

## Coming soon

+ More shortcodes

## FAQ
+ **Does this plugin have any plugin dependencies?**
  + No
+ **Why not just communicate directly with database?**
  
  + Some hosts accepts external scripts to query database directly, and some hosts don't.<br>
  By default, remote access to database server is disabled for security reasons on most hosts.

**The upside about this** is that WPBridge doesn't care about the database technology, and shouldn't either.<br>As long as the REST API Endpoint responds correctly **the data that is sent could basically be stored in any kind of database and format.**<br>

## Available shortcodes

---

`[wpbridge_server_info id="YOUR_SERVER_ID"]`<br>
`YOUR_SERVER_ID` The server id that is generated after you have added your server to api.rust-servers.info

The shortcode returns a string that is formatted like this: `Status: Online. Last restart: X days, Y hrs ago.`

**Example:** `[wpbridge_server_info id="1"]` will return short status data for [Amsterdam 3](https://api.rust-servers.info/status/1)

---

`[wpbridge_top_REPLACEWITHSTAT num="NUMBEROFPLAYERS"]`<br>
`REPLACEWITHSTAT` The stat that you want to show<br>
`NUMBEROFPLAYERS` The number of players returned

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


The shortcode returns a table with X number of players that have the highest stat that is requested

**Example:** `[wpbridge_top_kills num="5"]` will return a table with the 5 players that has the highest number of kills

---