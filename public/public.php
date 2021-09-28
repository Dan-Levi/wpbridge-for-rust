<?php

class WPBRIDGE_PUBLIC
{
    private static $_instance = null;

    public function __construct()
    {
        // wp_loaded
        add_action('wp_enqueue_scripts', [$this,'LoadStyles']);
    }

    function LoadStyles()
    {
        wp_enqueue_style(
            'wpbridge-public-style',
            WPBRIDGE_URL . 'public/css/public.css',
            '',
            rand()
        );
    }

    static function instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new WPBRIDGE_PUBLIC();
        }
        return self::$_instance;
    }
}

WPBRIDGE_PUBLIC::instance();