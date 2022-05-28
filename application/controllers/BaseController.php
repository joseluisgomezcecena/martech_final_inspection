<?php

class BaseController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function is_logged_in()
    {
        $logged = $this->session->userdata(IS_LOGGED_IN);
        if (isset($logged) && $logged == TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function is_production()
    {
        return $this->session->userdata(USER_TYPE) == PRODUCTION_USER;
    }

    public function is_quality()
    {
        return $this->session->userdata(USER_TYPE) == QUALITY_USER;
    }
}
