<?php

namespace AppBundle\Lib;

class Helper
{
    /**
     * build api url with given parameters by schema from $this->apiUrl
     *
     * @param array $params [key => val, ...]
     * @return string ready to use copy of url, original schema from $this->apiUrl left intact
     */
    public static function prepareUrl($url, $params)
    {
        if (empty($url) || empty($params)) return $url;
        $params = array_change_key_case($params, CASE_LOWER);
        $parsedUrl = parse_url($url);
        $parsedQuery = [];
        if (!empty($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $parsedQuery);
        }
        foreach ($params as $key => $val) {
            $mark = '{' . $key . '}';
            if (strpos($parsedUrl['path'], $mark) !== false) {
                $parsedUrl['path'] = str_replace($mark, $val, $parsedUrl['path']);
            } else {
                $parsedQuery[$key] = $val;
            }
        }
        array_walk($parsedQuery, function (&$item, $key) {
            $item = $key . '=' . $item;
        });
        $url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'] . '?' . join('&', $parsedQuery);
        return $url;
    }

    /**
     * Builds array of coordinates from given string
     *
     * @param string $locationString lat,lng
     * @return array
     */
    public static function locationToArray($locationString)
    {
        $expl = explode(',', $locationString);
        if (!is_numeric($expl[0])) {
            $expl[0] = 54.348545;
        }
        if (!isset($expl[1]) || !is_numeric($expl[1])) {
            $expl[1] = 18.6532295;
        }
        return ['lat' => $expl[0], 'lng' => $expl[1]];
    }
}

