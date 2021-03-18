(function( $ ) {
    document.addEventListener("DOMContentLoaded", function() {
        //defaultLanguage define before
        //falang //define before all languages info , slug , name, home_url
        var currentLanguage;//store locale
        var nextLanguage;//store locale

        // function isGutenbergActive() {
        //     //return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
        //     return document.body.classList.contains( 'block-editor-page' );
        // }

        $( document ).ready(function() {
            currentLanguage = $("#post_locale_choice").val() == 'all'?defaultLanguage:$("#post_locale_choice").val();
            nextLanguage = currentLanguage;
            $("#post_locale_choice").on("change", function(event) {
                nextLanguage = this.value == 'all'?defaultLanguage:this.value;
                //update view page
                // if (isGutenbergActive) {
                //     wp.data.dispatch('core/editor').editPost({});
                // }
                currentLanguage = nextLanguage;
                //show/hide translation link in metabox
                this.value == 'all'?$('#meta-post-translations').show():$('#meta-post-translations').hide();
            } );
        });

        function getCurrentLanguage(){
            return currentLanguage;
        }

        // if (isGutenbergActive){
        //
        //     var getPermalinkParts = wp.data.select("core/editor").getPermalinkParts;
        //
        //     wp.data.select("core/editor").getPermalinkParts = function() {
        //         var parts = getPermalinkParts();
        //             parts.prefix = falang[nextLanguage]['home_url'];
        //         return parts;
        //     }
        // }
    });
})( jQuery );