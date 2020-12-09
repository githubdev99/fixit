<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth([
            'session' => 'admin',
            'login' => false
        ]);
    }

    public function index()
    {
        $title = 'Admin Dashboard';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'home/v_home',
            'get_script' => 'home/script_home'
        ];
        $this->master->template($data);
    }
}
