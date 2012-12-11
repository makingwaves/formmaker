
/**
 * Set of general functions used in Form Maker
 */
jQuery(document).ready(function(){
    
    // Required for checkbox processing
    jQuery('.form_label_checkbox input[type=checkbox]').click(function(){
        var value = $(this).attr('checked') ? 'on' : '';
        $('#' + $(this).attr('connected')).val(value);
    })       
    
    // Security variable
    jQuery('#mwezform-submit').click(function(){
        $('#mwezform input[name=validation]').val('False');
    })
})

