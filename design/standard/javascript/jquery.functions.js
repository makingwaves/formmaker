
/**
 * Set of general functions used in Form Maker
 */
jQuery(document).ready(function(){
    
    // Required for checkbox processing
    jQuery('.form_label_checkbox input[type=checkbox]').click(function(){
        var value = $(this).attr('checked') ? 'on' : '';
        $('#' + $(this).attr('connected')).val(value);
    });       
    
    // Security variable
    jQuery('input[name=form-send], input[name=form-back], input[name=form-next]').click(function(){
        $('#mwezform input[name=validation]').val('False');
    });
    
    // Required for radio-buttons processing
    jQuery('.form_label_radio input[type=radio]').click(function(){
        var input_id = $(this).attr('name').replace('connected_', '');
        $(this).parents('.form_attribute_content').find('input[name=' + input_id + ']').val($(this).val());
    });    
})

