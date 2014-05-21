<?php

namespace BWBlog;

class View
{

    public static function view_static($res, $static = false)
    {

        if ($static) {
            $file = '/static'.$res;
        } else {
            if (defined('TEMPLATE_NATIVE')) {
                $file = '/native'.$res;
            } else {
                $file = '/themes/'.P_THEME.$res;
            }
        }

        $output = P_PREFIX.$file;

        $fp = ROOT_DIR.$file;

        if (file_exists($fp)) {
            $mtime = filemtime($fp);
        } else {
            $mtime = '0';
        }

        $output .= '?v='.$mtime;

        return $output;
    }

    public static function view_processTime()
    {

        $elapsed = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

        return sprintf('%f', $elapsed * 1000);
        
    }

    public static function view_getCatalogs()
    {

        return \BWBlog\Category::list_all();

    }

    public static function view_getTags()
    {

        return \BWBlog\Tag::list_all();

    }
    
}