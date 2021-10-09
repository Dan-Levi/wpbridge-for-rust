=== WPBridge for Rust ===
Contributors: danlevi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=T7FNEG2D2ELC8
Tags: rust, gaming, rest, rest-api
Requires at least: 5.8
Tested up to: 5.8.1
Requires PHP: 7.4
Stable tag: 1.0.11
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 
WPBridge for Rust integrates WordPress sites with Rust servers to show player statistics and server information with shortcodes.

== Description ==

WPBridge is a WordPress plugin that enables you to show nearly real time server and player statistics on your site. This is perfect for you who want an overview over player statistics such as; number of kills, headshots, suicides, server ip, server port and much more.

== Features ==

* Communication with a Rust server with uMod/Oxide that has the WPBridge for WordPress plugin installed and configured.
* Communicates via WordPress REST API and saves the data to database.
* Implements several shortcodes.
* rust-servers.info's API integration.

== Installation ==

1. Log in to your WordPress backend and navigate to _Plugins → Add New.
2. Type "WPBridge" into the Search and hit Enter.
3. Locate the WPBridge plugin in the list of search results and click Install Now.
4. Once installed, click the Activate link.

== Configuration ==

1. Locate and click the WPBridge for Rust menu item in the sidebar menu.
2. Click Generate to generate your unique secret and then click Save Settings.

== Elementor templates ==

[How to use Elementor Templates](https://elementor.com/help/template-library/)

`Right Click -> Save to download template`

[Mystic Gray](https://wpbridge.danlevi.no/ElementorTemplates/WPBridge_Elementor_Template_1.json)
[Sunrise Sweets](https://wpbridge.danlevi.no/ElementorTemplates/WPBridge_Elementor_Template_2.json)

== Screenshots ==

1. WPBridge for Rust Settings
2. Shortcode Usage

== Shortcodes ==

## Server Statistics

> ## `[wpbridge_server_REPLACEWITHSERVERSTAT]` 
> #### `REPLACEWITHSERVERSTAT` = The server stat that you want to show.
> 
> ### Server stats
> * ip
> * port
> * level
> * identity
> * seed
> * worldsize
> * maxplayers
> * hostname
> * description
> * Updated
> ---
> ### The shortcode returns the respective server variable requested.
> ---
> **Example 1:** `[wpbridge_server_ip]` Returns the current external ip for your server.
> 
> **Example 2:** `[wpbridge_server_identity]` Returns the current identity set on your server.
> 
> **Example 3:** `[wpbridge_server_description]` Returns the current description set on your server.

## Player Statistics

> ## `[wpbridge_top_REPLACEWITHSTAT num="NUMBEROFPLAYERS" name="false"]` 
> #### `REPLACEWITHSERVERSTAT` = The player stat that you want to show.
> #### `num="NUMBEROFPLAYERS"` = The number of players you want in you table.
> #### `name="false"` = Only used when `num="1"`, returns only the stat.
> 
> ### player stats
> * joins
> * leaves
> * deaths
> * suicides
> * kills
> * headshots
> * wounded
> * recoveries
> * crafteditems
> * repaireditems
> * explosivesthrown
> * voicebytes
> * hammerhits
> * reloads
> * shots
> * collectiblespickedup
> * growablesgathered
> * chats
> * npckills
> * meleeattacks
> * mapmarkers
> * respawns
> * rocketslaunched
> * antihackviolations
> * npcspeaks
> * researcheditems
> ---
> ### The shortcode returns either a table with X number of players that have the highest stat that is requested, or a string with a single players stat and name.
> ---
> **Example 1:** `[wpbridge_top_kills num="5"]` Returns a table with the 5 players, showing both the numbers and names with highest number of kills.
> 
> **Example 2:** `[wpbridge_top_kills num="1"]` Returns a string with the single player with most kills formatted like this: `PLAYER has X kills`. 
> 
> **Example 3:** `[wpbridge_top_kills num="1" name="false"]` Returns only the number of the player with highest number of kills.

## rust-servers API

> ## `[wpbridge_player_info id="YOUR_SERVER_ID" all="true"]` 
> #### `id="YOUR_SERVER_ID"` = The server id that is generated after you have added your server to api.rust-servers.info
> #### `all="true"` = set to true will generate a table with active players and their play time.
> ---
> ### The shortcode returns either a table with player names and play time, or a string with the number of active players. If there are no active players it returns a string formatted like this: No Players online at the moment.
> ---
> **Example 1:** `[wpbridge_player_info id="YOUR_SERVER_ID"]` - single string output
> 
> **Example 2:** `[wpbridge_player_info id="YOUR_SERVER_ID" all="true"]` - table output
---
> ## `[wpbridge_server_info id="YOUR_SERVER_ID"]` 
> #### `id="YOUR_SERVER_ID"` = The server id that is generated after you have added your server to api.rust-servers.info
> ---
> ### The shortcode returns a string with online status and last server restart.
> ---
> **Example:** `[wpbridge_server_info id="1"]` - **Output:** `Status: Online. Last restart: X days, Y hrs ago.`
---

## Steam URI

> ## `[wpbridge_steam_connect]` 
> ---
> ### The shortcode returns steam protocol uri with your server ip and port for use with hyperlink.
> ---
> **Example:** `[wpbridge_steam_connect]` - **Output:** `steam://connect/YOUR_SERVER_IP:YOUR_SERVER_PORT`
> 
> **Usage example:** `<a href="[wpbridge_steam_connect]">Connect to server now</a>`
---

## Other UI Elements

> ## `[wpbridge_progress_num_players show_ip_port="true" show_join="true"]`
> #### `show_ip_port="true"` = If set to true displays ip and port.
> #### `show_join="true"` = If set to true displays connect button.
> ---
> ### Widget with a progress bar that visualizes the number of active players, with optional display of ip, port and connect button.
> ---
> **Example 1:** `[wpbridge_progress_num_players]` - Widget with only progress bar visualizing number of active players.
> **Example 2:** `[wpbridge_progress_num_players show_join="true"]` - Widget with ip, port and progress bar visualizing number of active players.
> **Example 3:** `[wpbridge_progress_num_players show_ip_port="true" show_join="true"]` - Widget with ip, port, join button and progress bar visualizing number of active players.

== Coming soon ==

* More shortcodes
* More WordPress templates

== Security ==

Never post or share your unique secret as this is unique to your server and used by the system to authorize information transactions from your Rust server.

== Feedback ==

* I'm open to your [suggestions and feedback](mailto:danbannan@gmail.com) - Thanks for your interest in WPBridge for Rust!
* Tag me [@DanLeviH](https://twitter.com/DanLeviH) on Twitter #wpbridge
