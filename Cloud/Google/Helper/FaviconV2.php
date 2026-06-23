<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Cloud\Google\Helper;

class FaviconV2
{
    /**
     * Get icon from URL
     *
     * @param string $url
     * @return string
     */
    public static function getIconFromURL(string $url): string
    {
        $parts = parse_url($url);
        $new = $parts['scheme'] . '://' . $parts['host'];
        return 'https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=' . $new . '&size=16';
    }
}
