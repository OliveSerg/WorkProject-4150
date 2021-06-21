<?php

namespace general;

class Response {
    public function responseCode($code) {
        http_response_code($code);
    }

    public function redirect($url) {
        header("Location: $url");
    }
}
