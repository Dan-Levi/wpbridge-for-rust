(function($){
"use strict"

/**
 * Secret
 */
const wpbridge_secret_generate_button = $("#wpbridge_secret_generate_button")
const wpbridge_secret_field = $("#wpbridge_secret_field")
wpbridge_secret_generate_button.on('click', (e) => {
    e.preventDefault()
    wpbridge_secret_field.val(generateSecret(20))
})

/**
 * Database
 */
const wpbridge_database_purge_players_button = $("#wpbridge_database_purge_players_button");
wpbridge_database_purge_players_button.on('click', (e) => {
    e.preventDefault();
    if(confirm("Are you sure you want to purge all player data?"))
    {
        window.location.replace("/wp-admin/admin.php?page=wpbridge-settings-page&action=purge_player_database");
    }
});

/**
 * GET Params
 */
let getParams = new URLSearchParams(window.location.search);
if(getParams.has('page') && getParams.has('result'))
{
    const page = getParams.get('page');
    const result = getParams.get('result');
    if(page == 'wpbridge-settings-page')
    {
        if (result == 'purge_player_database')
        {
            window.history.pushState("data","Title",'/wp-admin/admin.php?page=wpbridge-settings-page');
            alert("Player database purged");
        } 
    }
}

})(jQuery)

function generateSecret(n) {
    var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_*!#$.,-';
    var token = '';
    for(var i = 0; i < n; i++) {
        token += chars[Math.floor(Math.random() * chars.length)];
    }
    return token;
}