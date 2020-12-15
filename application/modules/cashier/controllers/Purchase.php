<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase extends MY_Controller
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
        $title = 'Pembelian Supplier';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'cashier/purchase/v_purchase',
            'get_script' => 'cashier/purchase/script_purchase'
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
                'get_view' => 'cashier/purchase/v_detail_purchase',
                'get_script' => 'cashier/purchase/script_detail_purchase',
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

            redirect(base_url() . 'cashier/purchase', 'refresh');
        }
    }

    public function form()
    {
        $title = 'Tambah Pembelian Supplier';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'cashier/purchase/v_add_purchase',
            'get_script' => 'cashier/purchase/script_add_purchase'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            if ($this->input->post('submit') == 'add') {
                $response = json_decode(shoot_api([
                    'url' => $this->core['url_api'] . 'purchase',
                    'method' => 'POST',
                    'header' => [
                        "Content-Type: application/json"
                    ],
                    'data' => json_encode([
                        'supplier_name' => $this->input->post('supplier_name'),
                        'item_data' => json_decode($this->input->post('item_data'), true)
                    ])
                ]), true);

                if ($response['status']['code'] == 201) {
                    $output = [
                        'error' => false,
                        'type' => 'info',
                        'message' => 'Data sedang di simpan, mohon tunggu...',
                        'callback' => base_url() . 'cashier/purchase'
                    ];

                    $this->alert_popup([
                        'name' => 'show_alert',
                        'swal' => [
                            'title' => 'Data pembelian supplier ' . $response['data']['supplier_name'] . ' berhasil di simpan',
                            'type' => 'success'
                        ]
                    ]);
                } else {
                    if ($response['status']['code'] == 404) {
                        $output = [
                            'error' => true,
                            'type' => 'warning',
                            'message' => 'Data tidak ditemukan'
                        ];
                    } else {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data pembelian supplier gagal di simpan' . var_dump($response)
                        ];
                    }
                }
            } else {
                $output = [
                    'error' => true,
                    'type' => 'error',
                    'message' => 'Ada kesalahan teknis.'
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }
}
