<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends MY_Controller
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
        $title = 'Pengaturan Admin';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/v_setting',
            'get_script' => 'admin/script_setting'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
        }
    }
}
