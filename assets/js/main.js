(function($){
    $('.api-method').on('click', function(){
        $(this).siblings('.toggable').toggleClass('show');
    });
    $('.test-request').on('click', function(){
        var parent = $(this).parents('.entrypoint');
        var res = parent.find('.response');

        var data = parent.find(':input').serializeArray();

        $.ajax({
            'url': $(this).data('api-url'),
            'type': 'post',
            'data': data,
            'dataType': 'html',
            'success': function(data) {
                parent.find('.toggable').addClass('show');
                parent.find('.toggable-response').addClass('show');
                res.val(data);
            },
            'error' : function(data) {
                parent.find('.toggable').addClass('show');
                parent.find('.toggable-response').addClass('show');
                res.val(data);
            }
        });

    });
})(jQuery);