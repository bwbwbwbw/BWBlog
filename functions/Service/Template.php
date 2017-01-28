<?php

namespace BWBlog\Service;

class Template
{

    private static $_twig = null;

    private static function setVars($arr)
    {

        foreach ($arr as $k => $v) {
            self::$_twig->addGlobal($k, $v);
        }

    }

    public static function initialize()
    {

        if (defined('TEMPLATE_NATIVE')) {
            $loader = new \Twig_Loader_Filesystem(ROOT_DIR.'/native/tpl');
        } else {
            $loader = new \Twig_Loader_Filesystem(ROOT_DIR.'/themes/'.P_THEME.'/tpl');
        }

        self::$_twig = new \Twig_Environment($loader, [
            'cache'         => ROOT_DIR.'/runtime/twig_cache',
            'debug'         => true,
            'charset'       => 'UTF-8',
            'autoescape'    => 'html_attr',
        ]);

        self::$_twig->addFunction(new \Twig_SimpleFunction('view_static', '\BWBlog\View::view_static'));
        self::$_twig->addFunction(new \Twig_SimpleFunction('view_processTime', '\BWBlog\View::view_processTime'));
        self::$_twig->addFunction(new \Twig_SimpleFunction('get_catalogs', '\BWBlog\View::view_getCatalogs'));
        self::$_twig->addFunction(new \Twig_SimpleFunction('get_tags', '\BWBlog\View::view_getTags'));

        self::$_twig->addFilter(new \Twig_SimpleFilter('json', '\BWBlog\Escaper::json'));
        self::$_twig->addFilter(new \Twig_SimpleFilter('date', '\BWBlog\Utils::formatDate'));
        self::$_twig->addFilter(new \Twig_SimpleFilter('time', '\BWBlog\Utils::formatTime'));
        self::$_twig->addFilter(new \Twig_SimpleFilter('datetime', '\BWBlog\Utils::formatDateTime'));
        self::$_twig->addFilter(new \Twig_SimpleFilter('elapsed', '\BWBlog\Utils::formatElapsed'));

        self::setVars([
            'APP_NAME'      => APP_NAME,
            'APP_VERSION'   => APP_VERSION,
            'CONFIG_DEBUG'  => CONFIG_DEBUG,
            'P_PREFIX'      => P_PREFIX,
            'P_THEME'       => P_THEME,
            'P_TITLE'       => P_TITLE,
            'P_DISQUS_ID'   => P_DISQUS_ID,
            'TITLE_SUFFIX'  => P_TITLE,
            'GET'           => $_GET,
            'POST'          => $_POST,
            'REQUEST_URI'   => $_SERVER['REQUEST_URI']
        ]);

    }

    public static function render($template, $var)
    {

        if (self::$_twig === null) {
            self::initialize();
        }

        echo self::$_twig->render($template.'.twig', $var);

    }

}
