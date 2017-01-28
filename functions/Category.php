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
        $cursor = $db->selectCollection(MONGO_PREFIX.'posts')->aggregate([
            ['$match' => $condition],
            ['$group' => ['_id' => '$category', 'count' => ['$sum' => 1]]],
        ]);

        $result = [];
        foreach ($cursor as $doc) {
            $result[] = [
                'name'  => $doc['_id'],
                'count' => $doc['count']
            ];
        }

        // order by name asc
        usort($result, function($catalog1, $catalog2) {
            return strcmp($catalog1['name'], $catalog2['name']);
        });

        return $result;

    }

}
