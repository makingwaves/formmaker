jQuery(document).ready( function() {

    jQuery('.removeField:visible').live('click', function() {
        if (confirm($('#dialog-confirm').html())){
            var obj = $(this).parent();
            obj.hide(500, function(){
                obj.remove();
            })
        }
    });

    // setting the default checkbox value, works for all checkbox arrouded by <span> which contains also a hidden field
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
});