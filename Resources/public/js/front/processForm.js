$(document).ready(function() {

    $('.formmaker').on('click', '.next, .previous', function() {

        var postPath = $(this).data('post-path');
        $.ajax({
            url: postPath,
            type: 'POST',
            data: {

            },
            success: function( data ) {
                $('.formmaker').html(data);
            }
        })
    } )
});
