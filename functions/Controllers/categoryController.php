<?php

namespace BWBlog\Controllers;

class categoryController
{

    public static function initialize($param)
    {

        $category = strtolower($param['action']);
        $page = (int)($param['parameter'] || '1');
        $pages = ceil(\BWBlog\Post::count(['lcategory' => $category]) / P_POSTS_PER_PAGE);
        $posts = \BWBlog\Post::list_all(['lcategory' => $category], $pages);

        \BWBlog\Service\Template::render('pages/list', [
            'POSTS'      => $posts,
            'PAGE'       => $page,
            'PAGE_COUNT' => $pages,
            'CATEGORY'   => $category,
            'TITLE'      => ucwords($category),
            'BASE_URI'   => '/category/'.$param['action'].'/',
        ]);

        return false;

    }

}