<?php

namespace BWBlog\Controllers;

class postController
{

    public static function viewAction($rest)
    {

        $post = \BWBlog\Post::get($rest);
        
        if ($post == null) {
            throw new \ReflectionException();
            return false;
        }

        \BWBlog\Service\Template::render('pages/post', [
            'TITLE' => $post['title'],
            'POST'  => $post
        ]);

        return false;

    }

}