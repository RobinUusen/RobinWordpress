(function( $ ) {
	'use strict';
    // languages form
    // fills the fields based on the language dropdown list choice
    jQuery( document ).ready(function( $ ) {
        $( '#language_list' ).on ('change',function() {
            var value = $( this ).val().split( ':' );
            var selected = $( "option:selected", this ).text().split( ' - ' );
            $( '#language_slug' ).val( value[0] );
            $( '#language_locale' ).val( value[1] );
            $( 'input[name="rtl"]' ).val( [value[2]] );
            $( '#language_name' ).val( selected[0] );
            $( '#flag_list option[value="' + value[3] + '"]' ).attr( 'selected', 'selected' );

        });
    });


})( jQuery );

function copyToTranslation(value,action) {
    try {
        if (document.getElementById('edit-translation')
            || document.getElementById('edit-term-translation')
            || document.getElementById('edit-string-translation')
            || document.getElementById('edit-option-translation')) {
            $local_doc =
            innerHTML="";
            if (action=="copy") {
                srcEl = document.getElementById("original_value_"+value);
                innerHTML = srcEl.innerHTML;
            }
            if (action=="translate") {
                srcEl = document.getElementById("original_value_"+value);
                innerHTML = translateService(srcEl.innerHTML);
            }

            srcEl = document.getElementById(value);
            if (srcEl != null) {
                srcEl.value = innerHTML.trim();
                //srcEl.select();
            }

        }
    }
    catch(e){
        console.log('error in copyToTranslation');
        console.log(e);
    }
}

//add delete ajax action for post,menu,term
jQuery( document ).ready(function($) {
    jQuery( ".ajax-delete-action" ).on( "click", function() {

        var result = confirm('You are about to permanently delete this translation. Are you sure?');
        if (result != true){return false;}

        var ajaxurl = $(this).attr('href');

        params = null;

        var jqxhr = jQuery.post(ajaxurl, params,'json' )
            .success(function (response) {

                if (response.success) {
                    //display toast message
                    alert(response.message);
                } else {
                    //display toast error
                    //TODO display error for user
                    //var logMsg = '<div id="message" class="updated" style="display:block !important;"><p>' +
                    //    'Error during options save' +
                    //    '</p></div>';
                    //jQuery('#ajax-response').append( logMsg );
                    console.log("response", response);

                }

            })
            .error(function (e, xhr, error) {
                console.log("error", xhr, error);
                console.log(e.responseText);
                console.log("ajaxurl", ajaxurl);
                //console.log("params", params);
            });
        return false;

    });
});

jQuery( document ).ready(function($) {

    // Attach behaviour to toggle button.
    jQuery(document).on('click', '#toogle-source-panel', function()
    {
        var referenceHide = this.getAttribute('data-hide-reference');
        var referenceShow = this.getAttribute('data-show-reference');

        if ($(this).text() === referenceHide)
        {
            $(this).text(referenceShow);
        }
        else
        {
            $(this).text(referenceHide);
        }

        $('.col-source').toggle();
        $('.col-action').toggle();
        $('.col-target').toggleClass('full-width');

        return false;
    });
});