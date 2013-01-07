jQuery(document).ready( function() {

    // remove form attribute
    jQuery('.formField .remove-field').live('click', function() {
        if (confirm($('#dialog-confirm').html())){
            var obj = $(this).parents('.formField');
            obj.hide(500, function(){
                obj.remove();
            })
        }
    });

    // setting the default checkbox value for mandatory checkbox
    jQuery('.attribute-mandatory-holder input[type=checkbox]').live('click', function() {
        var value = 0;
        if ($(this).is(':checked')) {
            value = 'on';
        } 
        $(this).parent('span').find('input[type=hidden]').val(value);
    });
    
    // setting the default checkbox value for email receiver checkbox
    jQuery('.email-receiver-inputs input[type=checkbox]').live('click', function() {
        var value = 0;
        if ($(this).is(':checked')) {
            value = 1;
        } 
        $(this).parent('span').find('input[type=hidden]').val(value);
    });    

    jQuery( ".sortable-attributes" ).sortable();
    
    // Adding new field
    jQuery('input[name=add_field]').click(function(){
        jQuery('div#page').css('cursor', 'progress');
        var post_data = {'input_id' :  $('select[name=new-field-type]').val()};
        $.ez( 'formmaker::addField', post_data, function( data ) {
            
            if (data.error_text) {
                alert(data.error_text);
            }
            else {
                $('.sortable-attributes').append(data.content);
            }
            
            jQuery('div#page').css('cursor', 'default');
        });           
    });
    
    // Adding new option line to attribute
    jQuery('.formField .add-option').live('click', function(){
        jQuery('div#page').css('cursor', 'progress');
        var post_data = {'attribute_id' :  $(this).parents('.formField').find('.attribute-unique-id').val()};
        var object = $(this);
        
        $.ez( 'formmaker::addAttributeOption', post_data, function( data ) {
            
            if (data.error_text) {
                alert(data.error_text);
            }
            else {
                object.parent('.form-attribute-options').find('ul').append(data.content);
            }
            
            jQuery('div#page').css('cursor', 'default');
        });         
    });
    
    // Removing option line from attribute
    jQuery('.form-attribute-options .option-remove').live('click', function(){
        if ($(this).parents('.form-attribute-options ul').find('li').length > 1) {
            if (confirm($('#dialog-confirm').html())){
            
                // remove default value from hidden field if current option is checked
                if ($(this).parent().find('.default-radio input[type=checkbox]').is(':checked')){
                    $(this).parents('.formField').find('input[name="' + $(this).parent().find('.default-radio input[type=checkbox]').attr('connected') + '"]').val('');
                }
                
                var obj = $(this).parent();
                obj.hide(500, function(){
                    obj.remove();
                });
            }
        }
    });
    
    // Moving option line up
    jQuery('.form-attribute-options .option-move-up').live('click', function(){
        var current_index = $($(this).parents('ul').find('.option-move-up')).index(this);
        if (current_index > 0) {
            var current_element = $(this).parents('ul').find('li').get(current_index);
            $(current_element).prev().before(current_element);
        }
    });
    
    // Moving option line down
    jQuery('.form-attribute-options .option-move-down').live('click', function(){
        var current_index = $($(this).parents('ul').find('.option-move-down')).index(this);
        if (current_index+1 < $(this).parents('ul').find('.option-move-down').length) {
            var current_element = $(this).parents('ul').find('li').get(current_index);
            $(current_element).next().after(current_element);
        }
    });  
    
    // setting default value for radio options
    jQuery('.default-radio input[type=checkbox]').live('click', function(){
        
        var hidden_value = '';
        
        if ($(this).is(':checked')) {
            var option_id = $(this).attr('option_id');
            hidden_value = option_id;
            
            // setting the value to hidden field
            $(this).parents('.formField').find('input[name="' + $(this).attr('connected') + '"]').val(option_id);
            
            // unchecking other checkboxes from this group
            $(this).parents('.form-attribute-options').find('input[type=checkbox][option_id!=' + option_id + ']:checked').attr('checked', false);
        } else {
            // for security reasons, unchecking all checkboxes
            $(this).parents('.form-attribute-options').find('input[type=checkbox]').attr('checked', false);
        }
        
        // setting hidden field value
        $(this).parents('.formField').find('input[name="' + $(this).attr('connected') + '"]').val(hidden_value);        
    });
    
    // "enable" checkbox
    jQuery('.enable-attribute input[type=checkbox]').live('click', function(){
        var value = 0;
        var separator_id = $('#separator-id').val();
        if ($(this).is(':checked')) {
            value = 1;
        }
        
        // disabling/enabling whole form page
        if ($(this).parents('.formField').find('input[type=hidden][name$="[type]"]').val() == separator_id) {
            var all_formfields = $('.sortable-attributes .formField');
            var form_field = false;
            var checkbox = false;
            var start_index = all_formfields.index($(this).parents('.formField')) + 1;

            for (var i = start_index; i < all_formfields.length; i++){
                form_field = $(all_formfields[i]);
                if (form_field.find('input[type=hidden][name$="[type]"]').val() == separator_id) {
                    break;
                }
                checkbox = form_field.find('.enable-attribute input[type=checkbox]');
                checkbox.attr('checked', Boolean(value));
                checkbox.parent().find('input[type=hidden]').val(value);
            }
        }
        
        $(this).parent().find('input[type=hidden]').val(value);
    });
    
    // displaying and hiding email receiver for email validation
    jQuery('.attribute-validation').live('change', function(){
        if ($(this).val() == $('#validator-email-id').val()) {
            jQuery('div#page').css('cursor', 'progress');
            var post_data = {'attribute_id' :  $(this).parents('.formField').find('.attribute-unique-id').val()};
            var object = $(this);
            $.ez( 'formmaker::addEmailReceiver', post_data, function( data ) {
                if (data.error_text) {
                    alert(data.error_text);
                }
                else {
                    object.parents('.validation-paragraph').append(data.content);
                }
                jQuery('div#page').css('cursor', 'default');
            });  
        } else {
            $(this).parents('.validation-paragraph').find('.email-receiver-holder').remove();
        }
    });
    
    //jQuery('.email-receiver-inputs input[type=checkbox]')
});