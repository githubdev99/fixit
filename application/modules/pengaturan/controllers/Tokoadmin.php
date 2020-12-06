<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tokoadmin extends MY_Controller {

	public function __construct()
	{
        parent::__construct();
        $this->auth_seller();
    }
    
    public function index()
    {
        $title = 'Pengaturan Admin';
        $data = [
			'core' => $this->core($title),
			'plugin' => ['datatables', 'flatpickr', 'magnific-popup', 'select2', 'croppie'],
            'get_view' => 'pengaturan/toko_admin/v_pengaturan_admin',
            'get_script' => 'pengaturan/toko_admin/script_pengaturan_admin'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;
            $post_data = json_decode(json_encode($this->input->post('post_data')), TRUE);
            if ($this->input->post('submit') == 'tambah') {
                $check_data = $this->master_model->select_data([
                    'field' => 'id_customer',
                    'table' => 'toko_admin',
                    'where' => [
                        'id_customer' => $this->input->post('id_customer')
                    ]
                ])->result();

                if (!empty($check_data)) {
                    $checking = FALSE;
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data Admin sudah pernah di simpan.'
                    ];
                }

                if ($checking == TRUE) {
                  
                    $query = $this->master_model->send_data([
                        'data' => [
                            'id_customer' => $this->input->post('id_customer'),
                            'id_toko' => $this->data['seller']->id_toko,
                            'level_admin' => $this->input->post('level_admin'),
                            'nama_lengkap' => $this->input->post('nama_lengkap'),
                            'email' => $this->input->post('email'),
                            'created_date' => date('Y-m-d'),
                            'created_time' => date('H:i:s')                        
                        ],
                        'table' => 'toko_admin'
                    ]);

                    if ($query == FALSE) {
                     

                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data Admin gagal di simpan.',
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Data sedang di simpan, mohon tunggu...',
                            'callback' => base_url() . 'pengaturan/tokoadmin'
                        ];

                        $this->alert_popup([
                            'name' => 'success',
                            'swal' => [
                                'title' => 'Data Admin berhasil di simpan.',
                                'type' => 'success'
                            ]
                        ]);
                    }
                }
            }           
            elseif($this->input->post('submit') == 'delete') {
                $query = $this->master_model->delete_data([
                    'where' => [
                        'id_data' => decrypt_text($this->input->post('id_data'))
                    ],
                    'table' => 'toko_admin'
                ]);

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data Admin gagal di hapus.'
                    ];
                } else {                   

                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data Admin berhasil di hapus.'
                    ];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));

        }
    }



    public function edit($id)
    {
        $get_data = $this->master_model->select_data([
            'field' => '*',
            'table' => 'toko_admin',
            'where' => [
                'id_data' => decrypt_text($id)
            ]
        ])->row();

        // Check URL
        if (empty($get_data)) {
            $this->alert_popup([
                'name' => 'failed',
                'swal' => [
                    'title' => 'Ada kesalahan teknis',
                    'type' => 'error'
                ]
            ]);

            redirect(base_url() . 'pengaturan/tokoadmin', 'refresh');
        }

        $title = 'Edit Admin';
      

        $data = [
			'core' => $this->core($title),
			'plugin' => ['flatpickr', 'select2', 'croppie','datatables', 'flatpickr', 'magnific-popup','checkbox'],
            'get_view' => 'pengaturan/toko_admin/v_edit_admin',
            'get_script' => 'pengaturan/toko_admin/script_pengaturan_admin',
            'get_data' => $get_data
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

            if ($this->input->post('submit') == 'edit') {
                $check_data = $this->master_model->select_data([
                    'field' => '*',
                    'table' => 'toko_admin',
                    'where' => [
                        'id_customer' => $this->input->post('id_customer'),
                        'id_data !=' => decrypt_text($id)
                    ]
                ])->result();

                if (!empty($check_data)) {
                    $checking = FALSE;
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data Admin sudah pernah di simpan.'
                    ];
                }

                if ($checking == TRUE) {            

                    $query = $this->master_model->send_data([
                        'where' => [
                            'id_data' => decrypt_text($id)
                        ],
                        'data' => [
                            'id_customer' => $this->input->post('id_customer'),
                            'id_toko' => $this->data['seller']->id_toko,
                            'level_admin' => $this->input->post('level_admin'),
                            'nama_lengkap' => $this->input->post('nama_lengkap'),
                            'email' => $this->input->post('email'),
                            'created_date' => date('Y-m-d'),
                            'created_time' => date('H:i:s')                        
                        ],
                        'table' => 'toko_admin'
                    ]);

                    if ($query == FALSE) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data Admin gagal di edit.',
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Data sedang di edit, mohon tunggu...',
                            'callback' => base_url() . 'pengaturan/tokoadmin'
                        ];

                        $this->alert_popup([
                            'name' => 'success',
                            'swal' => [
                                'title' => 'Data  berhasil di edit.',
                                'type' => 'success'
                            ]
                        ]);
                    }
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

}