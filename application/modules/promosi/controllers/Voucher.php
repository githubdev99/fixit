<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Voucher extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth_seller();
    }

    public function index()
    {
        $title = 'Voucher';
        $data = [
            'core' => $this->core($title),
            'plugin' => ['datatables', 'flatpickr', 'magnific-popup', 'select2'],
            'get_view' => 'promosi/voucher/v_daftar_voucher',
            'get_script' => 'promosi/voucher/script_daftar_voucher'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $post_data = json_decode(json_encode($this->input->post('post_data')), TRUE);

            if ($this->input->post('submit') == 'delete') {
                $query = $this->master_model->delete_data([
                    'where' => [
                        'id_promo' => decrypt_text($this->input->post('id_promo'))
                    ],
                    'table' => 'promo_toko'
                ]);

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data voucher ' . $post_data['kode_promo'] . ' gagal di hapus.'
                    ];
                } else {
                    if (!empty($post_data['banner_promo'])) {
                        if (file_exists($this->data['put_upload_other'] . 'file/' . $post_data['banner_promo'])) {
                            unlink($this->data['put_upload_other'] . 'file/' . $post_data['banner_promo']);
                        }
                    }

                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data voucher ' . $post_data['kode_promo'] . ' berhasil di hapus.'
                    ];
                }
            } else {
                $query = $this->master_model->send_data([
                    'where' => [
                        'id_promo' => decrypt_text($this->input->post('id_promo'))
                    ],
                    'data' => [
                        'status_aktif' => $this->input->post('submit')
                    ],
                    'table' => 'promo_toko'
                ]);

                $status_submit = ($this->input->post('submit') == 'Aktif') ? 'aktifkan' : 'nonaktifkan';

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data voucher ' . $post_data['kode_promo'] . ' gagal di ' . $status_submit . '.'
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data voucher ' . $post_data['kode_promo'] . ' berhasil di ' . $status_submit . '.'
                    ];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function tambah()
    {
        $title = 'Tambah Voucher';
        $data = [
            'core' => $this->core($title),
            'plugin' => ['flatpickr', 'select2', 'croppie'],
            'get_view' => 'promosi/voucher/v_tambah_voucher',
            'get_script' => 'promosi/voucher/script_tambah_voucher'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

            if ($this->input->post('submit') == 'insert') {
                $check_data = $this->master_model->select_data([
                    'field' => 'kode_promo',
                    'table' => 'promo_toko',
                    'where' => [
                        'LOWER(kode_promo)' => trim(strtolower($this->input->post('kode_promo')))
                    ]
                ])->result();

                if (!empty($check_data)) {
                    $checking = FALSE;
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data voucher ' . $this->input->post('kode_promo') . ' sudah pernah di simpan.'
                    ];
                }

                if ($checking == TRUE) {
                    $banner_promo = $this->convert_foto('banner_promo', $this->input->post('banner_promo'), $this->data['put_upload_other'] . 'file/');

                    if ($this->input->post('tipe_promo') == 'no') {
                        $id_kategori = NULL;
                        $id_sub_kategori = NULL;
                    } else {
                        $id_kategori = decrypt_text($this->input->post('id_kategori'));
                        $id_sub_kategori = decrypt_text($this->input->post('id_sub_kategori'));
                    }

                    if ($this->input->post('tipe_diskon') == 'nominal') {
                        $nominal_diskon = clean_rupiah($this->input->post('nominal_diskon'));
                        $persentase_diskon = NULL;
                    } else {
                        $nominal_diskon = NULL;
                        $persentase_diskon = clean_rupiah($this->input->post('persentase_diskon'));
                    }

                    $query_promo_toko = $this->master_model->send_data([
                        'data' => [
                            'kode_promo' => $this->input->post('kode_promo'),
                            'mulai' => date('Y-m-d', strtotime($this->input->post('mulai'))),
                            'berakhir' => date('Y-m-d', strtotime($this->input->post('berakhir'))),
                            'judul_promo' => $this->input->post('judul_promo'),
                            'url_promo' => '',
                            'status_aktif' => 'Aktif',
                            'id_kategori' => $id_kategori,
                            'id_sub_kategori' => $id_sub_kategori,
                            'id_toko' => $this->data['seller']->id_toko,
                            'nama_toko' => $this->data['seller']->nama_toko,
                            'created_date' => date('Y-m-d'),
                            'created_time' => date('H:i:s'),
                            'banner_promo' => $banner_promo,
                            'nominal_diskon' => $nominal_diskon,
                            'persentase_diskon' => $persentase_diskon,
                            'kuota_voucher' => $this->input->post('kuota_voucher')
                        ],
                        'table' => 'promo_toko'
                    ]);

                    if ($query_promo_toko == FALSE) {
                        if (file_exists(base_url() . 'asset/front/images/file/' . $banner_promo)) {
                            unlink(base_url() . 'asset/front/images/file/' . $banner_promo);
                        }

                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data voucher ' . $this->input->post('kode_promo') . ' gagal di simpan.',
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Voucher sedang di simpan, mohon tunggu...',
                            'callback' => base_url() . 'promosi/voucher'
                        ];

                        $this->alert_popup([
                            'name' => 'success',
                            'swal' => [
                                'title' => 'Data voucher ' . $this->input->post('kode_promo') . ' berhasil di simpan.',
                                'type' => 'success'
                            ]
                        ]);
                    }
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function edit($id)
    {
        $get_data = $this->master_model->select_data([
            'field' => '*',
            'table' => 'promo_toko',
            'where' => [
                'id_promo' => decrypt_text($id)
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

            redirect(base_url() . 'promosi/voucher', 'refresh');
        }

        $title = 'Edit Voucher';
        $data = [
            'core' => $this->core($title),
            'plugin' => ['flatpickr', 'select2', 'croppie'],
            'get_view' => 'promosi/voucher/v_edit_voucher',
            'get_script' => 'promosi/voucher/script_edit_voucher',
            'get_data' => $get_data
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

            if ($this->input->post('submit') == 'edit') {
                $check_data = $this->master_model->select_data([
                    'field' => '*',
                    'table' => 'promo_toko',
                    'where' => [
                        'LOWER(kode_promo)' => trim(strtolower($this->input->post('kode_promo'))),
                        'id_promo !=' => decrypt_text($id)
                    ]
                ])->result();

                if (!empty($check_data)) {
                    $checking = FALSE;
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data voucher ' . $this->input->post('kode_promo') . ' sudah pernah di simpan.'
                    ];
                }

                if ($checking == TRUE) {
                    if ($get_data->banner_promo != $this->input->post('banner_promo')) {
                        $banner_promo = $this->convert_foto('banner_promo', $this->input->post('banner_promo'), $this->data['put_upload_other'] . 'file/');

                        if (file_exists($this->data['put_upload_other'] . 'file/' . $get_data->banner_promo)) {
                            unlink($this->data['put_upload_other'] . 'file/' . $get_data->banner_promo);
                        }
                    } else {
                        $banner_promo = $get_data->banner_promo;
                    }

                    if ($this->input->post('tipe_promo') == 'no') {
                        $id_kategori = NULL;
                        $id_sub_kategori = NULL;
                    } else {
                        $id_kategori = decrypt_text($this->input->post('id_kategori'));
                        $id_sub_kategori = decrypt_text($this->input->post('id_sub_kategori'));
                    }

                    if ($this->input->post('tipe_diskon') == 'nominal') {
                        $nominal_diskon = clean_rupiah($this->input->post('nominal_diskon'));
                        $persentase_diskon = NULL;
                    } else {
                        $nominal_diskon = NULL;
                        $persentase_diskon = clean_rupiah($this->input->post('persentase_diskon'));
                    }

                    $query_promo_toko = $this->master_model->send_data([
                        'where' => [
                            'id_promo' => decrypt_text($id)
                        ],
                        'data' => [
                            'kode_promo' => $this->input->post('kode_promo'),
                            'mulai' => date('Y-m-d', strtotime($this->input->post('mulai'))),
                            'berakhir' => date('Y-m-d', strtotime($this->input->post('berakhir'))),
                            'judul_promo' => $this->input->post('judul_promo'),
                            'url_promo' => '',
                            'status_aktif' => 'Aktif',
                            'id_kategori' => $id_kategori,
                            'id_sub_kategori' => $id_sub_kategori,
                            'banner_promo' => $banner_promo,
                            'nominal_diskon' => $nominal_diskon,
                            'persentase_diskon' => $persentase_diskon,
                            'kuota_voucher' => $this->input->post('kuota_voucher')

                        ],
                        'table' => 'promo_toko'
                    ]);

                    if ($query_promo_toko == FALSE) {
                        if ($get_data->banner_promo != $banner_promo) {
                            if (file_exists(base_url() . 'asset/front/images/file/' . $banner_promo)) {
                                unlink(base_url() . 'asset/front/images/file/' . $banner_promo);
                            }
                        }

                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data voucher ' . $this->input->post('kode_promo') . ' gagal di edit.',
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Voucher sedang di edit, mohon tunggu...',
                            'callback' => base_url() . 'promosi/voucher'
                        ];

                        $this->alert_popup([
                            'name' => 'success',
                            'swal' => [
                                'title' => 'Data voucher ' . $this->input->post('kode_promo') . ' berhasil di edit.',
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
