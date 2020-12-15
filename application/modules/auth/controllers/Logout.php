<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $title = 'Waiting';
        $data = [
            'core' => $this->core($title)
        ];
        $this->master->template($data);

        $this->session->unset_userdata('admin');
        $this->session->unset_userdata('cashier');

        $this->alert_popup([
            'name' => 'show_alert',
            'swal' => [
                'title' => 'Anda berhasil logout!',
                'type' => 'success'
            ]
        ]);
        redirect(base_url() . 'auth/login', 'refresh');
    }
}
