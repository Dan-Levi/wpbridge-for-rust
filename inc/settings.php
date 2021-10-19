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
        add_action('admin_menu', [$this,'WPB_F_R_RustMapAPIGen_SubMenu']);
        add_action('admin_enqueue_scripts', [$this,"WPB_F_R_InitAdminJavaScript"]);
        add_action('admin_init', [$this,"WPB_F_R_SetupSecretSection"]);
        add_action('admin_init', [$this,"WPB_F_R_ServerDetailsSection"]);
        add_action('admin_init', [$this,"WPB_F_R_SetupRustMapAPISection"]);
        
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
        wp_enqueue_style('jquery-ui', WPBRIDGE_URL . 'admin/css/jquery-ui.min.css');
        wp_enqueue_script(
            'wpbridge-admin-script',
            WPBRIDGE_URL . 'admin/js/settings.js',
            array('jquery', 'jquery-ui-core','jquery-ui-tabs'),
            rand(),
            true
        );
    }

    function WPB_F_R_ServerDetailsSection()
    {
        add_settings_section(
            'wpbridge_settings_server_details_section',
            '',
            [$this,'WPB_F_R_ServerDetailsSectionCallback'],
            'wpbridge-server-details-page'
        );
    }

    function WPB_F_R_ServerDetailsSectionCallback()
    {
        global $wpdb;
        $settings = $wpdb->get_row("SELECT * FROM `" . esc_sql(WPBRIDGE_SETTINGS_TABLE) . "` WHERE id = 1");
        ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><?php echo __('Last updated','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->updated); ?>
                    </td>
                </tr>  
                <tr>
                    <th scope="row"><?php echo __('IP','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->ip); ?>
                    </td>
                </tr>     
                <tr>
                    <th scope="row"><?php echo __('Port','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->port); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo __('Level','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->level); ?>
                    </td>
                </tr>      
                <tr>
                    <th scope="row"><?php echo __('Identity','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->identity); ?>
                    </td>
                </tr>     
                <tr>
                    <th scope="row"><?php echo __('Seed','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->seed); ?>
                    </td>
                </tr>     
                <tr>
                    <th scope="row"><?php echo __('World Size','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->worldsize); ?>
                    </td>
                </tr>     
                <tr>
                    <th scope="row"><?php echo __('Max Players','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->maxplayers); ?>
                    </td>
                </tr>     
                <tr>
                    <th scope="row"><?php echo __('Hostname','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->hostname); ?>
                    </td>
                </tr>     
                <tr>
                    <th scope="row"><?php echo __('Description','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->description); ?>
                    </td>
                </tr>    
                <tr>
                    <th scope="row"><?php echo __('Number Of Active Players','wpbridge-for-rust'); ?></th>
                    <td>
                        <?php echo esc_html($settings->numactiveplayers); ?>
                    </td>
                </tr>      
                   
            </tbody>
        </table>
        <?php

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

    function WPB_F_R_SetupRustMapAPISection()
    {
        add_settings_section(
            'wpbridge_settings_rustmapapi_section',
            '',
            '',
            'wpbridge-settings-page-rustmap'
        );
        register_setting(
            'wpbridge-settings-page',
            'wpbridge_rustmapapi_field',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );
        add_settings_field(
            'wpbridge_rustmapapi_field',
            __('Your RustMap API Key', 'wpbridge-for-rust'),
            [$this,'WPB_F_R_RustMapAPISettingsFieldCallback'],
            'wpbridge-settings-page-rustmap',
            'wpbridge_settings_rustmapapi_section'
        );
    }

    function WPB_F_R_SecretSettingsFieldCallback()
    {
        $secret = get_option('wpbridge_secret_field','');
    ?>
        <input type="text" id="wpbridge_secret_field" class="regular-text" name="wpbridge_secret_field" value="<?php echo esc_html($secret); ?>" placeholder="<?php echo __('Please type or generate your unique secret', 'wpbridge-for-rust'); ?>" />
        <button id="wpbridge_secret_generate_button" class="button button-primary"><?php echo __('Generate', 'wpbridge-for-rust'); ?></button>
        <br>
        <label for="wpbridge_secret_field"><?php echo __('Paste this unique secret into WPBridge config file <code>[your_rust_server]/oxide/config/WPBridge.json</code>', 'wpbridge-for-rust'); ?></label>
    <?php
    }

    function WPB_F_R_RustMapAPISettingsFieldCallback()
    {
        $rustMapAPIKey = get_option('wpbridge_rustmapapi_field','');

    ?>
        <input type="text" id="wpbridge_rustmapapi_field" class="regular-text" name="wpbridge_rustmapapi_field" value="<?php echo esc_html($rustMapAPIKey); ?>" placeholder="<?php echo __('Paste your RustMap API Key', 'wpbridge-for-rust'); ?>" />
        <?php
        if($rustMapAPIKey != "")
        {
        ?>
        <a href="?page=wpbridge-rustmapapi-gen" class="button button-primary"><?php echo __('Generate map', 'wpbridge-for-rust'); ?></a>
        <?php
        }
        ?>

        <br>
        <label for="wpbridge_rustmapapi_field"><?php echo __('Get your API Key on ', 'wpbridge-for-rust'); ?><a href="https://rustmaps.com/" target="_blank">rustmaps.com</a></label>
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
            '<span class="wpbridge_clear_statistics_elem">' . __('Clear Player Database','wpbridge-for-rust') . '</span>',
            'manage_options',
            'wpbridge-purge-statistics-database',
            [$this,'WPB_F_R_Player_Statistics_SubMenu_Template_Callback']
        );
    }

    function WPB_F_R_RustMapAPIGen_SubMenu()
    {
        add_submenu_page(
            'wpbridge-settings-page',
            '',
            __('Generate Map','wpbridge-for-rust'),
            'manage_options',
            'wpbridge-rustmapapi-gen',
            [$this,'WPB_F_R_RustMapAPIGen_SubMenu_Template_Callback']
        );
    }

    function WPB_F_R_RustMapAPIGen_SubMenu_Template_Callback()
    {
        if (!current_user_can('manage_options')) wp_die(__('Your user don\'t have permissions to do that action.','wpbridge-for-rust'));
        
        global $wpdb;
        $rustMapApiKey = get_option('wpbridge_rustmapapi_field','');
        $settings = $wpdb->get_row("SELECT `seed`,`worldsize` FROM `" . esc_sql(WPBRIDGE_SETTINGS_TABLE) . "` WHERE id = 1");
        ?>
        <div class="wrap">
            <h3><?php echo __('WPBridge for Rust - Settings', 'wpbridge-for-rust'); ?></h3>
            <hr />
        <?php
        if($settings->seed == 0 || $settings->worldsize == 0 || $rustMapApiKey == "")
        {
            ?><h1><?php echo __('Ops!','wpbridge-for-rust') ?></h1>
            <p><?php
            echo __('It seems that either your map seed, world size or your rustmaps api key is wrong. Please check that your server is properly connected and that your rustmaps api key is correct.','wpbridge-for-rust');
            echo "<br>";
            echo __('When your server is properly connected the server details tab are populated.','wpbridge-for-rust');
            ?>
            </p>
            <?php
        } else {
            $apiEndPoint = "https://rustmaps.com/api/v2/maps/$settings->seed/$settings->worldsize?staging=false&barren=false";
            $curlGetImageName = curl_init();
            curl_setopt_array($curlGetImageName, array(
                CURLOPT_URL => $apiEndPoint,
                CURLOPT_POSTFIELDS => "",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                    "postman-token: 4565ffcb-d550-7ea0-a895-f1c26fba5264",
                    "x-api-key: fb8524c7-cfc7-4fa2-b66e-53294a65f73b"
                ),
                CURLOPT_RETURNTRANSFER => 1
            ));

            $responseGetImageName = curl_exec($curlGetImageName);
            $err = curl_error($curlGetImageName);
            curl_close($curlGetImageName);          
            if($err)
            {
            ?>
            <h1><?php echo __('Failed to fetch map from rustmaps.com','wpbridge-for-rust'); ?> [CURL_ERROR] [responseGetImageName]</h1>
            <?php
            } else {
                $responseGetImageName = json_decode($responseGetImageName);
                if($responseGetImageName)
                {
                    if(isset($responseGetImageName->mapId) && $responseGetImageName->mapId !== "")
                    {
                        update_option('wpbridge_rustmapapigeneratedfilename', $responseGetImageName->mapId);
                        $curlGetImageFile = curl_init("https://files.rustmaps.com/img/217/$responseGetImageName->mapId/FullLabeledMap.png");
                        curl_setopt_array($curlGetImageFile, array(
                            CURLOPT_RETURNTRANSFER => 1
                        ));
                        $responseGetImageFile = curl_exec($curlGetImageFile);
                        $err = curl_error($curlGetImageFile);
                        curl_close($curlGetImageFile);
                        if($err)
                        {
                            ?>
                            <h2><?php echo __('Failed to fetch map from rustmaps.com','wpbridge-for-rust'); ?> [CURL_ERROR] [responseGetImageFile]</h2>
                            <?php
                        } else {
                            $todayDate = new DateTime('NOW');
                            $todayDate = $todayDate->format('d_m_Y');
                            $imageTitle = 'RustMap_' . $settings->seed . '_' . $settings->worldsize;
                            $localFilePath = wp_upload_dir()['basedir'] . '/' . $imageTitle . '.png';
                            $localFileSrc = '/wp-content/uploads/' . $imageTitle . '.png';
                            file_put_contents($localFilePath,$responseGetImageFile);
                            $upload_id = wp_insert_attachment( array(
                                'guid'           => $localFilePath, 
                                'post_mime_type' => 'image/png',
                                'post_title'     => 'RustMap_'.$settings->seed.'_'.$settings->worldsize,
                                'post_content'   => 'Rust map generated in WPBridge by rustmaps.com with seed '.$settings->seed.' and world size ' . $settings->worldsize,
                                'post_status'    => 'inherit'
                            ), $localFilePath );
                            
                            require_once( ABSPATH . 'wp-admin/includes/image.php' );
                            wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $localFilePath ) );
                            ?>
                            <h3>Seed: <?php echo $settings->seed; ?>, World size: <?php echo $settings->worldsize; ?></h3>
                            <p><?php echo __('Rust map successfully generated using','wpbridge-for-rust'); ?> <a target="_blank" href="https://rustmaps.com/map/<?php echo esc_html($settings->worldsize); ?>_<?php echo $settings->seed; ?>">rustmaps.com API</a></p>
                            <img src="<?php echo $localFileSrc; ?>" alt="Map generated using rustmaps.com API" width="500". height="500" />
                            <br>
                            <h1><?php echo __('Rust Map saved in Media','wpbridge-for-rust'); ?></h1>
                            <?php
                        }
                    } else {
                        ?>
                        <h1><?php echo __('Failed to fetch map from rustmaps.com','wpbridge-for-rust'); ?> [JSON_ERROR] [responseGetImageName]</h1>
                        <?php
                    }
                } else {
                    ?>
                    <h1><?php echo __('Failed to fetch map from rustmaps.com','wpbridge-for-rust'); ?> [JSON_ERROR] [responseGetImageName]</h1>
                    <?php
                }
            }
        }

        ?>
            <br>
            <a href="?page=wpbridge-settings-page" class="button button-primary button-warning"><?php echo __('Go back', 'wpbridge-for-rust'); ?></a>
        </div>
        <?php
    }
    
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
        global $wpdb;
        $settings = $wpdb->get_row("SELECT `seed`,`worldsize` FROM `" . WPBRIDGE_SETTINGS_TABLE . "` WHERE id = 1");
        ?>
        <form action="options.php" method="post">
            <div class="wrap">
                <h3><?php echo esc_html( get_admin_page_title() ) . ' ' . __(' - Settings', 'wpbridge-for-rust'); ?></h3>
                <?php settings_fields('wpbridge-settings-page'); ?>
                
                <div id="wpbridge_settings_tabs">
                    <ul>
                        <li><a href="#wpbridge_server_settings_tab"><?php echo __('Server Settings'); ?></a></li>
                        <li><a href="#wpbridge_server_details_tab"><?php echo __('Server Details'); ?></a></li>
                        <?php
                        if($settings->seed != 0 && $settings->worldsize != 0)
                        {
                        ?>
                        <li><a href="#wpbridge_rustmaps_settings_tab"><?php echo __('RustMap API Settings'); ?></a></li>
                        <?php
                        }
                        ?>
                        <li><a href="#wpbridge_documentation_tab"><?php echo __('Documentation'); ?></a></li>
                    </ul>
                    <div id="wpbridge_server_settings_tab">
                        <?php do_settings_sections('wpbridge-settings-page');?>
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
                    </div>
                    <div id="wpbridge_server_details_tab">
                        <?php do_settings_sections('wpbridge-server-details-page');?>
                    </div>
                    <?php
                    if($settings->seed != 0 && $settings->worldsize != 0)
                    {
                    ?>
                    <div id="wpbridge_rustmaps_settings_tab">
                        <?php do_settings_sections('wpbridge-settings-page-rustmap');?>
                    
                    <?php
                    
                    if($settings->seed == 0 || $settings->worldsize == 0 || !file_exists(WP_CONTENT_DIR . '/uploads/RustMap_' . $settings->seed . '_' . $settings->worldsize . '.png'))
                    {
                    $rustMapErrorText = wp_sprintf(__('Current map settings are invalid or last generated image file is missing, using seed %s and world size %s','wpbridge-for-rust'),$settings->seed,$settings->worldsize);
                    ?>
                    <h3><?php echo $rustMapErrorText; ?></h3>
                    <?php    
                    } else
                    {
                        $rustMapDetailText = wp_sprintf(__('Current map using seed %s and world size %s','wpbridge-for-rust'),$settings->seed,$settings->worldsize);
                    ?>
                    <hr>
                    <h3><?php echo $rustMapDetailText; ?></h3>
                    <img src="<?php echo content_url() . '/uploads/RustMap_' . $settings->seed . '_' . $settings->worldsize . '.png'; ?>" width="400" height="400">
                    <?php
                    }
                    ?>
                    </div>
                    <?php
                    }
                    ?>
                    <div id="wpbridge_documentation_tab">
                        <h3>Eventually there will be an easier way to lookup documentation.</h3>
                        For now, all documentation is only available at the <a target="_blank" href="https://wpbridge.danlevi.no/shortcode-documentation/">official website for WPBridge for Rust</a>
                    </div>
                </div>
                <?php echo submit_button(); ?>
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