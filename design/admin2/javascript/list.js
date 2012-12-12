jQuery(document).ready(function(){
    
    jQuery('a.formmaker_remove_form').click(function() {
        if (!confirm( jQuery("#dialog-confirm").html() )){
            return false;
        }
    });
})