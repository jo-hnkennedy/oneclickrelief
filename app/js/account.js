(function($) {
    "use strict"; // Start of use strict
    
    $.widget.bridge('uibutton', $.ui.button);
    
    $('#myCarousel').carousel({
        interval: 10000
    })
    
    $('[data-toggle="tooltip"]').tooltip();


    $(document).ready(function() {
        //get report summary
        $('.show_report').on('click', function(){
            //get reprot
            var report = $(this).siblings('.report');
            //check for report
            if( report ) {
                $('#report_body').html( report.html() );
            }
        });
        //open chat box
        $('.open-chat').on('click', function(){
            $('.direct-chat').removeClass('direct-chat-contacts-open collapsed-box');
            //check if hidden
            if( !$('.direct-chat .box-body').is(':visible') ) {
                $('.direct-chat .box-title').trigger('click');
            }
            $('.direct-chat .box-body,.direct-chat .box-footer').show();
        });

        //close dialog
        $('.close-dialog').on('click', function(){
            $('*[data-dismiss="modal"]').first().trigger('click');
        });

        var $btnSets = $('#responsive'),
        $btnLinks = $btnSets.find('a');
     
        $btnLinks.click(function(e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.user-menu>div.user-menu-content").removeClass("active");
            $("div.user-menu>div.user-menu-content").eq(index).addClass("active");
        });

        $("[rel='tooltip']").tooltip();    
     
        $('.view').hover(
            function(){
                $(this).find('.caption').slideDown(250); //.fadeIn(250)
            },
            function(){
                $(this).find('.caption').slideUp(250); //.fadeOut(205)
            }
        ); 
        //search top nav autocomplete
        $( "#SearchTopNav" ).on('submit', function(){ return false; });
        $( "#SearchTopNav input" ).autocomplete({
          source: function( request, response ) {
            $.ajax( {
              url: "/app/search.php",
              dataType: "json",
              data: {
                term: request.term
              },
              success: function( data ) {
                response( data );
              }
            } );
          },
          minLength: 2,
          select: function( event, ui ) {
            top.location.href = '/app/account/gradplan.php?studentId=' + ui.item.value.split(' - ')[0];
          }
        } );
    });

    var activeSystemClass = $('.list-group-item.active');

    //something is entered in search form
    $('#system-search').keyup( function() {
       var that = this;
        // affect all table rows on in systems table
        var tableBody = $('.table-list-search tbody');
        var tableRowsClass = $('.table-list-search tbody tr');
        $('.search-sf').remove();
        tableRowsClass.each( function(i, val) {
        
            //Lower text for case insensitive
            var rowText = $(val).text().toLowerCase();
            var inputText = $(that).val().toLowerCase();
            if(inputText != '')
            {
                $('.search-query-sf').remove();
                tableBody.prepend('<tr class="search-query-sf"><td colspan="6"><strong>Searching for: "'
                    + $(that).val()
                    + '"</strong></td></tr>');
            }
            else
            {
                $('.search-query-sf').remove();
            }

            if( rowText.indexOf( inputText ) == -1 )
            {
                //hide rows
                tableRowsClass.eq(i).hide();
                
            }
            else
            {
                $('.search-sf').remove();
                tableRowsClass.eq(i).show();
            }
        });
        //all tr elements are hidden
        if(tableRowsClass.children(':visible').length == 0)
        {
            tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No entries found.</td></tr>');
        }
    });




    // UPLOAD CLASS DEFINITION
    // ======================
    var dropZone = document.getElementById('drop-zone');
    var uploadForm = document.getElementById('js-upload-form');
    var startUpload = function(files) {
        //set count
        var count = 0;
        //set progress
        var progress=$('#upload_one .progress').removeClass('hide').children('.progress-bar'); 
        //set loading bar
        var wait = window.setInterval(function(){
            progress.attr('aria-valuenow', count).css('width', count+'%');
            progress.children('span.sr-only').html(count + '% Complete');
            //wait until done uploading
            if( count == 100) {
                //clear interval
                window.clearInterval( wait );
                //set uploads html
                var uploads = '';
                //add to uploads html
                for(var f=0;f < files.length;f++){
                    uploads += '<a href="#" class="list-group-item list-group-item-success"><span class="badge alert-success pull-right">Success</span>'+files[f].name+'</a>';
                }
                //set upload finished
                $('.js-upload-finished').removeClass('hide').children('.list-group').html( uploads );
            } else {
                count++;
            }
        },100); 
    };
    if( uploadForm ) {
        uploadForm.addEventListener('submit', function(e) {
            var uploadFiles = document.getElementById('js-upload-files').files;
            e.preventDefault()

            startUpload(uploadFiles)
        });
        dropZone.ondrop = function(e) {
            e.preventDefault();
            this.className = 'upload-drop-zone';

            startUpload(e.dataTransfer.files)
        };

        dropZone.ondragover = function() {
            this.className = 'upload-drop-zone drop';
            return false;
        };
        dropZone.ondragleave = function() {
            this.className = 'upload-drop-zone';
            return false;
        };
    }

})(jQuery); // End of use strict
