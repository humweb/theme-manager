<?php

if ( ! function_exists('theme_asset')) {
    function theme_asset($path) {
        return app('theme')->activeThemePath('assets/'.$path);
    }
}