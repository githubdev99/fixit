<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vehicle extends MY_Controller
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
        $title = 'Kendaraan';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/v_vehicle',
            'get_script' => 'admin/script_vehicle'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
        }
    }
}
