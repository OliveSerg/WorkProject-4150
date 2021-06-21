<?php

namespace general;

class Request {
    public function getMethod() {
        return strtoLower($_SERVER['REQUEST_METHOD']);
    }

    public function getUrl() {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return $path;
    }

    public function getBody() {
        $body = [];
        $data = [$_POST, INPUT_POST];
        if ($this->getMethod() == 'get') {
            $data = [$_GET, INPUT_GET];
        }
        foreach ($data[0] as $key => $value) {
            $body[$key] = filter_input($data[1], $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return $body;
    }
}
