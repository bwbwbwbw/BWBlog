(function(window)
{

    function renderPreview()
    {
        var markdown = editor.getValue();
        var html = marked(markdown);
        $('.rendered-markdown').html(html);
    }

    function initEditor()
    {
        var editor = window.editor = ace.edit('entry-markdown');
        var session = editor.getSession();
        editor.setTheme('ace/theme/tomorrow');
        editor.setOptions({
            fontSize: '16px',
            showGutter: false
        });
        session.setMode('ace/mode/markdown');
        session.setUseWrapMode(true);
        session.setWrapLimitRange();
        session.on('change', _.debounce(renderPreview, 300));
        session.on('changeScrollTop', function(scroll) {
            scroll = parseInt(scroll) || 0;
            var editorHeight = editor.renderer.layerConfig.maxHeight - editor.renderer.layerConfig.height;/* - editor.renderer.$size.scrollerHeight + editor.renderer.scrollMargin.bottom*/;
            var previewHeight = $('.rendered-markdown').outerHeight(true) - $('.entry-preview').height();
            var ratio = scroll / editorHeight;
            $('.entry-preview-content').scrollTop(previewHeight * ratio);
        });

        renderPreview();
    }

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

        initEditor();

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