<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Toko extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth_seller();
    }

    public function index()
    {
        $title = 'Pengaturan Toko';
        $data = [
            'core' => $this->core($title),
            'plugin' => ['datatables', 'flatpickr', 'magnific-popup', 'select2', 'croppie'],
            'get_view' => 'pengaturan/toko/v_pengaturan_toko',
            'get_script' => 'pengaturan/toko/script_pengaturan_toko'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            if ($this->input->post('submit') == 'profil_toko') {
                if (!empty($this->input->post('foto'))) {
                    $foto = $this->convert_foto('foto', $this->input->post('foto'), $this->data['put_upload_other'] . 'file/');
                    if (!empty($this->input->post('foto_old'))) {
                        if (file_exists($this->data['put_upload_other'] . 'file/' . $this->input->post('foto_old'))) {
                            unlink($this->data['put_upload_other'] . 'file/' . $this->input->post('foto_old'));
                        }
                    }
                } else {
                    if (!empty($this->input->post('foto_old'))) {
                        $foto = $this->input->post('foto_old');
                    } else {
                        $foto = NULL;
                    }
                }

                $query = $this->master_model->send_data([
                    'where' => [
                        'id_toko' => $this->data['seller']->id_toko
                    ],
                    'data' => [
                        'foto' => $foto,
                        'greating_message' => $this->input->post('greating_message'),
                        'deskripsi_toko' => $this->input->post('deskripsi_toko')
                    ],
                    'table' => 'toko'
                ]);

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Profil toko gagal di ubah, silahkan coba lagi.',
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'info',
                        'message' => 'Profil toko sedang di ubah, mohon tunggu...',
                        'callback' => base_url() . 'pengaturan/toko'
                    ];

                    $this->alert_popup([
                        'name' => 'success',
                        'swal' => [
                            'title' => 'Profil toko berhasil di ubah.',
                            'type' => 'success'
                        ]
                    ]);
                }
            } elseif ($this->input->post('submit') == 'banner_toko') {
                $get_data = $this->master_model->select_data([
                    'field' => '*',
                    'table' => 'banner_toko',
                    'where' => [
                        'id_toko' => $this->data['seller']->id_toko
                    ]
                ])->row();

                if (!empty($this->input->post('banner'))) {
                    $banner = $this->convert_foto('banner', $this->input->post('banner'), $this->data['put_upload_other'] . 'file/');
                    if (!empty($this->input->post('banner_old'))) {
                        if (file_exists($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old'))) {
                            unlink($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old'));
                        }
                    }
                } else {
                    if (!empty($this->input->post('banner_old'))) {
                        $banner = $this->input->post('banner_old');
                    } else {
                        $banner = NULL;
                    }
                }

                if (!empty($this->input->post('banner_1'))) {
                    $banner_1 = $this->convert_foto('banner_1', $this->input->post('banner_1'), $this->data['put_upload_other'] . 'file/');
                    if (!empty($this->input->post('banner_old_1'))) {
                        if (file_exists($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old_1'))) {
                            unlink($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old_1'));
                        }
                    }
                } else {
                    if (!empty($this->input->post('banner_old_1'))) {
                        $banner_1 = $this->input->post('banner_old_1');
                    } else {
                        $banner_1 = NULL;
                    }
                }

                if (!empty($this->input->post('banner_2'))) {
                    $banner_2 = $this->convert_foto('banner_2', $this->input->post('banner_2'), $this->data['put_upload_other'] . 'file/');
                    if (!empty($this->input->post('banner_old_2'))) {
                        if (file_exists($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old_2'))) {
                            unlink($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old_2'));
                        }
                    }
                } else {
                    if (!empty($this->input->post('banner_old_2'))) {
                        $banner_2 = $this->input->post('banner_old_2');
                    } else {
                        $banner_2 = NULL;
                    }
                }

                if (!empty($this->input->post('banner_3'))) {
                    $banner_3 = $this->convert_foto('banner_3', $this->input->post('banner_3'), $this->data['put_upload_other'] . 'file/');
                    if (!empty($this->input->post('banner_old_3'))) {
                        if (file_exists($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old_3'))) {
                            unlink($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old_3'));
                        }
                    }
                } else {
                    if (!empty($this->input->post('banner_old_3'))) {
                        $banner_3 = $this->input->post('banner_old_3');
                    } else {
                        $banner_3 = NULL;
                    }
                }

                if (!empty($this->input->post('banner_4'))) {
                    $banner_4 = $this->convert_foto('banner_4', $this->input->post('banner_4'), $this->data['put_upload_other'] . 'file/');
                    if (!empty($this->input->post('banner_old_4'))) {
                        if (file_exists($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old_4'))) {
                            unlink($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old_4'));
                        }
                    }
                } else {
                    if (!empty($this->input->post('banner_old_4'))) {
                        $banner_4 = $this->input->post('banner_old_4');
                    } else {
                        $banner_4 = NULL;
                    }
                }

                if (!empty($this->input->post('banner_5'))) {
                    $banner_5 = $this->convert_foto('banner_5', $this->input->post('banner_5'), $this->data['put_upload_other'] . 'file/');
                    if (!empty($this->input->post('banner_old_5'))) {
                        if (file_exists($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old_5'))) {
                            unlink($this->data['put_upload_other'] . 'file/' . $this->input->post('banner_old_5'));
                        }
                    }
                } else {
                    if (!empty($this->input->post('banner_old_5'))) {
                        $banner_5 = $this->input->post('banner_old_5');
                    } else {
                        $banner_5 = NULL;
                    }
                }

                if (!empty($get_data)) {
                    $query = $this->master_model->send_data([
                        'where' => [
                            'id_toko' => $this->data['seller']->id_toko
                        ],
                        'data' => [
                            'banner' => $banner,
                            'banner_1' => $banner_1,
                            'banner_2' => $banner_2,
                            'banner_3' => $banner_3,
                            'banner_4' => $banner_4,
                            'banner_5' => $banner_5
                        ],
                        'table' => 'banner_toko'
                    ]);
                } else {
                    $query = $this->master_model->send_data([
                        'data' => [
                            'id_toko' => $this->data['seller']->id_toko,
                            'banner' => $banner,
                            'banner_1' => $banner_1,
                            'banner_2' => $banner_2,
                            'banner_3' => $banner_3,
                            'banner_4' => $banner_4,
                            'banner_5' => $banner_5,
                            'created_date' => date('Y-m-d'),
                            'created_time' => date('H:i:s')
                        ],
                        'table' => 'banner_toko'
                    ]);
                }

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Banner toko gagal di ubah, silahkan coba lagi.',
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'info',
                        'message' => 'Banner toko sedang di ubah, mohon tunggu...',
                        'callback' => base_url() . 'pengaturan/toko'
                    ];

                    $this->alert_popup([
                        'name' => 'success',
                        'swal' => [
                            'title' => 'Banner toko berhasil di ubah.',
                            'type' => 'success'
                        ]
                    ]);
                }
            } elseif ($this->input->post('submit') == 'ubah_alamat') {
                $query = $this->master_model->send_data([
                    'where' => [
                        'id_toko' => $this->data['seller']->id_toko
                    ],
                    'data' => [
                        'alamat_toko' => $this->input->post('alamat_toko'),
                        'provinsi' => decrypt_text($this->input->post('provinsi')),
                        'kota_kabupaten' => decrypt_text($this->input->post('kota_kabupaten')),
                        'kecamatan' => decrypt_text(explode(':', $this->input->post('kecamatan'))[0]),
                        'kelurahan' => decrypt_text($this->input->post('kelurahan')),
                        'kode_pos' => $this->input->post('kode_pos')
                    ],
                    'table' => 'toko'
                ]);

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Alamat toko gagal di ubah, silahkan coba lagi.',
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'info',
                        'message' => 'Alamat toko sedang di ubah, mohon tunggu...',
                        'callback' => base_url() . 'pengaturan/toko'
                    ];
                    $this->alert_popup([
                        'name' => 'success',
                        'swal' => [
                            'title' => 'Alamat toko berhasil di ubah.',
                            'type' => 'success'
                        ]
                    ]);
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function get_kurir()
    {
        $filter_kurir = $this->master_model->select_data([
            'field' => 'jenis_kurir',
            'distinct' => 'jenis_kurir',
            'table' => 'daftar_kurir'
        ])->result();

        $pilihan_kurir = $this->master_model->select_data([
            'field' => 'pilihan_kurir',
            'table' => 'toko',
            'where' => [
                'id_toko' =>  $this->data['seller']->id_toko
            ]
        ])->row()->pilihan_kurir;


        $array_kurir = explode(':', $pilihan_kurir);

        $html = '';
        foreach ($filter_kurir as $key_filter) {
            $get_kurir = $this->master_model->select_data([
                'field' => '*',
                'table' => 'daftar_kurir',
                'where' => [
                    'jenis_kurir' =>  $key_filter->jenis_kurir
                ],
                'order_by' => [
                    'id_data' =>  'ASC'
                ]
            ])->result();

            $html .= '<div class="col-md-12" style="margin-bottom: 10px;"><h5 class="Bolded">' . ucwords($key_filter->jenis_kurir) . '</h5></div>';

            foreach ($get_kurir as $key) {
                $checked = (array_search($key->kode_kurir, $array_kurir) !== false) ? 'checked' : '';

                $html .= '
				<div class="col-md-5 ">
					<div class="img-thumbnail img-responsive mb-2">
						<div class="row align-items-center">
							<div class="col-md-1">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="pilih_kurir' . $key->id_data . '" ' . $checked . ' onchange="check_kurir(' . "'" . $key->kode_kurir . "'" . ', ' . $key->id_data . ');">
									<label class="custom-control-label label-check" for="pilih_kurir' . $key->id_data . '"></label>
								</div>
							</div>
							<div class="col-md-3">
								<img src="https://nimda.jaja.id/asset/front/images/file/' . $key->icon . '" width="80" class="img-kurir">
							</div>
							<div class="col-md-8">
								<p style="margin-top: 10px;">
									<span class="Bolded"><b>' . $key->kurir . '</b></span>
									<br>
                                  
                                    <span style="color:#888;" class="badge badge-soft-primary mr-2">' . ucwords($key->jenis_kurir) . '</span>
								</p>
							</div>
						</div>
					</div>
				</div> <br><br><br><br><br>
				';
            }

            $html .= '<div class="col-md-12"></div>';
        }

        $output = [
            'html' => $html,
            'pilihan_kurir' => $pilihan_kurir
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function update_kurir()
    {
        $this->master_model->send_data([
            'where' => [
                'id_toko' => $this->data['seller']->id_toko
            ],
            'data' => [
                'pilihan_kurir' => $this->input->post('pilihan_kurir')
            ],
            'table' => 'toko'
        ]);
    }
}
