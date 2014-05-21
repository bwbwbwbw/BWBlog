(function(window)
{

    $(document).ready(function() {

        $('.role-logout').click(function() {

            jQuery.ajax({

                url:  CONFIG.Prefix + '/bwblog/logout',
                type: 'POST',
                dataType: 'json',

                success: function(d) {
                    window.location.href = CONFIG.Prefix + '/';
                },
                error:   function(d) {
                    alert(d.responseJSON.error.message);
                }

            });

        });

    });

})(window);