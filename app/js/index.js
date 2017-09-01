(function($) {
    "use strict"; // Start of use strict

    //set tab section
    $('.tab-section ul li a').on('click', function() {
        $(this).tab('show')
    });
    //set global variables
    var Default = {
    };

    //SET ACTION
    var Action = {
        'message': {}
    };
    //SET TARGET
    var Target = {
        options: false,
        content: false,
        section: false,
        results: false,
        message: false
    };

    //CLEAN STRING VALUE 
    function clean(a, d, c, b) {
        d = (d) ? d : '';
        c = (c) ? c : '~!@#$%^&*()_+`-=<>,./?|";:[]\\{} '.split('');
        for (b in c) {
            if (a.indexOf(c[b]) != -1) { a = a.split(c[b]).join(d); }
        }
        return a;
    }

    //GET REQUEST
    var getRequest = function(target, reset, options, callback) {
        //do not submit
        event.preventDefault();
        event.stopPropagation();
        //set reset
        reset = 'red blue green black';
        //set message
        var opt = target.options;
        var msg = target.message;
        var res = target.results;
        var sec = target.section;
        var mth = target.method;
        var out = target.output;
        var url = target.url;
        //set results
        $(res).html('');
        //remove hide
        $(sec).addClass('hide');
        //set status
        $(msg).html('Loading...');
        $(msg).removeClass(reset);
        //set default data
        var request = opt;
        //extend with data
        if (options) {
            for (var o in options) {
                request[o] = options[o];
            }
        }
        //post data
        $.ajax({
            //no cache
            cache: 0,
            //no timeout
            timeout: 0,
            //set options
            data: request,
            //get json data
            dataType: 'json',
            //set request method
            method: mth,
            //set url
            url: url,
            //set before send
            beforeSend: function(xhr) {
                //xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
            }
                //when done
        }).done(function(result) {
            //set success
            if (result.status == 1) {
                //set success
                $(msg).html(out ? result.text : '');
                //set results
                $(res).html(result.data);
                //remove hide
                $(sec).removeClass('hide');
            } else {
                //set success
                $(msg).addClass('red');
            }
            //set message text
            $(msg).html(result.text);

        }).fail(function(result) {
            //set success
            $(msg).addClass('red').html('Server could satisfy request..');

        }).always(function(result) {
            //check for callback
            if (typeof callback == 'function') {
                callback(result);
            }
        });
    };


    //–––––––––––––––––––––––––––––––----
    // Submit a ticket or contact us
    //–––––––––––––––––––––––––––––––----
    $('#characterLeft').text('140 characters left');
    $('#feedback_text').keydown(function() {
        var max = 140;
        var len = $(this).val().length;
        if (len >= max) {
            $('#characterLeft').text('You have reached the limit');
            $('#characterLeft').addClass('red');
            $('#feedback_submit').addClass('disabled');
        } else {
            var ch = max - len;
            $('#characterLeft').text(ch + ' characters left');
            $('#feedback_submit').removeClass('disabled');
            $('#characterLeft').removeClass('red');
        }
    });

    //set message clear event
    $('#feedback_clear').on('click', function(event) {
        //prevent and stop
        event.preventDefault();
        event.stopPropagation();
        //clear 
        $('textarea,text,select', $('#feedback')).val('');
    });

    //set feedback submit event;
    $('#feedback_submit').on('click', function(event) {
        //prevent and stop
        event.preventDefault();
        event.stopPropagation();
        //get current
        var this_ = $(this);
        //show user fields
        var none = $('#feedback_none');
        var status = $('#characterLeft');
        var name = $('#feedback_name').val();
        var email = $('#feedback_email').val();
        var message = $('#feedback_text').val();
        //set target url
        Target.url = '/feedback.php';
        //set result
        Target.output = false;
        //set method
        Target.method = 'POST';
        //set section
        Target.section = none;
        //set tartet
        Target.results = status;
        //set message
        Target.message = status;
        //set options
        Target.options = { send: 'feedback' };
        //get request
        getRequest(Target, '', {
            time: Date.now(),
            send: 'feedback',
            name: name,
            email: email,
            message: message
        }, function(response) {
            //check response
            if (response.status == 1) {
                //clear text
                $('input,textarea,select', $('#message')).val('');
            }
        });
    });


    //–––––––––––––––––––––––––––––––----
    // Data tables with full features
    //–––––––––––––––––––––––––––––––----
    if( $('.data_table').length > 0 ){
        //loop in each data table
        $('.data_table').each(function(idx, elm){
            //check for id
            var _this = $(this);
            //check for id
            if( !$(this).attr('id') ) {
                $(this).attr('id', 'dataTable' + ( idx+1) ); 
            }
            //set data table
            var table = $( _this ).DataTable({
                "dom":            'Bfrtip',
                "responsive":     _this.data('responsive')    || true,
                "paging":         _this.data('paging')        || true,
                "lengthChange":   _this.data('lengthchange')  || true,
                "searching":      _this.data('searching')     || true,
                "ordering":       _this.data('ordering')      || true,
                "processing":     _this.data('processing')    || true,
                "scrollX":        _this.data('scrollx')       || true,
                "scrollY":        _this.data('scrolly')       || true,
                "stateSave":      _this.data('statesave')     || true,
                "info":           _this.data('info')          || true,
                "autoWidth":      _this.data('autowidth')     || true,
                "deferRender":    _this.data('deferrender')   || true,
                "fixedHeader":    _this.data('fixedheader')   || true,
                "buttons":        _this.data('buttons') ? [
                    {
                        extend: 'edit',
                        text: '<i class="fa fa-cololumns"></i> Edit'
                    },
                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-files-o"></i> Copy'
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-excel-o"></i> Excel'
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-0"></i> CSV'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-pdf-o"></i> PDF'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print'
                    }
                ] : false
            });
        })
    }


    //–––––––––––––––––––––––––––––––----
    // Export chart as data table
    //–––––––––––––––––––––––––––––––----
    if( $('.export-chart').length > 0 ) {
        var createdDataTable = [];
        $('.export-chart').on('click', function(){
            var target = $(this).data('target');
            var source = $(this).data('source');
            var type = $(this).data('type');
            var name = $(this).data('name');
            var status = $(this).siblings('.status');
            var defer = $.Deferred();
            if( createdDataTable.indexOf( name ) == -1 ){
                status.html('<i clas="fa fa-spinner fa-spin"></i> Getting data, please wait ..');
                status.removeClass('hide');
                createdDataTable.push( name );
                $('#'+source).DataTable({
                    "ajax": '?ajax=' + name,
                    "dom":            'Bfrtip',
                    "buttons": [
                        {
                            extend: 'edit',
                            text: '<i class="fa fa-cololumns"></i> Edit'
                        },
                        {
                            extend: 'copyHtml5',
                            text: '<i class="fa fa-files-o"></i> Copy'
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fa fa-excel-o"></i> Excel'
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="fa fa-file-0"></i> CSV'
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="fa fa-pdf-o"></i> PDF'
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa fa-print"></i> Print'
                        }
                    ],
                    "initComplete": function(settings, json) {
                        status.addClass('hide');
                        defer.resolve();
                    }
                });
            } else {
                defer.resolve();
            }
            $.when( defer ).done( function(){
                $('#'+target + ' a.buttons-'+type).trigger('click');
            });
        })
    }

})(jQuery); // End of use strict
