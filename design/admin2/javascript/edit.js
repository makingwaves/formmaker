jQuery(document).ready( function() {
    
    jQuery('.addField').live('click', function() {
        
        if( jQuery('#noneAttributesWarning').is(':visible') ) {
            jQuery('#noneAttributesWarning').hide();
        }
        
        var pattern = jQuery('#tpl_text').clone();
        pattern.removeAttr('id');
        pattern.addClass('formField');
        pattern.children().removeAttr('disabled');
        pattern.removeClass('formFieldTemplate');

        jQuery('#tpl_text').before( pattern );
        
    });

    jQuery('.removeField:visible').live('click', function() {
          
        var toBeRemoved = $(this).parent();

          jQuery( "#dialog-confirm" ).dialog({
              resizable: false,
              height:150,
              modal: true,
              buttons: {
                  Cancel: function() {
                        jQuery( this ).dialog( "close" );
                  },
                  "Delete": function() {
                        toBeRemoved.remove();
                        jQuery( this ).dialog( "close" );
                      
                        if( $('.formField').size() == 0 ) {
                            jQuery('#noneAttributesWarning').show();
                        }
                      
                  }
              }
          });
        
    });

    jQuery('.formField input[type=checkbox]').live('click', function() {
        
        if ($(this).is(':checked')) {
            $(this).parent('span').find('input[type=hidden]').val('on');
        } else {
            $(this).parent('span').find('input[type=hidden]').val(0);
        }
    });

    jQuery( ".left form .sortable-attributes" ).sortable();
    
    // Adding new field
    jQuery('input[name=add_field]').click(function(){
        jQuery('div#page').css('cursor', 'progress');
        var post_data = {'input_id' :  $('select[name=new_field_type]').val()};
        $.ez( 'formmaker::addField', post_data, function( data ) {
            
            if (data.error_text) {
                alert(data.error_text);
            }
            else {
                $('.sortable-attributes').append(data.content);
            }
            
            jQuery('div#page').css('cursor', 'default');
        });           
    })
    
});