<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mechanic extends MY_Controller
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
        $title = 'Mekanik';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/mechanic/v_mechanic',
            'get_script' => 'admin/mechanic/script_mechanic'
        ];

        $this->master->template($data);
    }

    public function detail($id = null)
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'mechanic/' . $id,
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $title = 'Detail Mekanik';
            $data = [
                'core' => $this->core($title),
                'get_view' => 'admin/mechanic/v_detail_mechanic',
                'get_script' => 'admin/mechanic/script_detail_mechanic',
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

            redirect(base_url() . 'admin/mechanic', 'refresh');
        }
    }

    public function form($id = null)
    {
        if (empty($id)) {
            $title = 'Tambah Mekanik';
            $data = [
                'core' => $this->core($title),
                'get_view' => 'admin/mechanic/v_add_mechanic',
                'get_script' => 'admin/mechanic/script_add_mechanic'
            ];

            if (!$this->input->post()) {
                $this->master->template($data);
            } else {
                if ($this->input->post('submit') == 'add') {
                    $response = json_decode(shoot_api([
                        'url' => $this->core['url_api'] . 'mechanic',
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
                            'callback' => base_url() . 'admin/mechanic'
                        ];

                        $this->alert_popup([
                            'name' => 'show_alert',
                            'swal' => [
                                'title' => 'Data mekanik ' . $response['data']['username'] . ' berhasil di simpan',
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
                                'message' => 'Data mekanik gagal di simpan'
                            ];
                        }
                    }
                }

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
            }
        } else {
            $response = json_decode(shoot_api([
                'url' => $this->core['url_api'] . 'mechanic/' . $id,
                'method' => 'get'
            ]), true);

            if ($response['status']['code'] == 200) {
                $title = 'Edit Mekanik';
                $data = [
                    'core' => $this->core($title),
                    'get_view' => 'admin/mechanic/v_edit_mechanic',
                    'get_script' => 'admin/mechanic/script_edit_mechanic',
                    'get_data' => $response['data']
                ];

                if (!$this->input->post()) {
                    $this->master->template($data);
                } else {
                    if ($this->input->post('submit') == 'edit') {
                        $response = json_decode(shoot_api([
                            'url' => $this->core['url_api'] . 'mechanic/' . $id,
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
                                'callback' => base_url() . 'admin/mechanic'
                            ];

                            $this->alert_popup([
                                'name' => 'show_alert',
                                'swal' => [
                                    'title' => 'Data mekanik ' . $response['data']['username'] . ' berhasil di edit',
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
                                    'message' => 'Data mekanik gagal di edit'
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

                redirect(base_url() . 'admin/mechanic', 'refresh');
            }
        }
    }
}
