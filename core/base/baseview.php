<?php

namespace App\Core\Base;


class BaseView {



    /**
     * Add to render template data
     * @param $path
     * @param $data
     */
    public function render($path,$data = array())
    {
        if (is_array($data)) {
            extract($data);
        }

        $path = 'views/'.$path.'.php';

        if (is_readable($path))
            require_once $path;
    }
}