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
});