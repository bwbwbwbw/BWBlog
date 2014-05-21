<?php

namespace BWBlog\Controllers;

class errorController
{

    public static function notfoundAction()
    {

        header('HTTP/1.1 404 Not Found');

    }

}