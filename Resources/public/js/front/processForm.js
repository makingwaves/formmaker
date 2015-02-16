$(document).ready(function() {

    $('.formmaker').on('click', '.next, .previous', function() {

        var postPath = $(this).data('post-path'),
            container = $(this).parents('.formmaker'),
            inputValue = '';

        var formElements = container.find('input[type=text], textarea, input[type=radio]:checked, input[type=checkbox], select'),
            formData = [];

        $.each(formElements, function(index, value){

            switch( $(value).attr('type') ) {

                case 'radio':
                    inputValue = $(value).data('value');
                    break;

                case 'checkbox':
                    inputValue = $(value).is(':checked') === true ? 1 : 0;
                    break;

                default:
                    inputValue = $(value).val();
                    break;
            }

            formData.push({
                id: $(value).attr('name'),
                value: inputValue
            });
        });

        $.ajax({
            url: postPath,
            type: 'POST',
            data: {
                formData: formData
            },
            success: function( data ) {

                container.html(data);
                window.scrollTo(0, container.offset().top);
            }
        })
    });
});
