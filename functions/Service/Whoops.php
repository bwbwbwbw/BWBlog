<?php

namespace BWBlog\Service;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;

class Whoops
{
    public static function initialize()
    {

        $run = new Run;

        if (\BWBlog\Utils::isAjax()) {
            $handler = new JsonResponseHandler;
        } else {
            $handler = new PrettyPageHandler;
        }

        $run->pushHandler($handler);
        $run->register();

    }
}
