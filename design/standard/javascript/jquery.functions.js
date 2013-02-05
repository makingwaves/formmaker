
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

    jQuery('input[type=hidden][class=validation-type]').each(function(){
        if ($.inArray($('#date-validator').val(), $(this).val().split(',')) != -1) {
            var input = $(this).parent().find('input[type=text]');
            input.attr('autocomplete', 'off');
            input.datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: $('#form-datepicker-format').val(),
                onClose: function(){
                    $(this).blur();
                },
                yearRange: '-150:nnnn'
            });            
        }
    });
    
    jQuery('select.date-year-validation').select2();

    /* lilleborg specific */
    jQuery('.category_selection').change( function() {
        var select = jQuery(this).find('select');
        var selectedVal = select.val()
        var chosenLabel = select.find('option[value='+selectedVal+']').text();
        chosenLabel = chosenLabel.replace(/\(/g, '');
        chosenLabel = chosenLabel.replace(/\)/g, '');
        chosenLabel = chosenLabel.replace(/ø/g, 'o');
        chosenLabel = chosenLabel.replace(/å/g, 'a');
        chosenLabel = chosenLabel.replace(/ /g, '_');
        var options = jQuery('.'+chosenLabel).children().children().clone();
        // console.log(jQuery('.'+choosenLabel));
        // console.log(select.children());
        jQuery('.product_selection').children().children().remove();
        if( options )
        {
            jQuery('.product_selection').children().append( options );
        }
        else
        {
            jQuery('.product_selection').children().html('');   
        }
        
    } );

    // jQuery('.hidden').find('select').attr('disabled', 'disabled');
    // console.log(jQuery('.hidden').find('select'));
    jQuery('.hidden').parent().parent().hide();
    /* /lilleborg specific */

})

