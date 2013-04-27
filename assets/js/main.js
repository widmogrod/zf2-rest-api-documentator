;(function(){
    var Formater  = {
        "auto": function(val) {
            var result = val;

            val.charAt(0) == '{' && (result = this.json(val));
            val.charAt(0) == '[' && (result = this.json(val));

            return result;
        },
        "json": function (val) {
            var retval = '';
            var str = val;
            var pos = 0;
            var strLen = str.length;
            var indentStr = '&nbsp;&nbsp;&nbsp;&nbsp;';
            var newLine = '<br />';
            var char = '';

            for (var i=0; i<strLen; i++) {
                char = str.substring(i,i+1);

                if (char == '}' || char == ']') {
                    retval = retval + newLine;
                    pos = pos - 1;

                    for (var j=0; j<pos; j++) {
                        retval = retval + indentStr;
                    }
                }

                retval = retval + char;

                if (char == '{' || char == '[' || char == ',') {
                    retval = retval + newLine;

                    if (char == '{' || char == '[') {
                        pos = pos + 1;
                    }

                    for (var k=0; k<pos; k++) {
                        retval = retval + indentStr;
                    }
                }
            }

            return retval;
        }
    };

    this.Formater = Formater;
}).call(this);
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
                response.html(Formater.auto(data));
            },
            'error' : function(data) {
                response.html('Ups... error occur during request.');
            }
        });
    });
})(jQuery);