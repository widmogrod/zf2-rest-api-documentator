;(function(){
    var Formater  = {
        "auto": function(value) {
            var result = value = new String(value);

            value.charAt(0) == '{' && (result = this.json(value));
            value.charAt(0) == '[' && (result = this.json(value));

            return result;
        },
        "json": function (value) {
            var value = new String(value);
            var result = '';
            var str = value;
            var pos = 0;
            var strLen = str.length;
            var indentStr = '&nbsp;&nbsp;&nbsp;&nbsp;';
            var newLine = '<br />';
            var char = '';

            for (var i=0; i<strLen; i++) {
                char = str.substring(i,i+1);

                if (char == '}' || char == ']') {
                    result = result + newLine;
                    pos = pos - 1;

                    for (var j=0; j<pos; j++) {
                        result = result + indentStr;
                    }
                }

                result = result + char;

                if (char == '{' || char == '[' || char == ',') {
                    result = result + newLine;

                    if (char == '{' || char == '[') {
                        pos = pos + 1;
                    }

                    for (var k=0; k<pos; k++) {
                        result = result + indentStr;
                    }
                }
            }

            return result;
        }
    };

    this.Formater = Formater;
}).call(this);

;(function($){

    // Select first tab
    $('.nav-tabs li:nth-child(1) a').tab('show');

    // Expand input
    $('.api-method').on('click', function() {
        var parent = $(this).parents('.api-entrypoint');
        var documentation = parent.find('.api-entrypoint-documentation');

        documentation.toggleClass('show');
    });

    // Perform request
    $('.api-request-action').on('click', function(){
        var parent = $(this).parents('.api-entrypoint');
        var documentation = parent.find('.api-entrypoint-documentation');
        var data = parent.find(':input').serializeArray();
        var items = ['requestUri', 'requestHeaders', 'requestBody', 'responseHeaders', 'responseBody'];

        $.ajax({
            'url': $(this).data('api-url'),
            'type': 'post',
            'data': data,
            'dataType': 'json',
            'success': function(data) {
                parent.find('a[href^=#response-]').tab('show');
                $.each(items, function(k, item){
                    parent.find('.api-response-' + item).html(Formater.auto(JSON.stringify(data[item])))
                });
            },
            'error' : function() {
                $.each(items, function(k, item){
                    parent.find('.api-response-' + item).html('Ups... error occurred during request.');
                });
            },
            'complete': function() {
                documentation.addClass('show');
            }
        });
    });
})(jQuery);