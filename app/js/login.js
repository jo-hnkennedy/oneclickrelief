(function($) {
    "use strict"; // Start of use strict

    //CLEAN STRING VALUE 
    function clean(a,d,c,b){
        d = (d) ? d : '';c = (c) ? c : '~!@#$%^&*()_+`-=<>,./?|";:[]\\{} '.split('');
        for(b in c){if(a.indexOf(c[b]) != -1){a=a.split(c[b]).join(d);}}return a;
    }
    //get href
    var dlocation = document.location.href;
    //get retry click
    var retryClick = localStorage.getItem('retry_click');
    //create button
    var loginForm = function( form ){

        //hide message
        $('.status_text', form ).addClass('hide');
        //remove class
        $('.status_text', form ).removeClass('alert-success alert-danger alert-warning alert-info');
        //set redir
        var redir = $('input.redirect', form);
        //get serialized key/value of each field
        var vals = $( form ).serializeArray();
        //set default data
        var post = {};
        //get data
        for(var v in vals){
            post[ vals[v].name ] = vals[v].value;
        }
        //post data
        $.ajax({
            //no timeout
            timeout: 0,
            //no cache
            cache: 0,
            //set options
            data: post,
            //get json data
            dataType:'json',
            //set request method
            method: 'POST',
            //set url
            url: $( form ).attr('action'),
            //set before send
            beforeSend: function( xhr ) {
                //xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
            }
            //when done
        }).done(function( response ){
            //set success
            if( response.status == 1 ) {
                //set success
                $('.status_text', form ).addClass('alert-success');
                //hide main login form
                $('.main-login-form', form).hide();
                //wait to redirect
                window.setTimeout(function(){
                    window.location.href = (redir.length > 0) ? redir.val(): 'index.php';
                },1000);
            //set success
            } else if( response.status == 2 ) {
                //wait to redirect
                window.setTimeout(function(){
                    window.location.href = 'login.php?activate=' + response.data;
                },1000);
                //hide main login form
                $('.main-login-form'. form).hide();
                //set success
                $('.status_text', form ).addClass('alert-warning');
            } else {
                //set success
                $('.status_text', form ).addClass('alert-danger');
            }
            //check for status
            if( response.status > 0 ) {
                //reset form
                $("input,textarea,select", form).not('[type="checkbox"]').val('');
            }
            //set message text
            $('.status_text div', form).html( response.text );

        }).fail(function( response ) {
            //set success
            $('.status_text', form ).addClass('alert-danger');
            //set message text
            $('.status_text div', form ).html( response.text ? response.text : 'Server could satisfy request..' );
        
        }).always(function( response ) {
            //remove class
            $('.status_text', form ).removeClass('hide').addClass('in');
            //scroll to message
            $('html, body').stop().animate({
                scrollTop: ( $('.status_text', form ).offset().top - 50)
            }, 1250, 'easeInOutExpo');
            //reset close button
            $('.status_text button'. form).off().on('click',function(){
                $('.status_text', form ).removeClass('in').addClass('hide');
            });
        });
    };
    //check for logout
    if( dlocation.indexOf('logout=1') != -1 ) {
        document.cookie = "<?php echo $crd_snid; ?>=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
    }
    //sset redirect
    if( dlocation.indexOf('redir=') != -1 ) {
        //set redire
        var redirect = decodeURIComponent( dlocation.split('redir=')[1] );
        //set status text
        $('.status_text').removeClass('hide').addClass('alert-warning');
        //set messagee
        $('.status_text div').html('Redirecting to: ' + redirect );
        //wait for a few seconds
        window.setTimeout(function(){
            document.location.href =  redirect;
        },1000);
    }
    //check retry action
    if( retryClick ) {
        //parse retry
        var parseRetry = JSON.parse( retryClick );
        //begin clicking
        var clicking = window.setInterval(function(){
            if( parseRetry.length > 0 ) {
                $( parseRetry.pop() ).trigger('click');
            } else {
                //remove 
                localStorage.removeItem('retry_click');
                //clear interval
                window.clearInterval( clicking );
            }
        }, 800);
    }
    // user_form
    $('.user_form').on('submit', function( event ){
        event.preventDefault();
        event.stopPropagation();
        loginForm( $(this) );
    });
    $('.submit_btn').on('click', function( event ){
        event.preventDefault();
        event.stopPropagation();
        loginForm( $(this).closest('.user_form') );
    });
    // clean username
    $('#rg_username').on('keyup', function(){
        $(this).val( clean( $(this).val(), '.') );
    });
    //set agreed
    if( $('#agreed').length > 0 ){ 
        $('#agreed').on('change', function(){
            //check for start register button
            if( $('#start-register').length > 0 ){
                $('#start-register').attr('disabled', !$(this).is(':checked') );
            }
            //check for start register content
            if( $('.start-register').length > 0 && !$(this).is(':checked') ) {
                $('.start-register').addClass('hide');
            }
        });
        //check for start register button
        if( $('#start-register').length > 0 ){
            $('#start-register').attr('disabled', true);
        }
    }
    //get complete register
    $('#start-register').on('click', function(){
        if( !$(this).data('target') && !$(this).attr('disabled') ) {
            $('.start-register').removeClass('hide');
        } else {
            $('.start-register').addClass('hide');
        }
    });
    //save data
    $('.save-local').on('click', function(){
        debugger;
        //get data na
        var name = $(this).data('name');
        var value = $(this).data('value');
        //set to local storage
        localStorage.setItem(name, value);
    });
    //set login modal event
    $('#login,#signup').on('show.bs.modal', function(event, index, clicks, array) {
        var redirect = $('input[name="redirect"]').val();
        var target = $(event.relatedTarget);
        var form = $(event.currentTarget);
        var retry = target.data('retry');
        //check for retry
        if( retry ) {
            //set clicks
            clicks = retry.indexOf(',')!=-1 ? retry.split(',') : [retry];
            //set redirect
            $('input[name="redirect"]').val( redirect + clicks[0] );
            //set array
            array = [ 
                '#'+target.attr('id'), 
                '*[href="' + clicks.unshift() + '"]:eq(0)' 
            ];
            //check for length
            if( clicks.length > 0 ) {
                for(var c in clicks ) {
                    array.push( clicks[c] );
                }
            }
            //set retry action
            localStorage.setItem('retry_click', JSON.stringify(array) );
        }
    });
})(jQuery); // End of use strict
