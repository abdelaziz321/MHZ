/*global $*/

$(document).ready(function () {
    "use strict";

    var registerBtns = $('.register_page .message a');

    // toggle between the login | register forms
    registerBtns.click(function (e) {
        e.preventDefault();
        $('form').animate({height:"toggle" , opacity:"toggle"} , "slow");
    });

});
