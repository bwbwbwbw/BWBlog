<?php

include __DIR__.'/includes/init.php';

use \BWBlog\Utils;

$url = parse_url($_SERVER['REQUEST_URI']);
$url = $url['path'];

// REST
$rest_part = mb_substr($url, mb_strlen(dirname($_SERVER['SCRIPT_NAME'])));
$rest_part = \BWBlog\Utils::trimRestURI($rest_part);
$rest_arr = explode('/', $rest_part);
$rest_arr = array_map(function($value) {
    if (mb_strlen($value) == 0) {
        return 'index';
    } else {
        return urldecode($value);
    }
}, $rest_arr);

$pc = count($rest_arr);

try
{

    if ($pc === 0) {
        Utils::invoke($rest_part, 'index', 'index');
    } else if ($pc === 1) {
        Utils::invoke($rest_part, $rest_arr[0], 'index');
    } else if ($pc === 2) {
        Utils::invoke($rest_part, $rest_arr[0], $rest_arr[1]);
    } else if ($pc >= 3) {
        Utils::invoke($rest_part, $rest_arr[0], $rest_arr[1], $rest_arr[2]);
    }

} catch (ReflectionException $e) {

    try
    {
        Utils::invoke($rest_part, 'post', 'view', $rest_part);
    } catch (ReflectionException $e) {
        Utils::invoke($rest_part, 'error', 'notfound');
    }

}