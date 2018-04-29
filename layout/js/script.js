$(document).ready(function () {

    // show/hide the forms
    $('.formbox .message a').click(function(e){
        e.preventDefault();
        $('form').animate({height:"toggle" , opacity:"toggle"} , "slow");
    });
    
});
