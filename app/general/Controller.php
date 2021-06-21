<?php

namespace general;

use \general\View;

abstract class Controller {
    public function render($view, $params = []) {
        return View::renderView($view, $params);
    }
}
