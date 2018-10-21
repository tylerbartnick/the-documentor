<?php

namespace App\Helpers\Guides;

use App\Models\Guide;

class GuideHelper
{
    public static function sanitizeContent(string $content)
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($content);

        $scriptTags = $dom->getElementsByTagName('script');
        $linkTags   = $dom->getElementsByTagName('link');
        $iframeTags = $dom->getElementsByTagName('iframe');
        $styleTags  = $dom->getElementsByTagName('style');

        foreach ($scriptTags as $tag) {
            $tag->parentNode->removeChild($tag);
        }

        foreach ($linkTags as $tag) {
            $tag->parentNode->removeChild($tag);
        }

        foreach ($iframeTags as $tag) {
            $tag->parentNode->removeChild($tag);
        }

        foreach ($styleTags as $tag) {
            $tag->parentNode->removeChild($tag);
        }

        return $dom->saveHTML();
    }
}
