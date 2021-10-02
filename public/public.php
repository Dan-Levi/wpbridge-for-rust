<?php

class WPB_F_R_WPBRIDGE_PUBLIC
{
    private static $_instance = null;

    public function __construct()
    {
        // wp_loaded
        add_action('wp_enqueue_scripts', [$this,'WPB_F_R_LoadStyles']);
    }

    function WPB_F_R_LoadStyles()
    {
        wp_enqueue_script(
            'wpbridge-public-script',
            WPBRIDGE_URL . 'public/js/public.js',
            array('jquery'),
            rand(),
            true
        );
        wp_enqueue_style(
            'wpbridge-public-style',
            WPBRIDGE_URL . 'public/css/public.css',
            '',
            rand()
        );
    }

    static function WPB_F_R_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPB_F_R_WPBRIDGE_PUBLIC();
        }
        return self::$_instance;
    }
}

WPB_F_R_WPBRIDGE_PUBLIC::WPB_F_R_instance();