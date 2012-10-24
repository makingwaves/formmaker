
/**
 * Set of general functions used in MWeZForms
 */
jQuery(document).ready(function(){
    
    // Required for checkbox processing
    jQuery('.mwform_label_checkbox input[type=checkbox]').click(function(){
        var value = $(this).attr('checked') ? 'on' : '';
        $('#' + $(this).attr('connected')).val(value);
    })       
    
    // Security variable
    jQuery('#mwezform-submit').click(function(){
        $('#mwezform input[name=validation]').val('False');
    })
})

