jQuery(document).ready(function(){
    
    jQuery('a.formmaker_remove_form').click(function(event) {
          
        // wtf
        event.preventDefault();
        var toBeRemoved = jQuery(this).attr('href');
          
        jQuery( "#dialog-confirm" ).dialog({
            resizable: false,
            height:140,
            modal: true,
            buttons: {
                Cancel: function() {
                    jQuery( this ).dialog( "close" );
                },
                "Delete": function() {
                    //jQuery( this ).dialog( "close" );
                    window.location = toBeRemoved;
                    return true;
                }
            }
        });
        
    })
})