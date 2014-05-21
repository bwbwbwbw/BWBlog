<?php

namespace BWBlog\Controllers;

class feedController
{

    public static function indexAction()
    {

        $rss = new \UniversalFeedCreator();
        $rss->useCached('RSS1.0', ROOT_DIR.'/runtime/rss.xml');
        $rss->title = P_TITLE;
        
        $rss->link = ENV_PREFERED_PROTOCOL.'://'.CONFIG_HOST.P_PREFIX;
        $rss->syndicationURL = $rss->link.'/feed';

        $posts = \BWBlog\Post::list_all([], 1, 0);
        foreach ($posts as $post) {
            $item = new \FeedItem();
            $item->title = $post['title'];
            $item->link = ENV_PREFERED_PROTOCOL.'://'.CONFIG_HOST.P_PREFIX.$post['rest_url'];
            $item->description = $post['content']['html'];
            $item->date = $post['time']['main'];
            $rss->addItem($item);
        }

        echo $rss->saveFeed('RSS1.0', ROOT_DIR.'/runtime/rss.xml');

    }

}