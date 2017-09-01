(function($) {
    "use strict"; // Start of use strict

    //set wysiwyg on textarea
    $('textarea').each(function(){
        //get text area id
        var taID = $(this).attr('id');
        //set text area id
        taID = ( taID ) ? taID : 'textarea-'+(this).index();
        //set text areea id
        $(this).attr('id', taID);
    });

    //set wysiwyg
    $('textarea.wysiwyg').wysihtml5({
        toolbar: {
            "fa": true, //Font Awesome icons
            "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
            "emphasis": true, //Italics, bold, etc. Default true
            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
            "link": true, //Button to insert a link. Default true
            "image": true, //Button to insert an image. Default true,
            "color": true, //Button to change color of font  
            "blockquote": false, //Blockquote  
            "size": 'sm' //default: none, other options are xs, sm, lg
        },
        events: {
            "load": function() { 
                //console.log("Loaded!");
            },

            "blur": function(){ 
                //console.log("when the editor (regardless if rich text or source view) gets blurred"); 
                $('.modal-backdrop').remove();
            },
            "blur:composer": function(){ 
                //console.log("when the rich text view gets blurred"); 
                $('.modal-backdrop').remove();
            },
            "blur:textarea": function(){ 
                //console.log("when the source view gets blurred"); 
            }
            /*
            "load": function(){ 
                console.log("when the editor is fully loaded"); 
            },
            "beforeload": function(){ 
                console.log("for internal use only"); 
            },
            "focus": function(){ 
                console.log("when the editor (regardless if rich text or source view) receives focus"); 
            },
            "focus:composer": function(){ 
                console.log("when the rich text view receives focus"); 
            },
            "focus:textarea": function(){ 
                console.log("when the source view receives focus"); 
            },
            "change": function(){ 
                console.log("when the value changed (regardless if rich text or source view)"); 
            },
            "change:composer": function(){ 
                console.log("when the value of the rich text view got changed"); 
            },
            "change:textarea": function(){ 
                console.log("when the value of the source view got changed"); 
            },
            "paste": function(){ 
                console.log("when the user pastes or drops content (regardless if rich text or source view)"); 
            },
            "paste:composer": function(){ 
                console.log("when the user pastes or drops content into the rich text view"); 
            },
            "paste:textarea": function(){ 
                console.log("when the user pastes or drops content into the source view"); 
            },
            "newword:composer": function(){ 
                console.log("when the user wrote a new word in the rich text view"); 
            },
            "destroy:composer": function(){ 
                console.log("when the rich text view gets removed"); 
            },
            "change_view": function(){ 
                console.log("when switched between source and rich text view"); 
            },
            "show:dialog": function(){ 
                console.log("when a toolbar dialog is shown"); 
            },
            "save:dialog": function(){ 
                console.log("when save in a toolbar dialog is clicked"); 
            },
            "cancel:dialog": function(){ 
                console.log("when cancel in a toolbar dialog is clicked"); 
            },
            "undo:composer": function(){ 
                console.log("when the user invokes an undo action (CMD + Z or via context menu)"); 
            },
            "redo:composer": function(){ 
                console.log("when the user invokes a redo action (CMD + Y, CMD + SHIFT + Z or via context menu)"); 
            },
            "beforecommand:composer": function(){ 
                console.log("when the user is about to format something via a rich text command"); 
            },
            "aftercommand:composer": function(){ 
                console.log("when the user formatted something via a rich text command");
            },
            */
        },
        parserRules: {
            tags: {
                a: {},
                b: {},
                i: {},
                p: {},
                strong: {},
                button: {},
                span: {},
                div: {},
                form: {},
                style: {},
                script: {},
                header: {},
                footer: {},
                section: {},
                link: {},
                nav: {},
                h1: {},
                h2: {},
                h3: {},
                h4: {},
                h5: {},
                h6: {},
                ol: {},
                ul: {},
                li: {},
                br: {
                    "add_class": {
                        "clear": "clear_br"
                    }
                }
                /*
                div: {
                    "add_class": {
                        "align": "align_text"
                    }
                },
               "tr": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "strike": {
                    "remove": 1
                },
                "form": {
                    "rename_tag": "div"
                },
                "rt": {
                    "rename_tag": "span"
                },
                "code": {},
                "acronym": {
                    "rename_tag": "span"
                },

                "details": {
                    "rename_tag": "div"
                },
                "h4": {
                    "add_class": {
                        "align": "align_text"
                    }
                }
                */
            }
        }
    });

    $('.wysihtml5-sandbox').contents().find('body').on("keydown",function() {
        console.log("Handler for .keypress() called.");
    });

})(jQuery); // End of use strict