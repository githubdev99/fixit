<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vehicle extends MY_Controller
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
        $title = 'Kendaraan';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/vehicle/v_vehicle',
            'get_script' => 'admin/vehicle/script_vehicle'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            if ($this->input->post('submit') == 'add') {
                $response = json_decode(shoot_api([
                    'url' => $this->core['url_api'] . 'vehicle',
                    'method' => 'POST',
                    'header' => [
                        "Content-Type: application/json"
                    ],
                    'data' => json_encode([
                        'name' => $this->input->post('name')
                    ])
                ]), true);

                if ($response['status']['code'] == 201) {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data kendaraan ' . $response['data']['name'] . ' berhasil di simpan'
                    ];
                } else {
                    if ($response['status']['code'] == 409) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data kendaraan ' . $response['data']['name'] . ' sudah ada'
                        ];
                    } else {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data kendaraan gagal di simpan'
                        ];
                    }
                }
            } elseif ($this->input->post('submit') == 'edit') {
                $response = json_decode(shoot_api([
                    'url' => $this->core['url_api'] . 'vehicle/' . $this->input->post('id'),
                    'method' => 'PUT',
                    'header' => [
                        "Content-Type: application/json"
                    ],
                    'data' => json_encode([
                        'name' => $this->input->post('name'),
                        'in_active' => $this->input->post('in_active')
                    ])
                ]), true);

                if ($response['status']['code'] == 200) {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data kendaraan ' . $response['data']['name'] . ' berhasil di edit'
                    ];
                } else {
                    if ($response['status']['code'] == 404) {
                        $output = [
                            'error' => true,
                            'type' => 'warning',
                            'message' => 'Data kendaraan tidak ditemukan'
                        ];
                    } elseif ($response['status']['code'] == 409) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data kendaraan ' . $response['data']['name'] . ' sudah ada'
                        ];
                    } else {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data kendaraan gagal di edit'
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
            'url' => $this->core['url_api'] . 'vehicle/' . $id,
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $title = 'Detail Kendaraan';
            $data = [
                'core' => $this->core($title),
                'get_view' => 'admin/vehicle/v_detail_vehicle',
                'get_script' => 'admin/vehicle/script_detail_vehicle',
                'get_data' => $response['data']
            ];

            if (!$this->input->post()) {
                $this->master->template($data);
            } else {
                if ($this->input->post('submit') == 'add_child') {
                    $response = json_decode(shoot_api([
                        'url' => $this->core['url_api'] . 'vehicle/children?from_parent=' . $id,
                        'method' => 'POST',
                        'header' => [
                            "Content-Type: application/json"
                        ],
                        'data' => json_encode([
                            'name' => $this->input->post('name')
                        ])
                    ]), true);

                    if ($response['status']['code'] == 201) {
                        $output = [
                            'error' => false,
                            'type' => 'success',
                            'message' => 'Data kendaraan ' . $response['data']['name'] . ' berhasil di simpan'
                        ];
                    } else {
                        if ($response['status']['code'] == 409) {
                            $output = [
                                'error' => true,
                                'type' => 'error',
                                'message' => 'Data kendaraan ' . $response['data']['name'] . ' sudah ada'
                            ];
                        } else {
                            $output = [
                                'error' => true,
                                'type' => 'error',
                                'message' => 'Data kendaraan gagal di simpan'
                            ];
                        }
                    }
                } elseif ($this->input->post('submit') == 'edit_child') {
                    $response = json_decode(shoot_api([
                        'url' => $this->core['url_api'] . 'vehicle/children/' . $this->input->post('id'),
                        'method' => 'PUT',
                        'header' => [
                            "Content-Type: application/json"
                        ],
                        'data' => json_encode([
                            'name' => $this->input->post('name'),
                            'in_active' => $this->input->post('in_active')
                        ])
                    ]), true);

                    if ($response['status']['code'] == 200) {
                        $output = [
                            'error' => false,
                            'type' => 'success',
                            'message' => 'Data kendaraan ' . $response['data']['name'] . ' berhasil di edit'
                        ];
                    } else {
                        if ($response['status']['code'] == 404) {
                            $output = [
                                'error' => true,
                                'type' => 'warning',
                                'message' => 'Data kendaraan tidak ditemukan'
                            ];
                        } elseif ($response['status']['code'] == 409) {
                            $output = [
                                'error' => true,
                                'type' => 'error',
                                'message' => 'Data kendaraan ' . $response['data']['name'] . ' sudah ada'
                            ];
                        } else {
                            $output = [
                                'error' => true,
                                'type' => 'error',
                                'message' => 'Data kendaraan gagal di edit'
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

            redirect(base_url() . 'admin/vehicle', 'refresh');
        }
    }

    public function option()
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
                $selected = (decrypt_text($this->input->post('id')) == decrypt_text($key['id'])) ? 'selected' : '';

                $output['html'] .= '
                <option value="' . $key['id'] . '" ' . $selected . '>' . $key['name'] . '</option>
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

    public function option_children($id = null)
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
                $selected = (decrypt_text($this->input->post('id')) == decrypt_text($key['id'])) ? 'selected' : '';

                $output['html'] .= '
                <option value="' . $key['id'] . '" ' . $selected . '>' . $key['name'] . '</option>
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
}
