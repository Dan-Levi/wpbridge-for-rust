<?php
global $wpdb;
define( 'WPBRIDGE_PLUGIN_VERSION', $this->plugin_version);

define( 'WPBRIDGE_SETTINGS_TABLE', $wpdb->prefix . 'wpbridge_settings' );
define( 'WPBRIDGE_PLAYER_STATS_TABLE', $wpdb->prefix . 'wpbridge_player_stats' );
define( 'WPBRIDGE_PLAYER_STATS', [
    'joins',            
    'leaves',               
    'deaths',               
    'suicides',             
    'kills',                
    'headshots',            
    'wounded',              
    'recoveries',           
    'crafteditems',         
    'repaireditems',        
    'explosivesthrown',     
    'voicebytes',           
    'hammerhits',           
    'reloads',              
    'shots',                
    'collectiblespickedup', 
    'growablesgathered',    
    'chats',                
    'npckills',             
    'meleeattacks',         
    'mapmarkers',           
    'respawns',             
    'rocketslaunched',      
    'antihackviolations',   
    'npcspeaks',            
    'researcheditems'  
]);
