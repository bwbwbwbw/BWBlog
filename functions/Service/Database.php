<?php

namespace BWBlog\Service;

class Database
{

    public static function initMongoDB()
    {
        
        global $db;
        
        try {

            $mc = new \MongoClient(MONGO_PATH, [

                'db'               => MONGO_DB,
                'username'         => MONGO_USERNAME,
                'password'         => MONGO_PASSWORD,
                'connectTimeoutMS' => MONGO_TIMEOUT

            ]);

            $db = $mc->selectDB(MONGO_DB);

        } catch (Exception $e) {

            die('Database connection error.');

        }

    }
    
}