<?php


class WPB_F_R_WPBRIDGE_SETTINGS
{
    private static $_instance = null;

    public function __construct()
    {
        $this->WPB_F_R_Add_Actions();
        $this->WPB_F_R_Add_Filters();
    }
    
    function WPB_F_R_Add_Actions()
    {
        add_action('admin_menu', [$this,"WPB_F_R_SetupSettingsMenu"]);
        add_action('admin_menu', [$this,'WPB_F_R_Add_Player_Statistics_SubMenu']);
        add_action('admin_enqueue_scripts', [$this,"WPB_F_R_InitAdminJavaScript"]);
        add_action('admin_init', [$this,"WPB_F_R_SetupSecretSection"]);
    }

    function WPB_F_R_Add_Filters()
    {
        add_filter( 'kses_allowed_protocols' , [$this,'WPB_F_R_AllowSteamProtocol'] );
    }

    function WPB_F_R_AllowSteamProtocol($protocols){
        $protocols[] = 'steam';
        return $protocols;
    }


    function WPB_F_R_InitAdminJavaScript()
    {
        wp_enqueue_script(
            'wpbridge-admin-script',
            WPBRIDGE_URL . 'admin/js/settings.js',
            array('jquery'),
            rand(),
            true
        );
    }

    

    function WPB_F_R_SetupSecretSection()
    {
        add_settings_section(
            'wpbridge_settings_secret_section',
            '',
            '',
            'wpbridge-settings-page'
        );
        register_setting(
            'wpbridge-settings-page',
            'wpbridge_secret_field',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );
        add_settings_field(
            'wpbridge_secret_field',
            __('Your Secret', 'wpbridge-for-rust'),
            [$this,'WPB_F_R_SecretSettingsFieldCallback'],
            'wpbridge-settings-page',
            'wpbridge_settings_secret_section'
        );
    }

    function WPB_F_R_SecretSettingsFieldCallback()
    {
        $secret = get_option('wpbridge_secret_field','');
    ?>
        <input type="text" id="wpbridge_secret_field" class="regular-text" name="wpbridge_secret_field" value="<?php echo esc_html($secret); ?>" placeholder="<?php echo __('Please type or generate your unique secret', 'wpbridge-for-rust'); ?>" />
        <button id="wpbridge_secret_generate_button" class="button button-primary"><?php echo __('Generate', 'wpbridge-for-rust'); ?></button>
        <br>
        <label for="wpbridgerust_settings_input_secret_field"><?php echo __('Paste this unique secret into WPBridge config file <code>[your_rust_server]/oxide/config/WPBridge.json</code>', 'wpbridge-for-rust'); ?></label>
    <?php
    }

    

    function WPB_F_R_SetupSettingsMenu()
    {
        add_menu_page(
            __('WPBridge for Rust', 'wpbridge-for-rust'),
            __('WPBridge for Rust', 'wpbridge-for-rust'),
            'manage_options',
            'wpbridge-settings-page',
            [$this,'WPB_F_R_wpbridge_settings_template_callback'],
            'dashicons-admin-links',
            null
        );
    }

    function WPB_F_R_Add_Player_Statistics_SubMenu()
    {
        add_submenu_page(
            'wpbridge-settings-page',
            '',
            '',
            'manage_options',
            'wpbridge-purge-statistics-database',
            [$this,'WPB_F_R_Player_Statistics_SubMenu_Template_Callback']
        );
    }
    // http://192.168.0.57/wp-admin/admin.php?page=wpbridge-purge-statistics-database
    function WPB_F_R_Player_Statistics_SubMenu_Template_Callback()
    {
        if (!current_user_can('manage_options')) wp_die(__('Your user don\'t have permissions to do that action.','wpbridge-for-rust'));
        global $wpdb;
        $wpdb->query("TRUNCATE TABLE `".esc_sql(WPBRIDGE_PLAYER_STATS_TABLE)."`;");
        $wpdb->query("TRUNCATE TABLE `".esc_sql(WPBRIDGE_PLAYER_LOOT_TABLE)."`;");
        ?>
        <div class="wrap">
            <h3><?php echo __('WPBridge for Rust - Settings', 'wpbridge-for-rust'); ?></h3>
            <hr />
            <h1><?php echo __('Players and loot successfully cleared.','wpbridge-for-rust'); ?></h1>
            <br>
            <a href="?page=wpbridge-settings-page" class="button button-primary button-warning"><?php echo __('Go back', 'wpbridge-for-rust'); ?></a>
        </div>
        <?php
    }

    function WPB_F_R_wpbridge_settings_template_callback()
    {
        ?>
        <form action="options.php" method="post">
            <div class="wrap">
                <h3><?php echo esc_html( get_admin_page_title() ) . ' ' . __(' - Settings', 'wpbridge-for-rust'); ?></h3>
                <hr />
                <!-- <h1><?php echo __('Secret','wpbridge-for-rust'); ?></h1> -->
                <?php settings_fields('wpbridge-settings-page'); ?>
                <?php do_settings_sections('wpbridge-settings-page');?>

                <!-- <h1><?php echo __('Data','wpbridge-for-rust'); ?></h1> -->
                <br>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><?php echo __('Player Statistics','wpbridge-for-rust'); ?></th>
                            <td>
                                <a href="?page=wpbridge-purge-statistics-database" id="wpbridge_database_purge_players_button" class="button button-primary button-warning">
                                    <?php echo __('Clear', 'wpbridge-for-rust'); ?>
                                </a>
                            </td>
                        </tr>      
                    </tbody>
                </table>
                <hr />
                <?php submit_button( __('Save Settings', 'wpbridge-for-rust') ); ?>
            </div>
        </form>
        <?php
    }


    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_SETTINGS();
        }
        return self::$_instance;
    }
}

WPB_F_R_WPBRIDGE_SETTINGS::WPB_F_R_instance();