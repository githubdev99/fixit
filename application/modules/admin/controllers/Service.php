<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service extends MY_Controller
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
        $title = 'Servis';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/service/v_service',
            'get_script' => 'admin/service/script_service'
        ];

        $this->master->template($data);
    }

    public function detail($id = null)
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'service/' . $id,
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $title = 'Detail Servis';
            $data = [
                'core' => $this->core($title),
                'get_view' => 'admin/service/v_detail_service',
                'get_script' => 'admin/service/script_detail_service',
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

            redirect(base_url() . 'admin/service', 'refresh');
        }
    }

    public function form($id = null)
    {
        if (empty($id)) {
            $title = 'Tambah Servis';
            $data = [
                'core' => $this->core($title),
                'get_view' => 'admin/service/v_add_service',
                'get_script' => 'admin/service/script_add_service'
            ];

            if (!$this->input->post()) {
                $this->master->template($data);
            } else {
                if ($this->input->post('submit') == 'add') {
                    $response = json_decode(shoot_api([
                        'url' => $this->core['url_api'] . 'service',
                        'method' => 'POST',
                        'header' => [
                            "Content-Type: application/json"
                        ],
                        'data' => json_encode([
                            'vehicle_id' => $this->input->post('vehicle_id'),
                            'vehicle_children_id' => $this->input->post('vehicle_children_id'),
                            'name' => $this->input->post('name'),
                            'price' => $this->input->post('price')
                        ])
                    ]), true);

                    if ($response['status']['code'] == 201) {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Data sedang di simpan, mohon tunggu...',
                            'callback' => base_url() . 'admin/service'
                        ];

                        $this->alert_popup([
                            'name' => 'show_alert',
                            'swal' => [
                                'title' => 'Data servis ' . $response['data']['name'] . ' berhasil di simpan',
                                'type' => 'success'
                            ]
                        ]);
                    } else {
                        if ($response['status']['code'] == 409) {
                            $output = [
                                'error' => true,
                                'type' => 'error',
                                'message' => 'Data servis ' . $response['data']['name'] . ' sudah ada'
                            ];
                        } elseif ($response['status']['code'] == 404) {
                            $output = [
                                'error' => true,
                                'type' => 'warning',
                                'message' => 'Data tidak dserviceukan'
                            ];
                        } else {
                            $output = [
                                'error' => true,
                                'type' => 'error',
                                'message' => 'Data servis gagal di simpan'
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
        } else {
            $response = json_decode(shoot_api([
                'url' => $this->core['url_api'] . 'service/' . $id,
                'method' => 'get'
            ]), true);

            if ($response['status']['code'] == 200) {
                $title = 'Edit Servis';
                $data = [
                    'core' => $this->core($title),
                    'get_view' => 'admin/service/v_edit_service',
                    'get_script' => 'admin/service/script_edit_service',
                    'get_data' => $response['data']
                ];

                if (!$this->input->post()) {
                    $this->master->template($data);
                } else {
                    if ($this->input->post('submit') == 'edit') {
                        $response = json_decode(shoot_api([
                            'url' => $this->core['url_api'] . 'service/' . $id,
                            'method' => 'PUT',
                            'header' => [
                                "Content-Type: application/json"
                            ],
                            'data' => json_encode([
                                'vehicle_id' => $this->input->post('vehicle_id'),
                                'vehicle_children_id' => $this->input->post('vehicle_children_id'),
                                'name' => $this->input->post('name'),
                                'price' => $this->input->post('price')
                            ])
                        ]), true);

                        if ($response['status']['code'] == 200) {
                            $output = [
                                'error' => false,
                                'type' => 'info',
                                'message' => 'Data sedang di edit, mohon tunggu...',
                                'callback' => base_url() . 'admin/service'
                            ];

                            $this->alert_popup([
                                'name' => 'show_alert',
                                'swal' => [
                                    'title' => 'Data servis ' . $response['data']['name'] . ' berhasil di edit',
                                    'type' => 'success'
                                ]
                            ]);
                        } else {
                            if ($response['status']['code'] == 409) {
                                $output = [
                                    'error' => true,
                                    'type' => 'error',
                                    'message' => 'Data servis ' . $response['data']['name'] . ' sudah ada'
                                ];
                            } elseif ($response['status']['code'] == 404) {
                                $output = [
                                    'error' => true,
                                    'type' => 'warning',
                                    'message' => 'Data tidak dserviceukan'
                                ];
                            } else {
                                $output = [
                                    'error' => true,
                                    'type' => 'error',
                                    'message' => 'Data servis gagal di edit'
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
            } else {
                $this->alert_popup([
                    'name' => 'show_alert',
                    'swal' => [
                        'title' => 'Ada kesalahan teknis',
                        'type' => 'error'
                    ]
                ]);

                redirect(base_url() . 'admin/service', 'refresh');
            }
        }
    }
}
