<?php

namespace App\Helpers\Guides;

use App\Models\Guide;

class GuideHelper
{
    public static function sanitizeContent(string $content)
    {
        return strip_tags($content);
    }
}
