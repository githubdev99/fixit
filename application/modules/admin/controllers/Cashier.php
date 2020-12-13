<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cashier extends MY_Controller
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
        $title = 'Cashier';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/cashier/v_cashier',
            'get_script' => 'admin/cashier/script_cashier'
        ];

        $this->master->template($data);
    }

    public function detail($id = null)
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'cashier/' . $id,
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $title = 'Detail Cashier';
            $data = [
                'core' => $this->core($title),
                'get_view' => 'admin/cashier/v_detail_cashier',
                'get_script' => 'admin/cashier/script_detail_cashier',
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

            redirect(base_url() . 'admin/cashier', 'refresh');
        }
    }

    public function form($id = null)
    {
        if (empty($id)) {
            $title = 'Tambah Cashier';
            $data = [
                'core' => $this->core($title),
                'get_view' => 'admin/cashier/v_add_cashier',
                'get_script' => 'admin/cashier/script_add_cashier'
            ];

            if (!$this->input->post()) {
                $this->master->template($data);
            } else {
                if ($this->input->post('submit') == 'add') {
                    $response = json_decode(shoot_api([
                        'url' => $this->core['url_api'] . 'cashier',
                        'method' => 'POST',
                        'header' => [
                            "Content-Type: application/json"
                        ],
                        'data' => json_encode([
                            'name' => $this->input->post('name'),
                            'birth_date' => $this->input->post('birth_date'),
                            'phone_number' => $this->input->post('phone_number'),
                            'username' => $this->input->post('username'),
                            'gender' => $this->input->post('gender'),
                            'address' => $this->input->post('address')
                        ])
                    ]), true);

                    if ($response['status']['code'] == 201) {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Data sedang di simpan, mohon tunggu...',
                            'callback' => base_url() . 'admin/cashier'
                        ];

                        $this->alert_popup([
                            'name' => 'show_alert',
                            'swal' => [
                                'title' => 'Data kasir ' . $response['data']['username'] . ' berhasil di simpan',
                                'type' => 'success'
                            ]
                        ]);
                    } else {
                        if ($response['status']['code'] == 409) {
                            $output = [
                                'error' => true,
                                'type' => 'error',
                                'message' => 'Username sudah terpakai'
                            ];
                        } elseif ($response['status']['code'] == 404) {
                            $output = [
                                'error' => true,
                                'type' => 'warning',
                                'message' => 'Jenis kelamin tidak ditemukan'
                            ];
                        } else {
                            $output = [
                                'error' => true,
                                'type' => 'error',
                                'message' => 'Data kasir gagal di simpan'
                            ];
                        }
                    }
                }

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
            }
        } else {
            $response = json_decode(shoot_api([
                'url' => $this->core['url_api'] . 'cashier/' . $id,
                'method' => 'get'
            ]), true);

            if ($response['status']['code'] == 200) {
                $title = 'Edit Cashier';
                $data = [
                    'core' => $this->core($title),
                    'get_view' => 'admin/cashier/v_edit_cashier',
                    'get_script' => 'admin/cashier/script_edit_cashier',
                    'get_data' => $response['data']
                ];

                if (!$this->input->post()) {
                    $this->master->template($data);
                } else {
                    if ($this->input->post('submit') == 'edit') {
                        $response = json_decode(shoot_api([
                            'url' => $this->core['url_api'] . 'cashier/' . $id,
                            'method' => 'PUT',
                            'header' => [
                                "Content-Type: application/json"
                            ],
                            'data' => json_encode([
                                'name' => $this->input->post('name'),
                                'birth_date' => $this->input->post('birth_date'),
                                'phone_number' => $this->input->post('phone_number'),
                                'username' => $this->input->post('username'),
                                'gender' => $this->input->post('gender'),
                                'address' => $this->input->post('address')
                            ])
                        ]), true);

                        if ($response['status']['code'] == 200) {
                            $output = [
                                'error' => false,
                                'type' => 'info',
                                'message' => 'Data sedang di edit, mohon tunggu...',
                                'callback' => base_url() . 'admin/cashier'
                            ];

                            $this->alert_popup([
                                'name' => 'show_alert',
                                'swal' => [
                                    'title' => 'Data kasir ' . $response['data']['username'] . ' berhasil di edit',
                                    'type' => 'success'
                                ]
                            ]);
                        } else {
                            if ($response['status']['code'] == 404) {
                                $output = [
                                    'error' => true,
                                    'type' => 'warning',
                                    'message' => 'Jenis kelamin tidak ditemukan'
                                ];
                            } else {
                                $output = [
                                    'error' => true,
                                    'type' => 'error',
                                    'message' => 'Data kasir gagal di edit'
                                ];
                            }
                        }
                    }

                    $this->output->set_content_type('application/json')->set_output(json_encode($output));
                }
            } else {
                $this->alert_popup([
                    'name' => 'show_alert',
                    'swal' => [
                        'title' => 'Ada kesalahan teknis',
                        'type' => 'error'
                    ]
                ]);

                redirect(base_url() . 'admin/cashier', 'refresh');
            }
        }
    }
}
