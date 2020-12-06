<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Produk extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth_seller();
    }

    public function index()
    {
        $title = 'Produk';
        $data = [
            'core' => $this->core($title),
            'plugin' => ['datatables', 'flatpickr', 'magnific-popup'],
            'get_view' => 'produk/v_daftar_produk',
            'get_script' => 'produk/script_daftar_produk'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $post_data = json_decode(json_encode($this->input->post('post_data')), TRUE);

            if ($this->input->post('submit') == 'delete') {
                $this->db->trans_start();

                $query = $this->master_model->delete_data([
                    'where' => [
                        'id_produk' => decrypt_text($this->input->post('id_produk'))
                    ],
                    'table' => 'produk'
                ]);

                if ($query != FALSE) {
                    if (!empty($post_data['foto'])) {
                        if (file_exists($this->data['put_upload'] . 'products/' . $post_data['foto'])) {
                            unlink($this->data['put_upload'] . 'products/' . $post_data['foto']);
                        }
                    }
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === false) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data produk ' . $post_data['nama_produk'] . ' gagal di hapus.'
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data produk ' . $post_data['nama_produk'] . ' berhasil di hapus.'
                    ];
                }
            } else {
                $query = $this->master_model->send_data([
                    'where' => [
                        'id_produk' => decrypt_text($this->input->post('id_produk'))
                    ],
                    'data' => [
                        'status_produk' => $this->input->post('submit')
                    ],
                    'table' => 'produk'
                ]);

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data produk ' . $post_data['nama_produk'] . ' gagal di ' . $this->input->post('submit') . '.'
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data produk ' . $post_data['nama_produk'] . ' berhasil di ' . $this->input->post('submit') . '.'
                    ];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function tambah()
    {
        $title = 'Tambah Produk';
        $data = [
            'core' => $this->core($title),
            'plugin' => ['flatpickr', 'select2', 'croppie'],
            'get_view' => 'produk/v_tambah_produk',
            'get_script' => 'produk/script_tambah_produk'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

            if ($this->input->post('submit') == 'live' || $this->input->post('submit') == 'arsipkan') {
                $check_data = $this->master_model->select_data([
                    'field' => 'nama_produk',
                    'table' => 'produk',
                    'where' => [
                        'LOWER(nama_produk)' => trim(strtolower($this->input->post('nama_produk')))
                    ]
                ])->result();

                if (!empty($check_data)) {
                    $checking = FALSE;
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data produk ' . $this->input->post('nama_produk') . ' sudah pernah di simpan.'
                    ];
                }

                if ($checking == TRUE) {
                    $this->db->trans_start();

                    $id_sub_kategori = (!empty($this->input->post('id_sub_kategori'))) ? decrypt_text($this->input->post('id_sub_kategori')) : NULL;
                    $berat = ($this->input->post('tipe_berat') == 'kilogram') ? $this->input->post('berat') * 1000 : $this->input->post('berat');
                    $masa_pengemasan = ($this->input->post('pre_order') == 'Y') ? $this->input->post('masa_pengemasan') : 0;
                    $draft = ($this->input->post('submit') == 'arsipkan') ? 'Y' : 'T';
                    $status_produk = ($this->input->post('submit') == 'arsipkan') ? 'arsipkan' : 'live';
                    $kode_sku_single = ($this->input->post('produk_variasi_harga') == 'Y') ? '' : $this->input->post('kode_sku_single');
                    $harga_single = ($this->input->post('produk_variasi_harga') == 'Y') ? 0 : $this->input->post('harga_single');
                    $stok_single = ($this->input->post('produk_variasi_harga') == 'Y') ? 0 : $this->input->post('stok_single');

                    $query_produk = $this->master_model->send_data([
                        'data' => [
                            'nama_produk' => $this->input->post('nama_produk'),
                            'id_kategori' => decrypt_text($this->input->post('id_kategori')),
                            'id_sub_kategori' => $id_sub_kategori,
                            'deskripsi' => $this->input->post('deskripsi'),
                            'merek' => decrypt_text($this->input->post('merek')),
                            'kode_sku' => $kode_sku_single,
                            'berat' => $berat,
                            'ukuran_paket_panjang' => $this->input->post('ukuran_paket_panjang'),
                            'ukuran_paket_lebar' => $this->input->post('ukuran_paket_lebar'),
                            'ukuran_paket_tinggi' => $this->input->post('ukuran_paket_tinggi'),
                            'asal_produk' => decrypt_text($this->input->post('asal_produk')),
                            'created_date' => date('Y-m-d'),
                            'created_time' => date('H:i:s'),
                            'id_user' => $this->data['customer']->id_customer,
                            'nama_user' => $this->data['customer']->nama_lengkap,
                            'disabled' => 'T',
                            'id_toko' => $this->data['seller']->id_toko,
                            'harga' => clean_rupiah($harga_single),
                            'diskon' => 0,
                            'variasi_harga' => $this->input->post('produk_variasi_harga'),
                            'stok' => $stok_single,
                            'kondisi' => $this->input->post('kondisi'),
                            'masa_pengemasan' => $masa_pengemasan,
                            'pre_order' => $this->input->post('pre_order'),
                            'draft' => $draft,
                            'status_produk' => $status_produk,
                            'slug_produk' => seo($this->input->post('nama_produk')),
                            'tag_produk' => ''
                        ],
                        'table' => 'produk'
                    ]);

                    $last_id_produk = $this->db->insert_id();

                    if ($this->input->post('produk_variasi_harga') == 'Y') {
                        for ($i = 0; $i < count($this->input->post('pilihan')); $i++) {
                            if ($this->input->post('pilihan')[$i] == 'model') {
                                $model = $this->input->post('nama')[$i];
                                $warna = NULL;
                                $ukuran = NULL;
                            } elseif ($this->input->post('pilihan')[$i] == 'warna') {
                                $model = NULL;
                                $warna = $this->input->post('nama')[$i];
                                $ukuran = NULL;
                            } else {
                                $model = NULL;
                                $warna = NULL;
                                $ukuran = $this->input->post('nama')[$i];
                            }

                            $query_produk_variasi = $this->master_model->send_data([
                                'data' => [
                                    'id_produk' => $last_id_produk,
                                    'kode_sku' => $this->input->post('sku')[$i],
                                    'model' => $model,
                                    'warna' => $warna,
                                    'ukuran' => $ukuran,
                                    'harga_normal' => clean_rupiah($this->input->post('harga')[$i]),
                                    'harga_variasi' => clean_rupiah($this->input->post('harga')[$i]),
                                    'stok' => $this->input->post('stok')[$i]
                                ],
                                'table' => 'produk_variasi'
                            ]);

                            $last_id_variasi = $this->db->insert_id();

                            $query_produk_stok = $this->master_model->send_data([
                                'data' => [
                                    'id_produk' => $last_id_produk,
                                    'id_variasi' => $last_id_variasi,
                                    'jumlah_stok' => $this->input->post('stok')[$i],
                                    'created_date' => date('Y-m-d'),
                                    'created_time' => date('H:i:s'),
                                    'status' => 'in'
                                ],
                                'table' => 'produk_stok'
                            ]);
                        }
                    } else {
                        $model = NULL;
                        $warna = NULL;
                        $ukuran = NULL;

                        $query_produk_variasi = $this->master_model->send_data([
                            'data' => [
                                'id_produk' => $last_id_produk,
                                'kode_sku' => $this->input->post('kode_sku_single'),
                                'model' => $model,
                                'warna' => $warna,
                                'ukuran' => $ukuran,
                                'harga_normal' => clean_rupiah($this->input->post('harga_single')),
                                'harga_variasi' => clean_rupiah($this->input->post('harga_single')),
                                'stok' => $this->input->post('stok_single')
                            ],
                            'table' => 'produk_variasi'
                        ]);

                        $last_id_variasi = $this->db->insert_id();

                        $query_produk_stok = $this->master_model->send_data([
                            'data' => [
                                'id_produk' => $last_id_produk,
                                'id_variasi' => $last_id_variasi,
                                'jumlah_stok' => $this->input->post('stok_single'),
                                'created_date' => date('Y-m-d'),
                                'created_time' => date('H:i:s'),
                                'status' => 'in'
                            ],
                            'table' => 'produk_stok'
                        ]);
                    }

                    for ($i = 0; $i < 5; $i++) {
                        if (!empty($this->input->post('file_foto_' . $i))) {
                            $foto = $this->convert_foto('file_foto_' . $i, $this->input->post('file_foto_' . $i), $this->data['put_upload'] . 'products/');
                            if ($foto != false) {
                                $thumbnail = ($i == 1) ? 'Y' : 'T';

                                $query_produk_cover = $this->master_model->send_data([
                                    'data' => [
                                        'foto' => $foto,
                                        'created_date' => date('Y-m-d'),
                                        'created_time' => date('H:i:s'),
                                        'id_user' => $this->data['customer']->id_customer,
                                        'nama_user' => $this->data['customer']->nama_lengkap,
                                        'thumbnail' => $thumbnail,
                                        'id_produk' => $last_id_produk
                                    ],
                                    'table' => 'produk_cover'
                                ]);
                            }
                        }
                    }

                    $this->db->trans_complete();

                    if ($this->db->trans_status() === false) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data produk ' . $this->input->post('nama_produk') . ' gagal di simpan.',
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Produk sedang di simpan, mohon tunggu...',
                            'callback' => base_url() . 'produk'
                        ];

                        $this->alert_popup([
                            'name' => 'success',
                            'swal' => [
                                'title' => 'Data produk ' . $this->input->post('nama_produk') . ' berhasil di simpan.',
                                'type' => 'success'
                            ]
                        ]);
                    }
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function edit($id = NULL)
    {
        $title = 'Edit Produk';
        $data = [
            'core' => $this->core($title),
            'plugin' => ['flatpickr', 'select2', 'croppie'],
            'get_view' => 'produk/v_edit_produk',
            'get_script' => 'produk/script_edit_produk',
            'get_data' => [
                'master' => $this->master_model->select_data([
                    'field' => 'produk.*, daftar_merek.merek AS nama_merek, asal_produk.asal_produk AS nama_asal_produk',
                    'table' => 'produk',
                    'join' => [
                        [
                            'table' => 'daftar_merek',
                            'on' => 'daftar_merek.id_data = produk.merek',
                            'type' => 'inner'
                        ],
                        [
                            'table' => 'asal_produk',
                            'on' => 'asal_produk.id_data = produk.asal_produk',
                            'type' => 'inner'
                        ]
                    ],
                    'where' => [
                        'produk.id_produk' => decrypt_text($id)
                    ]
                ])->row(),
                'foto' => $this->master_model->select_data([
                    'field' => '*',
                    'table' => 'produk_cover',
                    'where' => [
                        'id_produk' => decrypt_text($id)
                    ]
                ])->result(),
                'variasi' => $this->master_model->select_data([
                    'field' => '*',
                    'table' => 'produk_variasi',
                    'where' => [
                        'id_produk' => decrypt_text($id)
                    ]
                ])->result(),
                'variasi_total' => $this->master_model->count_all_data([
                    'where' => [
                        'id_produk' => decrypt_text($id)
                    ],
                    'table' => 'produk_variasi'
                ])
            ]
        ];

        // Check URL
        if (empty($data['get_data']['master'])) {
            $this->alert_popup([
                'name' => 'failed',
                'swal' => [
                    'title' => 'Ada kesalahan teknis',
                    'type' => 'error'
                ]
            ]);

            redirect(base_url() . 'produk', 'refresh');
        } elseif (empty($id)) {
            $this->alert_popup([
                'name' => 'failed',
                'swal' => [
                    'title' => 'Ada kesalahan teknis',
                    'type' => 'error'
                ]
            ]);

            redirect(base_url() . 'produk', 'refresh');
        }

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

            if ($this->input->post('submit') == 'edit-live' || $this->input->post('submit') == 'edit-arsipkan') {
                $check_data = $this->master_model->select_data([
                    'field' => 'nama_produk',
                    'table' => 'produk',
                    'where' => [
                        'LOWER(nama_produk)' => trim(strtolower($this->input->post('nama_produk'))),
                        'id_produk !=' => decrypt_text($id)
                    ]
                ])->result();

                if (!empty($check_data)) {
                    $checking = FALSE;
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data produk ' . $this->input->post('nama_produk') . ' sudah pernah di simpan.'
                    ];
                }

                if ($checking == TRUE) {
                    $this->db->trans_start();

                    $id_sub_kategori = (!empty($this->input->post('id_sub_kategori'))) ? decrypt_text($this->input->post('id_sub_kategori')) : NULL;
                    $berat = ($this->input->post('tipe_berat') == 'kilogram') ? $this->input->post('berat') * 1000 : $this->input->post('berat');
                    $masa_pengemasan = ($this->input->post('pre_order') == 'Y') ? $this->input->post('masa_pengemasan') : 0;
                    $draft = ($this->input->post('submit') == 'edit-arsipkan') ? 'Y' : 'T';
                    $status_produk = ($this->input->post('submit') == 'edit-arsipkan') ? 'arsipkan' : 'live';
                    $kode_sku_single = ($this->input->post('produk_variasi_harga') == 'Y') ? '' : $this->input->post('kode_sku_single');
                    $harga_single = ($this->input->post('produk_variasi_harga') == 'Y') ? 0 : $this->input->post('harga_single');
                    $stok_single = ($this->input->post('produk_variasi_harga') == 'Y') ? 0 : $this->input->post('stok_single');

                    $query_produk = $this->master_model->send_data([
                        'where' => [
                            'id_produk' => decrypt_text($id)
                        ],
                        'data' => [
                            'nama_produk' => $this->input->post('nama_produk'),
                            'id_kategori' => decrypt_text($this->input->post('id_kategori')),
                            'id_sub_kategori' => $id_sub_kategori,
                            'deskripsi' => $this->input->post('deskripsi'),
                            'merek' => decrypt_text($this->input->post('merek')),
                            'kode_sku' => $kode_sku_single,
                            'berat' => $berat,
                            'ukuran_paket_panjang' => $this->input->post('ukuran_paket_panjang'),
                            'ukuran_paket_lebar' => $this->input->post('ukuran_paket_lebar'),
                            'ukuran_paket_tinggi' => $this->input->post('ukuran_paket_tinggi'),
                            'asal_produk' => decrypt_text($this->input->post('asal_produk')),
                            'harga' => clean_rupiah($harga_single),
                            'variasi_harga' => $this->input->post('produk_variasi_harga'),
                            'stok' => $stok_single,
                            'kondisi' => $this->input->post('kondisi'),
                            'masa_pengemasan' => $masa_pengemasan,
                            'pre_order' => $this->input->post('pre_order'),
                            'draft' => $draft,
                            'status_produk' => $status_produk,
                            'slug_produk' => seo($this->input->post('nama_produk'))
                        ],
                        'table' => 'produk'
                    ]);

                    $last_id_produk = decrypt_text($id);

                    if ($this->input->post('produk_variasi_harga') == 'Y') {
                        $this->master_model->delete_data([
                            'where' => [
                                'id_produk' => $last_id_produk
                            ],
                            'table' => 'produk_variasi'
                        ]);

                        $this->master_model->delete_data([
                            'where' => [
                                'id_produk' => $last_id_produk
                            ],
                            'table' => 'produk_stok'
                        ]);

                        for ($i = 0; $i < count($this->input->post('pilihan')); $i++) {
                            if ($this->input->post('pilihan')[$i] == 'model') {
                                $model = $this->input->post('nama')[$i];
                                $warna = NULL;
                                $ukuran = NULL;
                            } elseif ($this->input->post('pilihan')[$i] == 'warna') {
                                $model = NULL;
                                $warna = $this->input->post('nama')[$i];
                                $ukuran = NULL;
                            } else {
                                $model = NULL;
                                $warna = NULL;
                                $ukuran = $this->input->post('nama')[$i];
                            }

                            $query_produk_variasi = $this->master_model->send_data([
                                'data' => [
                                    'id_produk' => $last_id_produk,
                                    'kode_sku' => $this->input->post('sku')[$i],
                                    'model' => $model,
                                    'warna' => $warna,
                                    'ukuran' => $ukuran,
                                    'harga_normal' => clean_rupiah($this->input->post('harga')[$i]),
                                    'harga_variasi' => clean_rupiah($this->input->post('harga')[$i]),
                                    'stok' => $this->input->post('stok')[$i]
                                ],
                                'table' => 'produk_variasi'
                            ]);

                            $last_id_variasi = $this->db->insert_id();

                            $query_produk_stok = $this->master_model->send_data([
                                'data' => [
                                    'id_produk' => $last_id_produk,
                                    'id_variasi' => $last_id_variasi,
                                    'jumlah_stok' => $this->input->post('stok')[$i],
                                    'created_date' => date('Y-m-d'),
                                    'created_time' => date('H:i:s'),
                                    'status' => 'in'
                                ],
                                'table' => 'produk_stok'
                            ]);
                        }
                    } else {
                        $model = NULL;
                        $warna = NULL;
                        $ukuran = NULL;

                        if (!empty($this->input->post('id_variasi'))) {
                            $last_id_variasi = decrypt_text($this->input->post('id_variasi'));

                            $query_produk_variasi = $this->master_model->send_data([
                                'where' => [
                                    'id_data' => $last_id_variasi
                                ],
                                'data' => [
                                    'kode_sku' => $this->input->post('kode_sku_single'),
                                    'model' => $model,
                                    'warna' => $warna,
                                    'ukuran' => $ukuran,
                                    'harga_normal' => clean_rupiah($this->input->post('harga_single')),
                                    'harga_variasi' => clean_rupiah($this->input->post('harga_single')),
                                    'stok' => $this->input->post('stok_single')
                                ],
                                'table' => 'produk_variasi'
                            ]);

                            $query_produk_stok = $this->master_model->send_data([
                                'where' => [
                                    'id_variasi' => $last_id_variasi
                                ],
                                'data' => [
                                    'jumlah_stok' => $this->input->post('stok_single')
                                ],
                                'table' => 'produk_stok'
                            ]);
                        } else {
                            $this->master_model->delete_data([
                                'where' => [
                                    'id_produk' => $last_id_produk
                                ],
                                'table' => 'produk_variasi'
                            ]);

                            $this->master_model->delete_data([
                                'where' => [
                                    'id_produk' => $last_id_produk
                                ],
                                'table' => 'produk_stok'
                            ]);

                            $query_produk_variasi = $this->master_model->send_data([
                                'data' => [
                                    'id_produk' => $last_id_produk,
                                    'kode_sku' => $this->input->post('kode_sku_single'),
                                    'model' => $model,
                                    'warna' => $warna,
                                    'ukuran' => $ukuran,
                                    'harga_normal' => clean_rupiah($this->input->post('harga_single')),
                                    'harga_variasi' => clean_rupiah($this->input->post('harga_single')),
                                    'stok' => $this->input->post('stok_single')
                                ],
                                'table' => 'produk_variasi'
                            ]);

                            $last_id_variasi = $this->db->insert_id();

                            $query_produk_stok = $this->master_model->send_data([
                                'data' => [
                                    'id_produk' => $last_id_produk,
                                    'id_variasi' => $last_id_variasi,
                                    'jumlah_stok' => $this->input->post('stok_single'),
                                    'created_date' => date('Y-m-d'),
                                    'created_time' => date('H:i:s'),
                                    'status' => 'in'
                                ],
                                'table' => 'produk_stok'
                            ]);
                        }
                    }

                    for ($i = 1; $i <= 5; $i++) {
                        if (!empty($this->input->post('file_foto_' . $i))) {
                            $get_foto = $this->master_model->select_data([
                                'field' => '*',
                                'table' => 'produk_cover',
                                'where' => [
                                    'id_foto' => decrypt_text($this->input->post('id_foto_' . $i))
                                ]
                            ])->row();

                            if (!empty($get_foto)) {
                                if ($get_foto->foto != $this->input->post('file_foto_' . $i)) {
                                    $foto = $this->convert_foto('file_foto_' . $i, $this->input->post('file_foto_' . $i), $this->data['put_upload'] . 'products/');

                                    if (file_exists($this->data['put_upload'] . 'products/' . $get_foto->foto)) {
                                        unlink($this->data['put_upload'] . 'products/' . $get_foto->foto);
                                    }
                                } else {
                                    $foto = $get_foto->foto;
                                }

                                $query_produk_cover = $this->master_model->send_data([
                                    'where' => [
                                        'id_foto' => decrypt_text($this->input->post('id_foto_' . $i))
                                    ],
                                    'data' => [
                                        'foto' => $foto
                                    ],
                                    'table' => 'produk_cover'
                                ]);
                            } else {
                                $foto = $this->convert_foto('file_foto_' . $i, $this->input->post('file_foto_' . $i), $this->data['put_upload'] . 'products/');
                                if ($foto != false) {
                                    $thumbnail = ($i == 1) ? 'Y' : 'T';

                                    $query_produk_cover = $this->master_model->send_data([
                                        'data' => [
                                            'foto' => $foto,
                                            'created_date' => date('Y-m-d'),
                                            'created_time' => date('H:i:s'),
                                            'id_user' => $this->data['customer']->id_customer,
                                            'nama_user' => $this->data['customer']->nama_lengkap,
                                            'thumbnail' => $thumbnail,
                                            'id_produk' => $last_id_produk
                                        ],
                                        'table' => 'produk_cover'
                                    ]);
                                }
                            }
                        } else {
                            $get_foto = $this->master_model->select_data([
                                'field' => '*',
                                'table' => 'produk_cover',
                                'where' => [
                                    'id_foto' => decrypt_text($this->input->post('id_foto_' . $i))
                                ]
                            ])->row();

                            if (!empty($get_foto)) {
                                if (file_exists($this->data['put_upload'] . 'products/' . $get_foto->foto)) {
                                    unlink($this->data['put_upload'] . 'products/' . $get_foto->foto);
                                }

                                $this->master_model->delete_data([
                                    'where' => [
                                        'id_foto' => decrypt_text($this->input->post('id_foto_' . $i))
                                    ],
                                    'table' => 'produk_cover'
                                ]);
                            }
                        }
                    }

                    $this->db->trans_complete();

                    if ($this->db->trans_status() === false) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data produk ' . $this->input->post('nama_produk') . ' gagal di edit.',
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Produk sedang di edit, mohon tunggu...',
                            'callback' => base_url() . 'produk'
                        ];

                        $this->alert_popup([
                            'name' => 'success',
                            'swal' => [
                                'title' => 'Data produk ' . $this->input->post('nama_produk') . ' berhasil di edit.',
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
