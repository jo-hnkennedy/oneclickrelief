(function($) {
    "use strict";

    //set window printing
    window.Printing=true;
    //add print class
    $('body').addClass('print');
    //wait for everything to load
    $( window ).load(function() {
        window.setTimeout(function(){
            window.print();
            window.setTimeout(function(){
                window.close();
            },500);
        },3000);
    });

})(jQuery);
