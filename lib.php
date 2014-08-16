<?php
/**
 * http://snipplr.com/view/20067/nl2p--alternative-to-nl2br/
 * Returns string with newline formatting converted into HTML paragraphs.
 *
 * @author Michael Tomasello <miketomasello@gmail.com>
 * @copyright Copyright (c) 2007, Michael Tomasello
 * @license http://www.opensource.org/licenses/bsd-license.html BSD License
 * 
 * @param string $string String to be formatted.
 * @param boolean $line_breaks When true, single-line line-breaks will be converted to HTML break tags.
 * @param boolean $xml When true, an XML self-closing tag will be applied to break tags (<br />).
 * @return string
 */
function nl2p($string, $line_breaks = false, $xml = false)
{
    // Remove existing HTML formatting to avoid double-wrapping things
    $string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);
 
    // It is conceivable that people might still want single line-breaks
    // without breaking into a new paragraph.
    if ($line_breaks == true)
        return '<p>'.preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '<br'.($xml == true ? ' /' : '').'>'), trim($string)).'</p>';
    else 
        return '<p>'.preg_replace("/([\n]{1,})/i", "</p>\n<p>", trim($string)).'</p>';
}



/**
 * http://eloux.com/a-better-alternative-to-php-nl2br/
 * @param string $string String to be formatted.
 * @return string
 */
function convert ($text,$begin='<p>',$midle='</p><p>',$end='</p>') {
$text = trim($text);
//return '<p contenteditable="true" >' . preg_replace('/[\r\n]+/', '</p><p contenteditable="true">', $text) . '</p>';
return $begin . preg_replace('/[\r\n]+/', $midle, $text) . $end;

}


function convertx($text) {
$text = trim($text);
return '<p contenteditable="true" >' . preg_replace('/[\r\n]+/', '</p><p contenteditable="true">', $text) . '</p>';
}
?>