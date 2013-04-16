(function($){
    $('.api-method').on('click', function(){
        $(this).siblings('.toggable').toggleClass('show');
    });
    $('.test-request').on('click', function(){
        var parent = $(this).parents('.entrypoint');
        var res = parent.find('.response');

        $.ajax({
            'url': $(this).data('href'),
            'dataType': 'html',
            'success': function(data) {
                parent.find('.toggable').toggleClass('show');
                parent.find('.toggable-response').toggleClass('show');
                res.val(data);
            },
            'error' : function(data) {
                parent.find('.toggable').toggleClass('show');
                parent.find('.toggable-response').toggleClass('show');
                res.val(data);
            }
        });

    });
})(jQuery);