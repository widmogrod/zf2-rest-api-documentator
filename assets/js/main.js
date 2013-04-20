;(function($){
    // Select first tab
    $('.nav-tabs li:nth-child(1) a').tab('show');

    // Expand input
    $('.api-param-placeholder').on('keypress', function(){
        var $this = $(this);
        var value = ($this.val()) ? $this.val() : $this.attr('placeholder');
        $this.css('width', ((value.length + 1) * 8) + 'px');
    }).trigger('keydown');

    // Perform request
    $('.api-request-action').on('click', function(){
        var parent = $(this).parents('.api-entrypoint');
        var response = parent.find('.api-response');
        var data = parent.find(':input').serializeArray();

        $.ajax({
            'url': $(this).data('api-url'),
            'type': 'post',
            'data': data,
            'dataType': 'html',
            'success': function(data) {
                parent.find('a[href^=#response-]').tab('show');
                response.html(data);
            },
            'error' : function(data) {
                response.html('Ups... error occur during request.');
            }
        });
    });
})(jQuery);