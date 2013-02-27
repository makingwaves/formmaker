
/**
 * jQuery plugin for fields validation
 */
jQuery(document).ready(function(){
    
    /**
     * Validator object
     */ 
    var Validator = function(object) {
        
        // jQuery object
        var obj = $(object);
        
        /**
         * Public factory of event handlers
         */
        this.init = function(action) {
            eval('obj.' + action + '(function(){validate()})');
        };
        
        /**
         * Private function, validates input data using ezJSCore
         */
        function validate() {
            
            var work_object     = setObject(obj);
            var parts           = work_object.attr('name').split('_');
            var id              = parts[parts.length-1];
            var post_data       = 'id=' + id + '&value=' + work_object.val();   
            var content_element = $('#form_element_' + id + ' .form_notification');

            $.ez( 'formmaker::validate', post_data, function( data ) {
                if (data.content && data.content.length){
                    content_element.html(data.content);
                } else if(data.content) {
                    content_element.html(' ');
                } else {
                    content_element.html('Error during validation process');
                }
            });                           
        };
        
        /**
         * Private function, returns the work object
         */
        function setObject(object) {
            
            var obj = $(object);
            if (obj.attr('type') == 'checkbox') {
                obj = $('#' + $(object).attr('connected'));
            }
            else if (obj.attr('type') == 'radio') {
                var input_id = $(object).attr('name').replace('connected_', '');
                obj = $(object).parents('.form_attribute_content').find('input[name=' + input_id + ']');
            }
            return obj;
        }
    };
    
    // jQuery plugin definition
    jQuery.fn.validator = function(action){
        return this.each(function(){
            var validator = new Validator(this);
            validator.init(action);
        });
    };    
    
    // jQuery plugin init
    jQuery('.validate-it .form_element').validator('blur');
    jQuery('.validate-it input[type=checkbox], .validate-it input[type=radio]').validator('click');
    jQuery('.validate-it select.form-select-attribute').validator('change');
})
