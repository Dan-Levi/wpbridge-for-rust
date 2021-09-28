(function($){
"use strict"

const wpbridge_secret_generate_button = $("#wpbridge_secret_generate_button")
const wpbridge_secret_field = $("#wpbridge_secret_field")
wpbridge_secret_generate_button.on('click', (e) => {
    e.preventDefault()
    wpbridge_secret_field.val(generateSecret(20))


})

})(jQuery)

function generateSecret(n) {
    var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_*!#$.,-';
    var token = '';
    for(var i = 0; i < n; i++) {
        token += chars[Math.floor(Math.random() * chars.length)];
    }
    return token;
}