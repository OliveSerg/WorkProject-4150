<?php

namespace general;

class View {
    public static function renderView($view, $params = []) {
        $viewContent = View::getContent($view, $params);
        ob_start();
        include_once WebApp::$ROOT_DIR . "/templates/main.phtml";
        $layoutContent = ob_get_clean();
        return  str_replace('{{__bodyContent__}}', $viewContent, $layoutContent);
    }

    private static function getContent($view, $params) {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once WebApp::$ROOT_DIR . "/templates/$view.phtml";
        return ob_get_clean();
    }
}
