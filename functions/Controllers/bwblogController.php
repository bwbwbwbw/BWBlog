<?php

namespace BWBlog\Controllers;

class bwblogController
{

    public static function initialize($param)
    {

        session_start();

        if (!isset($_SESSION['logined']) && $param['action'] !== 'login') {

            header('Location: '.P_PREFIX.'/bwblog/login');
            exit();

        }

        define('TEMPLATE_NATIVE', true);

    }

    public static function indexAction()
    {

        $posts = \BWBlog\Post::_list_all();

        return [
            'TITLE' => 'Dashboard',
            'POSTS' => $posts
        ];

    }

    public static function viewAction($id)
    {
        
        $post = \BWBlog\Post::getById($id);

        return [
            'POST' => $post
        ];

    }

    public static function writeAction($type)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($type == 'publish') {
                $state = \BWBlog\Post::STATE_OPEN;
            } else if ($type == 'draft') {
                $state = \BWBlog\Post::STATE_DRAFT;
            } else if ($type == 'page') {
                $state = \BWBlog\Post::STATE_PAGE;
            } else {
                throw new \Exception('Invalid publish type (publish / draft)');
            }

            if (!isset($_POST['title']) || mb_strlen($_POST['title']) == 0) {
                throw new \Exception('Please enter title');
            }

            if (!isset($_POST['url']) || mb_strlen($_POST['url']) == 0) {
                throw new \Exception('Please enter clean-URI');
            }

            if (!isset($_POST['markdown'])) {
                throw new \Exception('Missing argument: markdown');
            }

            if (!isset($_POST['category'])) {
                throw new \Exception('Missing argument: category');
            }

            if (!isset($_POST['tags'])) {
                throw new \Exception('Missing argument: tags');
            }

            if (!isset($_POST['time'])) {
                throw new \Exception('Missing argument: time');
            }

            $time = \DateTime::createFromFormat('Y-n-j H:i:s', $_POST['time']);
            if ($time === false) {
                throw new \Exception('Invalid date-time format');
            }

            $time = $time->getTimestamp();
            if (mb_strlen(trim($_POST['tags'])) > 0) {
                $tags = array_map('trim', explode(',', $_POST['tags']));
            } else {
                $tags = [];
            }

            if (isset($_POST['id'])) {
                $id = $_POST['id'];
            } else {
                $id = null;
            }

            die(json_encode(\BWBlog\Post::edit(
                $_POST['title'],
                $_POST['markdown'],
                $_POST['url'],
                $time,
                $_POST['category'],
                $tags,
                $state,
                $id
            )));

        } else {

            $id = $type;
            $post = \BWBlog\Post::getById($id);

            if ($post !== null) {
                
                return [
                    'POST'  => $post,
                    'TITLE' => 'Edit'
                ];

            } else {

                return [
                    'TITLE' => 'Write'
                ];

            }

        }

    }

    public static function logoutAction()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_SESSION = [];
            session_destroy();
            die('[]');

        } else {

            header('Location: '.P_PREFIX.'/bwblog');
            exit();

        }

    }

    public static function loginAction()
    {
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['username']) && isset($_POST['password'])) {

                // 2 times are hashed at client side

                $hash = $_POST['password'];
                for ($i = 0; $i < 10; ++$i) {
                    $hash = sha1($hash);
                }

                if ($_POST['username'] === ADMIN_USER && $hash === ADMIN_PASS) {

                    $_SESSION['logined'] = true;

                    header('Location: '.P_PREFIX.'/bwblog');
                    exit();

                }

            }

        }

        return [
            'TITLE' => 'Login'
        ];

    }

}