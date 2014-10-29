<?php

namespace BWBlog\Controllers;

class tagController
{

    public static function initialize($param)
    {

        $tag = strtolower($param['action']);
        $page = (int)($param['parameter'] ?: '1');
        $pages = ceil(\BWBlog\Post::count(['ltags' => $tag]) / P_POSTS_PER_PAGE);
        $posts = \BWBlog\Post::list_all(['ltags' => $tag], $page);

        \BWBlog\Service\Template::render('pages/list', [
            'POSTS'      => $posts,
            'PAGE'       => $page,
            'PAGE_COUNT' => $pages,
            'TAG'        => $tag,
            'TITLE'      => ucwords($tag),
            'BASE_URI'   => '/tag/'.$param['action'].'/',
        ]);

        return false;

    }

}