<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase extends MY_Controller
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
        $title = 'Pembelian Supplier';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/purchase/v_purchase',
            'get_script' => 'admin/purchase/script_purchase'
        ];

        $this->master->template($data);
    }

    public function detail($id = null)
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'purchase/' . $id,
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $title = 'Detail Pembelian Supplier';
            $data = [
                'core' => $this->core($title),
                'get_view' => 'admin/purchase/v_detail_purchase',
                'get_script' => 'admin/purchase/script_detail_purchase',
                'get_data' => $response['data']
            ];

            $this->master->template($data);
        } else {
            $this->alert_popup([
                'name' => 'show_alert',
                'swal' => [
                    'title' => 'Ada kesalahan teknis',
                    'type' => 'error'
                ]
            ]);

            redirect(base_url() . 'admin/purchase', 'refresh');
        }
    }
}
