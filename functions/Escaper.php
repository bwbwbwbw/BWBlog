<?php

namespace BWBlog;

use \Ciconia\Ciconia;
use \Ciconia\Extension\Gfm;

class Escaper
{

    private static $purifier = null;

    /**
     * Escape HTML
     *
     * @param $content
     *
     * @return string
     */
    public static function html($content)
    {

        return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

    }

    /**
     * Escape HTML attribute
     *
     * @param $content
     *
     * @return string
     */
    public static function htmlAttr($content)
    {

        return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

    }

    /**
     * Escape URI
     *
     * @param $content
     *
     * @return string
     */
    public static function uri($content)
    {

        return url_encode($content);

    }

    /**
     * Build HTTP query
     *
     * @param $query
     *
     * @return string
     */
    public static function uriQuery($query)
    {

        return http_build_query($query, '', '&');

    }

    /**
     * Safely generate JSON string
     * Avoid XSS like this:
     * <script>var a = <?php echo json_encode('</script><script>alert(1)//'); ?>;</script>
     *
     * @param $obj
     *
     * @return string
     */
    public static function json($obj)
    {

        return json_encode($obj, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

    }

    /*
     * Init HTMLPurifier object
     */
    private static function _initPurifier()
    {
        if (defined('HTMLPURIFIER_ALLOW_CLASSES'))
            $all = '*[style|title|class]';
        else
            $all = '*[style|title]';

        $pconfig = \HTMLPurifier_Config::createDefault();
        $pconfig->set('Cache.SerializerPath', ROOT_DIR.'/runtime/htmlpurifier_cache/');
        $pconfig->set('Core.Encoding', 'UTF-8');
        $pconfig->set('AutoFormat.AutoParagraph', true);
        $pconfig->set('AutoFormat.RemoveEmpty', true);
        $pconfig->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $pconfig->set('HTML.Allowed', $all.',font[color|face],p,span,div,center,h3,h4,br,sub,sup,blockquote[cite],cite,q[cite],ol,ul,li,b,strong,strike,i,em,a[href],pre[lang]');
        $pconfig->set('CSS.AllowedProperties', 'font-family,font-style,font-weight,color,background-color,text-decoration,text-align,list-style-type');

        self::$purifier = new \HTMLPurifier($pconfig);
    }

    /**
     * Purify HTML
     *
     * @param $html
     *
     * @return mixed
     */
    public static function purify($html)
    {

        if (self::$purifier == null) {
            self::_initPurifier();
        }

        return self::$purifier->purify((string)$html);

    }

    private static function _fix($html)
    {
        $pBegin = 0;

        //fix: <pre><code>中<>被两次escape

        while (false !== $pBegin = stripos($html, '<code>', $pBegin)) {
            $pEnd = strpos($html, '</code>', $pBegin + 6);
            if ($pEnd === false) break;

            $inner = substr($html, $pBegin + 6, $pEnd - $pBegin - 6);
            $inner = str_replace('&amp;', '&', $inner);

            $html = substr_replace($html, $inner, $pBegin + 6, $pEnd - $pBegin - 6);

            $pBegin += strlen($inner) + 6;
        }

        return $html;
    }

    public static function markdown($content, $purify = true)
    {
        static $ciconia = null;

        if ($ciconia == null) {
            $ciconia = new Ciconia();
            $ciconia->addExtension(new Gfm\FencedCodeBlockExtension());
            $ciconia->addExtension(new Gfm\InlineStyleExtension());
            $ciconia->addExtension(new Gfm\WhiteSpaceExtension());
            $ciconia->addExtension(new Gfm\UrlAutoLinkExtension());
        }
        
        if ($purify) {
            $c = self::purify(self::_fix($ciconia->render(self::html($content))));
        } else {
            $c = $ciconia->render($content);
        }

        return $c;
    }

}