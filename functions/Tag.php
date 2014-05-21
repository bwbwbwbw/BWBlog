<?php

namespace BWBlog;

class Tag
{

    public static function list_all($condition = [], $show_all = false)
    {

        if ($show_all !== true) {
            $condition['state'] = \BWBlog\Post::STATE_OPEN;
        }

        global $db;
        $r = $db->selectCollection(MONGO_PREFIX.'posts')->aggregate([
        ['$match' => $condition],
        ['$project' => ['tags' => 1]],
        ['$unwind' => '$tags'],
        ['$group' => [
            '_id'   => '$tags',
            'count' => ['$sum' => 1]
        ]]
        ]);

        $r = $r['result'];

        $result = [];
        foreach ($r as $tag) {
            $result[] = [
                'name'  => $tag['_id'],
                'count' => $tag['count']
            ];
        }

        // order by count desc, name asc
        usort($result, function($tag1, $tag2) {
            $v = $tag2['count'] - $tag1['count'];
            if ($v == 0) {
                $v = strcmp($tag1['name'], $tag2['name']);
            }
            return $v;
        });

        return $result;

    }

}