<?php

namespace BWBlog\Controllers;

class pageController
{

    public static function initialize($param)
    {

        if (is_numeric($param['action'])) {

            // page/1

            $page = (int)$param['action'];
            $pages = ceil(\BWBlog\Post::count() / P_POSTS_PER_PAGE);
            $posts = \BWBlog\Post::list_all([], $page);

            \BWBlog\Service\Template::render('pages/list', [
                'CATEGORY'   => 'home',
                'POSTS'      => $posts,
                'PAGE'       => $page,
                'PAGE_COUNT' => $pages,
                'BASE_URI'   => '/page/',
            ]);

        } else {

            // page/custom_page

            $post = \BWBlog\Post::get($param['raw']);

            if ($post == null) {
                throw new \ReflectionException();
                return false;
            }

            \BWBlog\Service\Template::render('pages/page', [
                'TITLE' => $post['title'],
                'POST'  => $post
            ]);

        }


        return false;
    }

}
