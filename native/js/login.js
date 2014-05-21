(function(window)
{
    $(document).ready(function() {

        $('.role-form').submit(function() {

            var pass = $('.role-password').val();
            $('.role-password').val(Sha1.hash(Sha1.hash(pass)));

        });

    });

})(window);