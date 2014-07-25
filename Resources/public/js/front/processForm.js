$(document).ready(function() {

    $('.formmaker').on('click', '.next, .previous', function() {

        var postPath = $(this).data('post-path'),
            container = $(this).parents('.formmaker');

        var formElements = container.find('input[type=text]'),
            formData = [];

        $.each(formElements, function(index, value){

            formData.push({
                id: $(value).attr('name'),
                value: $(value).val()
            });
        });

        console.log(formData);

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
    } )
});
