<?php

namespace BWBlog;

class Category
{

    public static function list_all($condition = [], $show_all = false)
    {

        if ($show_all !== true) {
            $condition['state'] = \BWBlog\Post::STATE_OPEN;
        }

        global $db;
        $r= $db->selectCollection(MONGO_PREFIX.'posts')->group([
            'category' => 1
        ],[
            'count' => 0
        ],
        'function(obj, prev)
        {
            prev.count++;
        }',[
            'condition' => $condition
        ]);

        $r = $r['retval'];

        $result = [];
        foreach ($r as $catalog) {
            $result[] = [
                'name'  => $catalog['category'],
                'count' => $catalog['count']
            ];
        }

        // order by name asc
        usort($result, function($catalog1, $catalog2) {
            return strcmp($catalog1['name'], $catalog2['name']);
        });

        return $result;

    }

}