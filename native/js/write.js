(function(window)
{

    function getPostData()
    {
        var doc = {
            title:    $('.role-title').val(),
            url:      $('.role-url').val(),
            markdown: window.editor.getValue(),
            html:     document.getElementsByClassName('rendered-markdown')[0].innerHTML,
            category: $('.role-category').val(),
            tags:     $('.role-tags').val(),
            time:     $('.role-time').val()
        };

        if (typeof POST_id != 'undefined') {
            doc.id = POST_id.$id;   //edit
        }

        return doc;
    }

    $(document).ready(function() {

        ['publish', 'save-draft', 'save-page'].forEach(function(method) {
            
            $('.role-' + method).click(function() {

                jQuery.ajax({

                    url:  CONFIG.Prefix + '/bwblog/write/' + method.replace('save-', ''),
                    type: 'POST',
                    data: getPostData(),
                    dataType: 'json',

                    success: function(d) {
                        window.location.href = CONFIG.Prefix + '/bwblog';
                    },
                    error:   function(d) {
                        alert(d.responseJSON.error.message);
                    }

                });

            });

        });

    });

})(window);