<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item extends MY_Controller
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
        $title = 'Barang';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/item/v_item',
            'get_script' => 'admin/item/script_item'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            if ($this->input->post('submit') == 'add') {
                $response = json_decode(shoot_api([
                    'url' => $this->core['url_api'] . 'item',
                    'method' => 'POST',
                    'header' => [
                        "Content-Type: application/json"
                    ],
                    'data' => json_encode([
                        'vehicle_id' => $this->input->post('vehicle_id'),
                        'vehicle_children_id' => $this->input->post('vehicle_children_id'),
                        'name' => $this->input->post('name'),
                        'price' => $this->input->post('price'),
                        'stock' => $this->input->post('stock')
                    ])
                ]), true);

                if ($response['status']['code'] == 201) {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data barang ' . $response['data']['name'] . ' berhasil di simpan'
                    ];
                } else {
                    if ($response['status']['code'] == 409) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data barang ' . $response['data']['name'] . ' sudah ada'
                        ];
                    } else {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data barang gagal di simpan'
                        ];
                    }
                }
            } elseif ($this->input->post('submit') == 'edit') {
                $response = json_decode(shoot_api([
                    'url' => $this->core['url_api'] . 'item/' . $this->input->post('id'),
                    'method' => 'PUT',
                    'header' => [
                        "Content-Type: application/json"
                    ],
                    'data' => json_encode([
                        'vehicle_id' => $this->input->post('vehicle_id'),
                        'vehicle_children_id' => $this->input->post('vehicle_children_id'),
                        'name' => $this->input->post('name'),
                        'price' => $this->input->post('price'),
                        'stock' => $this->input->post('stock')
                    ])
                ]), true);

                if ($response['status']['code'] == 200) {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data barang ' . $response['data']['name'] . ' berhasil di edit'
                    ];
                } else {
                    if ($response['status']['code'] == 404) {
                        $output = [
                            'error' => true,
                            'type' => 'warning',
                            'message' => 'Data barang tidak ditemukan'
                        ];
                    } elseif ($response['status']['code'] == 409) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data barang ' . $response['data']['name'] . ' sudah ada'
                        ];
                    } else {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data barang gagal di edit'
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

    public function detail($id = null)
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'item/' . $id,
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $title = 'Detail Barang';
            $data = [
                'core' => $this->core($title),
                'get_view' => 'admin/item/v_detail_item',
                'get_script' => 'admin/item/script_detail_item',
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

            redirect(base_url() . 'admin/item', 'refresh');
        }
    }
}
