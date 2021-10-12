<?php

namespace Util;

class RoutesUtil
{
    public static function getRoutes()
    {
        $urls = self::getUrls();

        $request = [];
        $request['route'] = strtoupper($urls[0]);
        $request['resource'] = $urls[1] ?? null;
        $request['id'] = filter_var($urls[2], FILTER_SANITIZE_NUMBER_INT)  ?? null;
        $request['method'] = $_SERVER['REQUEST_METHOD'];

        return $request;
    }

    public static function getUrls()
    {
        $uri = str_replace('/' . DIR_PROJECT, '', $_SERVER['REQUEST_URI']);
        return explode('/', trim($uri, '/'));
    }
}
