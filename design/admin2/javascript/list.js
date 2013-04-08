jQuery(document).ready(function(){
    
    jQuery('a.formmaker_remove_form').click(function() {
        var location = $(this).attr('href');
        jQuery('.form-ajax-loader').show();
        $.ez( 'formmaker::getFormConnectedObjects', {'form_id' :  $(this).parent().find('input[name=form-id]').val()}, function( data ) {
            if ( data.error_text ) {
                alert(data.error_text);
            }
            else {
                if (confirm( jQuery("#dialog-confirm").html())){
                    window.location.href = location;
                }
            }
            jQuery('.form-ajax-loader').hide();
        });
        return false;
    });
    
    // Enabling tablesorter
    if (jQuery('.tablesorter tbody td').length > 1) {
        jQuery('.tablesorter').tablesorter({
            sortList: [[1,1]],
            headers: {
                3: {
                    sorter: false
                }
            },
            widgets: ['zebra'],
            widgetZebra: {
                css: ["odd", "even"]
            }
        });
    }
    
    // List page "create" button
    jQuery('input[name=CreateButton]').click(function(){
        window.location = $('#edit-url').val();
    });    
})