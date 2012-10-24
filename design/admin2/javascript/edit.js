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
              height:140,
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

    jQuery('input[type="checkbox"]').live('click', function() {
        
        if( $(this).next().next().val() == '0' ) {
            $(this).next().next().val('on');
        }
        else {
            $(this).next().next().val('0');
        }
        
    });

    jQuery('select[name="types[]"]').live('change', function() {
        
        var type = '#tpl_' + $(this).val();
        
        var attributeData = {};

        attributeData.id = $(this).parent().find('input[name="ids[]"]').val();
        attributeData.label = $(this).parent().find('input[name="labels[]"]').val();
        attributeData.mandatory = $(this).parent().find('input[name="mandatories[]"]').val();
        attributeData.defaultValue = $(this).parent().find('input[name="placeholders[]"]').val();
        attributeData.cssClass = $(this).parent().find('input[name="css_classes[]"]').val();
        
        var pattern = jQuery(type).clone();
        pattern.removeAttr('id');
        pattern.addClass('formField');
        pattern.children().removeAttr('disabled');
        pattern.removeClass('formFieldTemplate');
        
        pattern.find('input[name="ids[]"]').val( attributeData.id );
        pattern.find('input[name="labels[]"]').val( attributeData.label );
        pattern.find('input[name="mandatories[]"]').val( attributeData.mandatory );
        pattern.find('input[name="placeholders[]"]').val( attributeData.defaultValue );
        pattern.find('input[name="css_classes[]"]').val( attributeData.cssClass );

        $(this).parent().replaceWith( pattern );
        
    });

    jQuery( ".left form .sortable-attributes" ).sortable();
    
    
    
});