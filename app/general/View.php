<?php

namespace general;

class View {
    public static function renderView($view, $params = []) {
        $viewContent = View::getContent($view, $params);
        ob_start();
        include_once WebApp::$ROOT_DIR . "/app/templates/main.phtml";
        $layoutContent = ob_get_clean();
        return  str_replace('{{__bodyContent__}}', $viewContent, $layoutContent);
    }

    private static function getContent($view, $params) {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        if ($file = glob(WebApp::$ROOT_DIR . "/app/templates/$view.{phtml,html}", GLOB_BRACE)) {
            include_once $file[0];
        } else {
            echo "File $view Does not exist";
        }
        return ob_get_clean();
    }
}
