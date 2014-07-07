<?php

namespace BWBlog;

class Post
{

    const STATE_DRAFT = 0;
    const STATE_OPEN  = 1;
    const STATE_PAGE  = 2;

    public static function create(
        $title, 
        $md, 
        $html, 
        $url,  
        $time       = null,
        $category   = null, 
        $tags       = [], 
        $state      = self::STATE_OPEN
    )
    {

        global $db;
        $collection = $db->selectCollection(MONGO_PREFIX.'posts');

        // ensure index when create posts
        // for viewing post
        $collection->ensureIndex([
            'rest_url' => 1
        ]);

        // for listing posts
        $collection->ensureIndex([
            'state'     => 1,
            'time.main' => -1
        ]);

        // for category
        $collection->ensureIndex([
            'lcategory' => 1,
            'state'     => 1,
            'time.main' => -1
        ]);

        // for tags
        $collection->ensureIndex([
            'ltags'     => 1,
            'state'     => 1,
            'time.main' => -1
        ]);

        return self::edit($title, $md, $html, $url, $time, $category, $tags, $state);

    }

    public static function edit(
        $title, 
        $md, 
        $html, 
        $url,  
        $time       = null,
        $category   = null, 
        $tags       = [], 
        $state      = self::STATE_OPEN,
        $id         = null
    )
    {

        if ($id !== null && mb_strlen((string)$id) !== 24) {
            throw new \Exception('Invalid argument: id');
        }

        if ($time == null) {
            $time = time();
        }

        if (P_HTML_FILTER) {
            $html = \BWBlog\Escaper::purify($html);
        }
        
        // page doesn't need short introductions
        if ($state != self::STATE_PAGE) {
            
            // process introduction
            foreach (['<!-- more -->', '<!--more -->', '<!-- more-->', '<!--more-->'] as $meta) {
                $pos = mb_stripos($html, $meta, 0, 'UTF-8');
                if ($pos !== false) {
                    break;
                }
            }

            if ($pos !== false) {
                $intro = \BWBlog\Utils::closeTags(mb_substr($html, 0, $pos));
                $more = true;
            } else {
                $intro = $html;
                $more = false;
            }

        } else {
            $more = false;
            $intro = '';
        }

        // page uses direct url
        if ($state != self::STATE_PAGE) {

            $rest_url = \BWBlog\Utils::trimRestURI(str_replace('\\', '/', date('Y/m/d/', $time).$url));

        } else {

            $rest_url = \BWBlog\Utils::trimRestURI(str_replace('\\', '/', 'page/'.$url));

        }
        
        $doc = [
            'title'     => $title,
            'content'   => [
                'markdown'  => $md,
                'html'      => $html,
                'introhtml' => $intro,
                'more'      => $more
            ],
            'time'      => [
                'main'      => $time
            ],
            'url'       => $url,
            'rest_url'  => $rest_url,
            'category'  => $category,
            'lcategory' => strtolower($category),
            'tags'      => $tags,
            'ltags'     => array_map('strtolower', array_map('trim', $tags)),
            'state'     => (int)$state
        ];

        global $db;

        if ($id === null) {

            // create

            $doc['time']['create'] = time();
            $db->selectCollection(MONGO_PREFIX.'posts')->insert($doc);

            return $doc['_id'];
    
        } else {

            // update
            
            $doc['time']['edit'] = time();
            $result = $db->selectCollection(MONGO_PREFIX.'posts')->findAndModify([
                '_id'    => new \MongoId((string)$id)
            ], [
                '$set'   => $doc
            ], [
                '_id'    => 1
            ], [
                'upsert' => true,
                'new'    => true
            ]);

            return $result['_id'];

        }
        
    }
    
    public static function getById($id)
    {

        if (mb_strlen((string)$id) !== 24) {
            return null;
        }

        global $db;
        $result = $db->selectCollection(MONGO_PREFIX.'posts')->findOne([
            '_id' => new \MongoId((string)$id)
        ]);

        return $result;

    }

    public static function get($rest)
    {

        global $db;
        $result = $db->selectCollection(MONGO_PREFIX.'posts')->findOne([
            'rest_url' => (string)$rest
        ]);

        return $result;

    }

    public static function count($condition = [], $show_all = false)
    {

        if ($show_all !== true) {
            $condition['state'] = self::STATE_OPEN;
        }

        global $db;
        $count = $db->selectCollection(MONGO_PREFIX.'posts')->find($condition)->count();

        return $count;

    }

    public static function _list_all($condition = [])
    {

        global $db;
        $cursor = $db->selectCollection(MONGO_PREFIX.'posts')->find($condition, [
            'content'      => 0
        ])->sort([
            'time.main'    => -1
        ]);

        $result = [];

        foreach ($cursor as $post) {
            $result[] = $post;
        }

        return $result;

    }

    public static function list_all($condition = [], $page = 1, $page_size = P_POSTS_PER_PAGE, $show_all = false)
    {

        if ($show_all !== true) {
            $condition['state'] = self::STATE_OPEN;
        }

        $page = (int)$page;

        if ($page < 1) {
            $page = 1;
        }

        global $db;
        $cursor = $db->selectCollection(MONGO_PREFIX.'posts')->find($condition, [
            //'content.html'      => 0,     // for feed purpose
            'content.markdown'  => 0
        ])->sort([
            'time.main'         => -1
        ]);

        if ($page_size > 0) {
            $cursor = $cursor->skip(($page - 1) * $page_size)->limit($page_size);
        }

        $result = [];

        foreach ($cursor as $post) {
            $result[] = $post;
        }

        return $result;

    }

}