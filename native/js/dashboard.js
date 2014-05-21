(function(window)
{

    $(document).ready(function() {

        $(document).on('click', '.index-right-content a', function(event) {
            $(this).attr('target', '_blank');
        });

        $('.post-item').click(function() {

            $('.post-item-active').removeClass('post-item-active');
            $(this).addClass('post-item-active');

            var post_id = $(this).attr('data-id');

            jQuery.ajax({

                url:  CONFIG.Prefix + '/bwblog/view/' + post_id,
                type: 'GET',
                dataType: 'text',

                success: function(d) {
                    $('.index-right-content').empty().append(d);
                    $('.index-action a').attr('href', CONFIG.Prefix + '/bwblog/write/' + post_id);
                    $('.index-action').show();
                },
                error:   function(d) {
                    alert(d.responseJSON.error.message);
                }

            })

        });

    });

})(window);