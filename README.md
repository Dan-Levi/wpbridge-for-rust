
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

1. Log in to your WordPress backend and navigate to _Plugins &rarr; Add New.<br>![Illustration showing how to add new plugin](https://i.imgur.com/UoyAhFN.png)
2. Type "`WPBridge`" into the Search and hit Enter.
3. Locate the `WPBridge` plugin in the list of search results and click **Install Now**.<br>![Illustration of how WPBridge looks like in the WordPress plugins directory](https://i.imgur.com/xrf6GoL.png)
4. Once installed, click the **Activate** link.

It can also be installed manually.

1. Download the WPBridge plugin from [WordPress.org](https://wordpress.org/plugins/wpbridge-for-rust/).
2. Unzip the package and move it to your plugins directory.
3. Log into your WordPress backend and navigate to the Plugins screen.
4. Locate WPBridge in the list and click the **Activate** link.

## How to configure

1. Locate and click the `WPBridge for Rust` menu item in the sidebar menu.<br>![Illustration showing where to locate WPBridge for Rust settings page](https://i.imgur.com/QO6VoLk.png)
2. Click `Generate` to generate your unique secret and then click `Save Settings`.<br>![Illustration showing where to generate unique secret](https://i.imgur.com/zV7Nkew.png)


## Coming soon

+ More shortcodes.
+ More WordPress Elementor templates
+ 

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
`Right Click -> Save to download template`
Mystic Gray     | Sunrise Sweets
:---------------|--------------:
[![Mystic Gray](https://i.imgur.com/uWVlyXs.jpg)](https://wpbridge.danlevi.no/ElementorTemplates/WPBridge_Elementor_Template_1.json) | [![Sunrise Sweets](https://i.imgur.com/MNdY2pg.jpg)](https://wpbridge.danlevi.no/ElementorTemplates/WPBridge_Elementor_Template_2.json)

<br />
<br />

## Shortcodes

Read the [shortcode documentation on the official website](https://wpbridge.danlevi.no/#shortcode-documentation).
