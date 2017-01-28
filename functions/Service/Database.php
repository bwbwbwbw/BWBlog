<?php

namespace BWBlog\Service;

class Database
{

    public static function initMongoDB()
    {

        global $db;

        try {
            $mc = new \MongoDB\Client(MONGO_CONNECTION);
            $db = $mc->selectDatabase(MONGO_DB);
        } catch (Exception $e) {
            die('Database connection error.');
        }

    }

}
