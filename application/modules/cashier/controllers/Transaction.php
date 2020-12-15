<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Transaction extends MY_Controller
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
        $title = 'Transaksi';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'cashier/transaction/v_transaction',
            'get_script' => 'cashier/transaction/script_transaction'
        ];

        $this->master->template($data);
    }

    public function form()
    {
        $title = 'Tambah Transaksi';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'cashier/transaction/v_add_transaction',
            'get_script' => 'cashier/transaction/script_add_transaction'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            if ($this->input->post('submit') == 'add') {
                $response = json_decode(shoot_api([
                    'url' => $this->core['url_api'] . 'transaction',
                    'method' => 'POST',
                    'header' => [
                        "Content-Type: application/json"
                    ],
                    'data' => json_encode([
                        'customer_name' => $this->input->post('customer_name'),
                        'cashier_id' => $this->session->userdata('cashier'),
                        'vehicle_data' => [
                            'id' => $this->input->post('vehicle_id'),
                            'children_id' => $this->input->post('vehicle_children_id')
                        ],
                        'service_data' => $this->input->post('service_data'),
                        'item_data' => json_decode($this->input->post('item_data'), true)
                    ])
                ]), true);

                if ($response['status']['code'] == 201) {
                    $output = [
                        'error' => false,
                        'type' => 'info',
                        'message' => 'Data sedang di simpan, mohon tunggu...',
                        'callback' => base_url() . 'cashier/transaction'
                    ];

                    $this->alert_popup([
                        'name' => 'show_alert',
                        'swal' => [
                            'title' => 'Data transaksi ' . $response['data']['customer_name'] . ' berhasil di simpan',
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
                            'message' => 'Data transaksi gagal di simpan' . var_dump($response)
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

    public function option_service()
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'service',
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $output = [
                'error' => false,
                'html' => '<option></option>'
            ];

            foreach ($response['data'] as $key) {
                $output['html'] .= '
                <option value="' . $key['id'] . '">' . $key['name'] . ' (' . $key['price_currency_format'] . ')</option>
                ';
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

    public function option_item()
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'item',
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $output = [
                'error' => false,
                'html' => '<option></option>'
            ];

            foreach ($response['data'] as $key) {
                if ($key['in_active'] == true) {
                    $output['html'] .= '
                    <option value="' . $key['id'] . '">' . $key['name'] . ' (Stok : ' . $key['stock'] . ')</option>
                    ';
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

    public function option_vehicle()
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'vehicle',
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $output = [
                'error' => false,
                'html' => '<option></option>'
            ];

            foreach ($response['data'] as $key) {
                if ($key['in_active'] == true) {
                    $selected = (decrypt_text($this->input->post('id')) == decrypt_text($key['id'])) ? 'selected' : '';

                    $output['html'] .= '
                    <option value="' . $key['id'] . '" ' . $selected . '>' . $key['name'] . '</option>
                    ';
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

    public function option_vehicle_children($id = null)
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'vehicle/children?from_parent=' . $id,
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $output = [
                'error' => false,
                'html' => '<option></option>'
            ];

            foreach ($response['data'] as $key) {
                if ($key['in_active'] == true) {
                    $selected = (decrypt_text($this->input->post('id')) == decrypt_text($key['id'])) ? 'selected' : '';

                    $output['html'] .= '
                    <option value="' . $key['id'] . '" ' . $selected . '>' . $key['name'] . '</option>
                    ';
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
