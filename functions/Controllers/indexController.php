<?php

namespace BWBlog\Controllers;

class indexController
{

    public static function indexAction()
    {

        return \BWBlog\Controllers\pageController::initialize([
            'action' => 1
        ]);

    }

}