<?php

if ( ! function_exists('theme_asset')) {
    function theme_asset($path, $secure = null)
    {
        return app('url')->asset(app('theme')->getUrlPath('assets/'.$path), $secure);
    }
}