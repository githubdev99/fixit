<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth([
            'session' => 'cashier',
            'login' => false
        ]);
    }

    public function index()
    {
        $title = 'Kasir Dashboard';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'cashier/dashboard/v_dashboard',
            'get_script' => 'cashier/dashboard/script_dashboard'
        ];

        $this->master->template($data);
    }
}
