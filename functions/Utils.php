<?php

namespace BWBlog;

class Utils
{

    public static function invoke($rest, $controller, $action, $parameter = null)
    {

        $controller = strtolower($controller);
        $action     = strtolower($action);

        $reflection = new \ReflectionClass('\BWBlog\Controllers\\'.$controller.'Controller');
        
        // call initialize
        if ($reflection->hasMethod('initialize')) {
            $method = $reflection->getMethod('initialize');
            $result = $method->invoke(null, [
                'controller' => $controller,
                'action'     => $action,
                'parameter'  => $parameter,
                'raw'        => $rest
            ]);

            if ($result === false) {
                return false;
            }
        }

        $method = $reflection->getMethod($action.'Action');
        $result = $method->invoke(null, $parameter);
        if ($result !== false) {
            if ($result == null) {
                $result = [];
            };
            \BWBlog\Service\Template::render($controller.'/'.$action, $result);
        }

    }

    public static function trimRestURI($URI)
    {

        return strtolower(trim($URI, "\r\n/\\ "));

    }

    /**
     * 判断是否是Ajax请求
     *
     * @return bool
     */
    public static function isAjax()
    {

        return (
            (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ||
            isset($_POST['ajax']) ||
            isset($_GET['ajax'])
        );

    }

    /**
     * 格式化日期
     *
     * @param $timestamp
     *
     * @return bool|string
     */
    public static function formatDate($timestamp, $format = null)
    {

        if ($timestamp == null) {
            $timestamp = time();
        }

        if ($format == null) {
            $format = CONFIG_DATE_FORMAT;
        }

        return date($format, $timestamp);

    }

    /**
     * 强制闭合HTML标签
     *
     * @param $html
     *
     * @return string
     */
    public static function closeTags($html)
    {
        //From jCore : License(GPL/LGPL/MPL)

        preg_match_all("#<([a-z0-9]+)( .*)?(?!/)>#iU", $html, $result, PREG_OFFSET_CAPTURE);

        if (!isset($result[1]))
            return $html;

        $openedtags = $result[1];
        $len_opened = count($openedtags);

        if (!$len_opened)
            return $html;

        preg_match_all("#</([a-z0-9]+)>#iU", $html, $result, PREG_OFFSET_CAPTURE);
        $closedtags = array();

        foreach ($result[1] as $tag)
            $closedtags[$tag[1]] = $tag[0];

        $openedtags = array_reverse($openedtags);

        for ($i = 0; $i < $len_opened; $i++) {
            if (preg_match('/(img|br|hr)/i', $openedtags[$i][0]))
                continue;

            $found = array_search($openedtags[$i][0], $closedtags);

            if (!$found || $found < $openedtags[$i][1])
                $html .= "</".$openedtags[$i][0].">";

            if ($found)
                unset($closedtags[$found]);
        }

        return $html;
    }

    /**
     * 格式化时间
     *
     * @param $timestamp
     *
     * @return bool|string
     */
    public static function formatTime($timestamp)
    {

        if ($timestamp == null) {
            $timestamp = time();
        }

        return date(CONFIG_TIME_FORMAT, $timestamp);

    }

    /**
     * 格式化日期时间
     *
     * @param $timestamp
     *
     * @return bool|string
     */
    public static function formatDateTime($timestamp)
    {

        if ($timestamp == null) {
            $timestamp = time();
        }

        return date(CONFIG_DATE_FORMAT.' '.CONFIG_TIME_FORMAT, $timestamp);

    }

    public static function formatElapsed($ptime)
    {
        $etime = time() - $ptime;

        if ($etime < 1) {
            return 'just now';
        }

        $a = [
            12 * 30 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        ];

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
            }
        }
    }

}