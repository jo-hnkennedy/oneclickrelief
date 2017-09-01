(function($) {
    "use strict"; // Start of use strict

    // set mobiel  table restruction
    var setMobileTable = function( selector ){
        $( 'tr', selector ).each(function(){
            //set html
            var pt = $( selector );
            var ht = $(this).html();
            var dv = '<div>';
            //get children
            pt.html( pt.html().
               split('</td>').join('</div>').
               split('</div></tr>').join('</div></td></tr>').
               split('<td>').join(dv).
               split('<tr><div>').join('<tr><td>'+dv) 
            );
        });
    };

    //check if is mobile
    var isMobile = (window.screen.availWidth < 716);

    //check if it's mobile and switch table data structure
    if( isMobile ) {
        //setMobileTable( '.number-table' );
    }

    // jQuery for page scrolling feature - requires jQuery Easing plugin
    $(document).on('click', 'a.page-scroll', function(event) {
        var offsetTop = 50;
        var $anchor = $(this);
        var targetOffset = ($($anchor.attr('href')).offset().top-offsetTop);
        var targetScope = $('html,body');
        targetScope.animate({
            scrollTop: targetOffset
        }, 1000, 'easeInOutExpo', function(){
            $(targetScope).off("scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove");
        });
        targetScope.scroll(function(){
            $(targetScope).stop(true, false);
        });
        event.preventDefault();
    });

    // Highlight the top nav as scrolling occurs
    $('body').scrollspy({
        target: '.navbar-fixed-top',
        offset: 51
    });

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a').click(function() {
        $('.navbar-toggle:visible').click();
    });

    // Offset for Main Navigation
    $('#mainNav').affix({
        offset: {
            top: 100
        }
    });

    //progress bar animations
    $('.progress .progress-bar').css("width", function() {
        return $(this).attr("aria-valuenow") + "%";
    });
    
    // click on a selector
    $('.data-click').click(function() {
        var selector = $(this).data('click');
        if( selector && $( selector ) ) {
            $( selector ).trigger('click');
        }
    });

    // toggle between two selectors
    $('.data-toggle').click(function() {
        //check for data toggle
        var button = $(this);
        var selector = $(this).data('toggle');
        var btnlabel = $(this).data('labels');
        //check for selector
        if( selector ) {
            //check for multiple
            selector = (selector.indexOf(',') != -1 ) ? selector.split(',') : [ selector ];
            if( btnlabel ) {
                btnlabel = (btnlabel.indexOf(',') != -1 ) ? btnlabel.split(',') : [ btnlabel ];
            }
            //look in each selector
            for(var s in selector) {
                //check for label
                if( $( selector[s] ).hasClass('hide') ) {
                    $( selector[s] ).removeClass('hide');
                    if( btnlabel ) {
                        button.html( btnlabel[0] );
                    }
                } else {
                    $( selector[s] ).addClass('hide');
                    if( btnlabel ) {
                        button.html( btnlabel[1] );
                    }
                }
            }
        }
    });


    // Initialize and Configure Scroll Reveal Animation
    window.sr = ScrollReveal();
    //check if not printing
    if( !window.Printing ) {
        sr.reveal('.sr-icons', {
            duration: 600,
            scale: 0.3,
            distance: '0px'
        }, 200);
        sr.reveal('.sr-button', {
            duration: 1000,
            delay: 200
        });
        sr.reveal('.sr-contact', {
            duration: 600,
            scale: 0.3,
            distance: '0px'
        }, 300);
        sr.reveal('.sr-text', {
            duration: 800,
            scale: 0.3,
            distance: '0px'
        });
        sr.reveal('.sr-list', {
            delay: 200,
            duration: 600,
            rotate: { x: 0, y: 40, z: 0 }
        }, 50);
        sr.reveal('.sr-fold', {
            delay: 50,
            duration: 400,
            rotate: { x: 10, y: 20, z: 30 }
        }, 100);
    }
    // Initialize and Configure Magnific Popup Lightbox Plugin
    $('.popup-gallery').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-with-zoom',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1], // Will preload 0 - before current, and 1 after the current image
            tPrev: 'Previous (Left arrow key)',
            tNext: 'Next (Right arrow key)',
            tCounter: 'Game %curr% of %total%'
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            cursor: 'mfp-zoom-out-cur',
            titleSrc: function(item) {
              return $('.project-name', $(item.el).parent() ).html() + ' <small>Jackpot ' + $('.project-category', $(item.el).parent() ).html()+ '</small>';
            },
            markup: '<div class="mfp-figure">'+
                        '<div class="mfp-close"></div>'+
                        '<div class="mfp-overlay"></div>'+
                        '<div class="mfp-buttons"></div>'+
                        '<div class="mfp-img"></div>'+
                        '<div class="mfp-bottom-bar">'+
                            '<div class="mfp-title"></div>'+
                            '<div class="mfp-counter"></div>'+
                        '</div>'+
                    '</div>',
        },
        callbacks: {
            beforeOpen: function(item) {
                console.log('Start of popup initialization', item);
            },
            elementParse: function(item) {
                // Function will fire for each target element
                // "item.el" is a target DOM element (if present)
                // "item.src" is a source that you may modify

                console.log('Parsing content. Item object that is being parsed:', item);
            },
            change: function(item) {
                console.log('Content changed', item);
                console.log(this.content); // Direct reference to your popup element
            },
            resize: function(item) {
                console.log('Popup resized', item);
                // resize event triggers only when height is changed or layout forced
            },
            open: function(item) {
                console.log('Popup is opened', item);
            },

            beforeClose: function(item) {
                // Callback available since v0.9.0
                console.log('Popup close has been initiated', item);
            },
            close: function(item) {
                console.log('Popup removal initiated (after removalDelay timer finished)', item);
            },
            afterClose: function() {
                console.log('Popup is completely closed');
            },
            markupParse: function(template, values, item) {
                // Triggers each time when content of popup changes
                // console.log('Parsing:', template, values, item);
                console.log('Popup markup parse', template, values, item);
                //add buttons
                var buttons = '<div class="button-group middle col-md-8">' + 
                    '<h1><b>' + values.title.split('<small>').join('</b><br><small>') + '</h1>' + 
                    '<a href="#winners" class="page-scroll btn btn-default btn-xl sr-button view_past_winners">View Past Winners</a> ' + 
                    '<a href="#numbers" class="page-scroll btn btn-primary btn-xl sr-button generate_numbers">Generate Numbers</a> ' + 
                    '<a href="#checkme" class="page-scroll btn btn-default btn-xl sr-button check_numbers">CHeck Numbers</a> ' + 
                    '</div>';
                $( '.mfp-buttons', template ).html(buttons);
                //set events
                $( '.page-scroll', template ).on('click', function(){
                    $('.mfp-close').trigger( 'click' );
                });
            },
            updateStatus: function(data) {
                console.log('Status changed', data);
                // "data" is an object that has two properties:
                // "data.status" - current status type, can be "loading", "error", "ready"
                // "data.text" - text that will be displayed (e.g. "Loading...")
                // you may modify this properties to change current status or its text dynamically
            },
            imageLoadComplete: function(item) {
                // fires when image in current popup finished loading
                // avaiable since v0.9.0
                console.log('Image loaded', item);
            },


            // Only for ajax popup type
            parseAjax: function(mfpResponse) {
                // mfpResponse.data is a "data" object from ajax "success" callback
                // for simple HTML file, it will be just String
                // You may modify it to change contents of the popup
                // For example, to show just #some-element:
                // mfpResponse.data = $(mfpResponse.data).find('#some-element');

                // mfpResponse.data must be a String or a DOM (jQuery) element

                console.log('Ajax content loaded:', mfpResponse);
            },
            ajaxContentAdded: function(item) {
                // Ajax content is loaded and appended to DOM
                console.log('Popup Ajax Content Added',this.content, item);
            }
        },
        zoom: {
            enabled: true, // By default it's false, so don't forget to enable it
            duration: 300, // duration of the effect, in milliseconds
            easing: 'ease-in-out', // CSS transition easing function

            // The "opener" function should return the element from which popup will be zoomed in
            // and to which popup will be scaled down
            // By defailt it looks for an image tag:
            opener: function(openerElement) {
              // openerElement is the element on which popup was initialized, in this case its <a> tag
              // you don't need to add "opener" option if this code matches your needs, it's defailt one.
              return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        }
    });

})(jQuery); // End of use strict
