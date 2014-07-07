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
        $pconfig = \HTMLPurifier_Config::createDefault();
        $pconfig->set('Cache.SerializerPath', ROOT_DIR.'/runtime/htmlpurifier_cache/');
        $pconfig->set('Core.Encoding', 'UTF-8');
        $pconfig->set('AutoFormat.AutoParagraph', true);
        $pconfig->set('AutoFormat.RemoveEmpty', true);
        $pconfig->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $pconfig->set('HTML.AllowedComments', 'more');
        $pconfig->set('HTML.Allowed', '*[style|title|class],font[color|face],p,span,div,center,h1[id],h2[id],h3[id],h4[id],br,sub,sup,blockquote[cite],cite,q[cite],ol,ul,li,b,strong,strike,i,em,a[href|id],pre[lang],code,img[src|alt]');
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

}