(function($){
"use strict"
$("#wpbridge_settings_tabs").tabs();
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
    if(confirm("Are you sure you want to clear all player data?"))
    {
        window.location.replace("?page=wpbridge-purge-statistics-database");
    }
});

const wpbridge_clear_statistics_elem = $(".wpbridge_clear_statistics_elem");
wpbridge_clear_statistics_elem.on('click', (e) => {
    e.preventDefault();
    if(confirm("Are you sure you want to clear all player data?"))
    {
        window.location.replace("?page=wpbridge-purge-statistics-database");
    }
});

})(jQuery)

function generateSecret(n) {
    var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_*!#$.,-';
    var token = '';
    for(var i = 0; i < n; i++) {
        token += chars[Math.floor(Math.random() * chars.length)];
    }
    return token;
}