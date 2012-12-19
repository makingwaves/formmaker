jQuery(document).ready(function(){
    
    jQuery('a.formmaker_remove_form').click(function() {
        var location = $(this).attr('href');
        jQuery('div#page').css('cursor', 'progress');
        $.ez( 'formmaker::getFormConnectedObjects', {'form_id' :  $(this).parent().find('input[name=form-id]').val()}, function( data ) {
            if ( data.error_text ) {
                alert(data.error_text);
            }
            else {
                if (confirm( jQuery("#dialog-confirm").html())){
                    window.location.href = location;
                }
            }
            jQuery('div#page').css('cursor', 'default');
        });
        return false;
    });
})