jQuery(document).ready(function(){

    // Enabling tablesorter
    if (jQuery('.tablesorter tbody td').length > 1) {
        jQuery('.tablesorter').tablesorter({
            headers: {
                0: { sorter: false },
                1: { sorter: false },
                2: { sorter: false }
            },
            widgets: ['zebra'],
            widgetZebra: {
                css: ["odd", "even"]
            }
        });
    }

    // process a form_id as a view parameter
    jQuery('#formmaker-set-answers-form').click(function(){
        var form = $(this).parent('form');
        var addr = form.attr('action');
        var form_id = form.find('option:selected').val();

        if (form_id > 0) {
            addr += '/(form_id)/' + form_id;
        }

        window.location.href = addr;
    });
});