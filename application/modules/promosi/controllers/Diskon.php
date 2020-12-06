<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Diskon extends MY_Controller {

	public function __construct()
	{
        parent::__construct();
        $this->auth_seller();
    }
    
    public function index()
    {
        $title = 'Pengaturan Diskon';
        $data = [
			'core' => $this->core($title),
			'plugin' => ['datatables', 'flatpickr', 'magnific-popup', 'select2'],
            'get_view' => 'promosi/diskon/v_daftar_diskon',
            'get_script' => 'promosi/diskon/script_daftar_diskon'
        ];
        
        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

            if ($this->input->post('submit') == 'atur_diskon' || $this->input->post('submit') == 'ubah_diskon') {
                if (date('Y-m-d', strtotime($this->input->post('tgl_berakhir_diskon'))) < date('Y-m-d', strtotime($this->input->post('tgl_mulai_diskon')))) {
                    $checking = FALSE;

                    $output = [
                        'error' => true,
                        'type' => 'warning',
                        'message' => 'Tanggal berakhir tidak boleh kurang dari tanggal mulai.',
                    ];
                }

                if ($checking == TRUE) {
                    $query = $this->master_model->send_data([
                        'where' => [
                            'id_data' => decrypt_text($this->input->post('id_data'))
                        ],
                        'data' => [
                            'harga_variasi' => $this->input->post('harga_variasi'),
                            'nominal_diskon' => $this->input->post('nominal_diskon'),
                            'presentase_diskon' => $this->input->post('presentase_diskon'),
                            'tgl_mulai_diskon' => date('Y-m-d', strtotime($this->input->post('tgl_mulai_diskon'))),
                            'tgl_berakhir_diskon' => date('Y-m-d', strtotime($this->input->post('tgl_berakhir_diskon')))
                        ],
                        'table' => 'produk_variasi'
                    ]);

                    $submit = ($this->input->post('submit') == 'atur_diskon') ? 'atur' : 'ubah';

                    if ($query == FALSE) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Diskon gagal di '.$submit,
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'success',
                            'message' => 'Diskon berhasil di '.$submit,
                        ];
                    }
                }
            } elseif ($this->input->post('submit') == 'delete') {
                $harga_normal = $this->master_model->select_data([
                    'field' => 'harga_normal',
                    'table' => 'produk_variasi',
                    'where' => [
                        'id_data' => decrypt_text($this->input->post('id_data'))
                    ]
                ])->row()->harga_normal;
                    
                $query = $this->master_model->send_data([
                    'where' => [
                        'id_data' => decrypt_text($this->input->post('id_data'))
                    ],
                    'data' => [
                        'harga_variasi' => $harga_normal,
                        'nominal_diskon' => NULL,
                        'presentase_diskon' => NULL,
                        'tgl_mulai_diskon' => NULL,
                        'tgl_berakhir_diskon' => NULL
                    ],
                    'table' => 'produk_variasi'
                ]);

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Diskon gagal di hapus.',
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Diskon berhasil di hapus.',
                    ];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

}