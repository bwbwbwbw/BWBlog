(function(window)
{
    function sha1(text) {
        var shaObj = new jsSHA(text, 'TEXT');
        return shaObj.getHash('SHA-1', 'HEX');
    }

    $(document).ready(function() {
        $('.role-form').submit(function() {
            var pass = $('.role-password').val();
            $('.role-password').val(sha1(sha1(pass)));
        });
    });

})(window);