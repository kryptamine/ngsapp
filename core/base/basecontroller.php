<?php

namespace App\Core\Base;

abstract class BaseController {

    /**
     * @var BaseView
     */
    protected $view;


    public function __construct() {

        $this->view = new BaseView();
    }


}