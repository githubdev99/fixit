<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flashsale extends MY_Controller {

	public function __construct()
	{
        parent::__construct();
        $this->auth_seller();
    }
    
    public function index()
    {
        $title = 'flashsale';
        $data = [
			'core' => $this->core($title),
			'plugin' => ['datatables', 'flatpickr', 'magnific-popup', 'select2'],
            'get_view' => 'promosi/flashsale/v_daftar_flashsale',
            'get_script' => 'promosi/flashsale/script_daftar_flashsale'
        ];
        
        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $post_data = json_decode(json_encode($this->input->post('post_data')), TRUE);

            if ($this->input->post('submit') == 'delete') {

                $this->db->trans_start();
                $query = $this->master_model->delete_data([
                    'where' => [
                        'id_data' => decrypt_text($this->input->post('id_data'))
                    ],
                    'table' => 'flashsale'
                ]);
                $last_id_produk = $this->db->insert_id();

                $queryp = $this->master_model->delete_data([
                    'where' => [
                        'id_data' => decrypt_text($this->input->post('id_data'))
                    ],
                    'table' => 'produk_flashsale'
                ]);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data flashsale gagal di hapus.'
                    ];
                } else {
                  
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data flashsale berhasil di hapus.'
                    ];
                }
            }else {
                $query = $this->master_model->send_data([
                    'where' => [
                        'id_data' => decrypt_text($this->input->post('id_data'))
                    ],
                    'data' => [
                        'status_flashsale' => $this->input->post('submit')
                    ],
                    'table' => 'flashsale'
                ]);

                $status_submit = ($this->input->post('submit') == 'aktif') ? 'aktif' : 'nonaktif';

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data flashsale '.$post_data['judul_flashsale'].' gagal di '.$status_submit.'.'
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data flashsale '.$post_data['judul_flashsale'].' berhasil di '.$status_submit.'.'
                    ];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function tambah()
    {
        $title = 'Tambah Flashsale';
        $data = [
            'core' => $this->core($title),
			'plugin' => ['flatpickr', 'select2', 'croppie','datatables', 'flatpickr', 'magnific-popup','checkbox'],
            'get_view' => 'promosi/flashsale/v_daftar_flashsale',
            'get_script' => 'promosi/flashsale/script_daftar_flashsale'
        ];
        
        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

            if ($this->input->post('submit') == 'insert') {
                $check_data = $this->master_model->select_data([
                    'field' => 'id_data',
                    'table' => 'flashsale',
                    'where' => [
                        'id_data' => $this->input->post('id_data')
                    ]
                ])->result();

                if (!empty($check_data)) {
                    $checking = FALSE;
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data flashsale  sudah pernah di simpan.'
                    ];
                }
                if ($checking == TRUE) {                 
                    
                    $query_flashsale = $this->master_model->send_data([
                        'data' => [
                            'judul_flashsale' => $this->input->post('judul_flashsale'),
                            'date_live' => date('Y-m-d', strtotime($this->input->post('date_live'))),
                            'time_live' => $this->input->post('time_live'),
                            'id_toko' => $this->data['seller']->id_toko                          

                        ],
                        'table' => 'flashsale'
                    ]);

                    $last_id_produk = $this->db->insert_id();

                    if ($query_flashsale == FALSE) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data flashsale  gagal di simpan.',
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Mohon tunggu...',
                            'callback' => base_url().'promosi/flashsale/edit/'.encrypt_text($last_id_produk)
                        ];

                        $this->alert_popup([
                            'name' => 'success',
                            'swal' => [
                                'title' => 'Silahkan tambahkan produk untuk flashsale.',
                                'type' => 'success'
                            ]
                        ]);
                    }

                }
              

            }


            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }


    public function produk_ditambahkan()
    {
        $title = 'Produk Ditambahkan';
        $data = [
            'core' => $this->core($title),
			'plugin' => ['flatpickr', 'select2', 'croppie','datatables', 'flatpickr', 'magnific-popup','checkbox'],
            'get_view' => 'promosi/flashsale/v_produk_ditambahkan',
            'get_script' => 'promosi/flashsale/script_produk_ditambahkan'
        ];
        
        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

            if ($this->input->post('submit') == 'insert') {
                $check_data = $this->master_model->select_data([
                    'field' => 'id_flashsale',
                    'table' => 'produk_flashsale',
                    'where' => [
                        'id_flashsale' => $this->input->post('id_flashsale')
                    ]
                ])->result();

                if (!empty($check_data)) {
                    $checking = FALSE;
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data flashsale  sudah pernah di simpan.'
                    ];
                }

                if ($checking == TRUE) {                 
                    
                    $query_flashsale = $this->master_model->send_data([
                        'data' => [
                            'id_produk' => decrypt_text($this->input->post('id_produk')),
                            'date_live' => date('Y-m-d', strtotime($this->input->post('date_live'))),
                            'time_live' => $this->input->post('time_live'),
                            'diskon' => $this->input->post('diskon'),
                            'jumlah_flashsale' => $this->input->post('jumlah_flashsale'),
                            'id_toko' => $this->data['seller']->id_toko
                           

                        ],
                        'table' => 'produk_flashsale'
                    ]);

                    if ($query_flashsale == FALSE) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data flashsale  gagal di simpan.',
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'flashsale sedang di simpan, mohon tunggu...',
                            'callback' => base_url().'promosi/flashsale'
                        ];

                        $this->alert_popup([
                            'name' => 'success',
                            'swal' => [
                                'title' => 'Data flashsale  berhasil di simpan.',
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
            'table' => 'flashsale',
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

            redirect(base_url().'promosi/flashsale','refresh');
        }

        $title = 'Edit Flashsale';
        $data = [
			'core' => $this->core($title),
			'plugin' => ['flatpickr', 'select2', 'croppie','datatables', 'flatpickr', 'magnific-popup','checkbox'],
            'get_view' => 'promosi/flashsale/v_edit_flashsale',
            'get_script' => 'promosi/flashsale/script_edit_flashsale',
            'get_data' => $get_data
        ];
        
        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;


            if ($this->input->post('submit') == 'edit') {
                $check_data = $this->master_model->select_data([
                    'field' => '*',
                    'table' => 'flashsale',
                    'where' => [
                        'LOWER(judul_flashsale)' => trim(strtolower($this->input->post('judul_flashsale'))),
                        'id_data !=' => decrypt_text($id)
                    ]
                ])->result();

                if (!empty($check_data)) {
                    $checking = FALSE;
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data  sudah pernah di simpan.'
                    ];
                }

                if ($checking == TRUE) {
   
                    $query_flashsale = $this->master_model->send_data([
                        'where' => [
                            'id_data' => decrypt_text($id)
                        ],
                        'data' => [
                            // 'judul_flashsale' => $this->input->post('judul_flashsale'),
                            'date_live' => date('Y-m-d', strtotime($this->input->post('date_live'))),
                            'time_live' => $this->input->post('time_live'),
                            'id_toko' => $this->data['seller']->id_toko
                        ],
                        'table' => 'produk_flashsale'
                    ]);

                    if ($query_flashsale == FALSE) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Data  gagal di edit.',
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Data sedang di edit, mohon tunggu...',
                            'callback' => base_url().'promosi/flashsale'
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

    public function produk_flashsale()
    {
       
        $title = 'Edit Flashsale';
        $data = [
			'core' => $this->core($title),
			'plugin' => ['flatpickr', 'select2', 'croppie','datatables', 'flatpickr', 'magnific-popup','checkbox'],
            'get_view' => 'promosi/flashsale/v_edit_flashsale',
            'get_script' => 'promosi/flashsale/script_edit_flashsale',
        ];
              

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            
            $post_data = json_decode(json_encode($this->input->post('post_data')), TRUE);

            if ($this->input->post('submit') == 'delete') {
                $query = $this->master_model->delete_data([
                    'where' => [
                        'id_flashsale' =>$this->input->post('id_flashsale')
                    ],
                    'table' => 'produk_flashsale'
                ]);

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data gagal di hapus.'
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data  berhasil di hapus.'
                         
                        
                    ];
                }
            }else {
                $id_data = $this->input->post('id_data');
                $query = $this->master_model->send_data([
                    'where' => [
                        'id_flashsale' =>$this->input->post('id_flashsale')
                    ],
                    'data' => [
                        'diskon' => $this->input->post('diskon'),
                        'jumlah_flashsale' => $this->input->post('jumlah_flashsale')
                    ],
                    'table' => 'produk_flashsale'
                ]);

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data gagal di hapus.'
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data Berhasil Di update.',
                        'callback' => base_url().'promosi/flashsale/edit/'.encrypt_text($id_data)
                        
                    ];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }



    public function html_flash()
	{
		$id_toko =  $this->data['seller']->id_toko;	

        $count_produk = $this->db->query("SELECT count(id_produk) AS jumlah FROM produk WHERE id_toko = '$id_toko' AND status_pilih = 'Y'")->row_array();
 
        $get_data_sum = $this->master_model->select_data([
            'field' => 'produk.*',
            'table' => 'produk',
            'where' => [
                'id_toko' =>  $this->data['seller']->id_toko
            ]
        ])->result();


        $html = '';
      
		$no = 0;
	
        foreach ($get_data_sum as $key) {
            $detail = [
                'foto' => $this->master_model->select_data([
                    'field' => 'foto',
                    'table' => 'produk_cover',
                    'where' => [
                        'id_produk' => $key->id_produk
                    ]
                ])->row(),
                'jumlah_stok' => $this->master_model->select_data([
                    'field' => 'SUM(jumlah_stok) AS jumlah_stok',
                    'table' => 'produk_stok',
                    'where' => [
                        'id_produk' => $key->id_produk,
                        'status' => 'in'
                    ]
                ])->row()->jumlah_stok,
                'variasi_min' => $this->master_model->select_data([
                    'field' => '*',
                    'table' => 'produk_variasi',
                    'where' => [
                        'id_produk' => $key->id_produk
                    ],
                    'order_by' => [
                        'harga_variasi' => 'asc'
                    ]
                ])->row(),
                'variasi_max' => $this->master_model->select_data([
                    'field' => '*',
                    'table' => 'produk_variasi',
                    'where' => [
                        'id_produk' => $key->id_produk
                    ],
                    'order_by' => [
                        'harga_variasi' => 'desc'
                    ]
                ])->row(),
                'kategori' => $this->master_model->select_data([
                    'field' => '*',
                    'table' => 'kategori_produk',
                    'where' => [
                        'id_kategori' => $key->id_kategori
                    ]
                ])->row()
            ];

            if (!empty($detail['foto']->foto)) {
                $foto = $this->data['folder_upload'].'products/'.$detail['foto']->foto;
            } else {
                $foto = $this->data['link_seller'].'asset/images/img-thumbnail.svg';
            }

            $status_pilih = ($key->status_pilih == 'Y') ? 'checked' : '';

            $html .= '      <tr width="100%" height="70px" >
                                    <td width="5%">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input  check-product" id="tanda_perproduk_'.$key->id_produk.'" onchange="check_product('.$key->id_produk.');" '.$status_pilih.'>
                                            <label class="custom-control-label" for="tanda_perproduk_'.$key->id_produk.'"></label>
                                            
                                         
                                        </div>                                       
                                    </td>
                                    <td  width="40%">
                                        <div class="row">
                                            <div class="col-3">                                            
                                                    <img src="'.$foto.'" width="40" class="img-thumbnail img-responsive">                                            
                                            </div>
                                            <div class="col-9">
                                                <span style="font-size:12px;">'.$key->nama_produk.'
                                                <br>
                                                SKU : 
                                                <br>
                                                
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>'.rupiah($key->harga).'</td>
                                    <td ><label>'.$detail['jumlah_stok'].'</label> </td>                                
                                </tr>
                        <hr>
                    
                ';

        }

		$return = array(
            'count_produk' => ($count_produk['jumlah'] != null) ? $count_produk['jumlah'] : 0,
			'html' => $html
		);

		return $return;
	}

	public function get_flash()
	{
		$id_toko =  $this->data['seller']->id_toko;	

		$output = array(
			'count_produk' => $this->html_flash()['count_produk'],
			'html' => $this->html_flash()['html']
		);

		echo json_encode($output);
    }

    public function insert_produk_pilih()
	{
       
      
        $status_pilih = $this->input->post('status_pilih');


        
        $id_data = $this->input->post('id_data');
        $id_produk = $this->input->post('id');

		if ($status_pilih == 'Y') {
            $queryf = $this->master_model->send_data([         
                'data' => [
                    'id_data' =>   $id_data,    
                    'id_produk' =>  $id_produk,    
                    'date_created' =>  date('Y-m-d H-i-s')      
                                
                ],
                'table' => 'produk_flashsale'
            ]);
        }
        if ($status_pilih == 'T') {
            $queryf = $this->master_model->delete_data([
                'where' => [
                    'id_flashsale' => $this->input->post('id_flashsale')
                ],
                'table' => 'produk_flashsale'
            ]);
        }


        $output = array(          
			'count_produk' => $this->html_flash()['count_produk']
			
		);
		echo json_encode($output);
       
    }

  

    public function update_produk_pilih()
	{
		$id_produk = $this->input->post('id');
		$status_pilih = $this->input->post('status_pilih');

		if ($status_pilih == 'Y') {	       
            $queryf = $this->master_model->send_data([   
                'where' => [
                    'id_produk' => $id_produk
                ],      
                'data' => [
                    'status_pilih'	=> $status_pilih                   
                ],
                'table' => 'produk'
            ]);

		} if ($status_pilih == 'T') {	       
            $queryf = $this->master_model->send_data([   
                'where' => [
                    'id_produk' => $id_produk
                ],      
                'data' => [
                    'status_pilih'	=> $status_pilih                   
                ],
                'table' => 'produk'
            ]);

        }
        
        $output = array(
            'count_produk' => $this->html_flash()['count_produk'],
            'html' => $this->html_flash()['html']
			
		);
		echo json_encode($output);


	}


}