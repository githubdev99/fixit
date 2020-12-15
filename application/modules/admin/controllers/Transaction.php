<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transaction extends MY_Controller
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
        $title = 'Transaksi';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/transaction/v_transaction',
            'get_script' => 'admin/transaction/script_transaction'
        ];

        $this->master->template($data);
    }
}
