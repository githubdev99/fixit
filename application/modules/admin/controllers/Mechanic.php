<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mechanic extends MY_Controller
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
        $title = 'Mechanic';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/v_mechanic',
            'get_script' => 'admin/script_mechanic'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
        }
    }
}
