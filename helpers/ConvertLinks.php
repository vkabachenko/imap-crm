<?php


namespace app\helpers;


class ConvertLinks
{
    public static function convert($text)
    {
        $convertedText = preg_replace (
            "/(?<!a href=\")(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i",
            "<a href=\"\\0\" target=\"blank\">\\0</a>",
            $text
        );

        return $convertedText;
    }

}