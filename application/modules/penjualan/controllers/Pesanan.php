<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pesanan extends MY_Controller {

	public function __construct()
	{
        parent::__construct();
        $this->auth_seller();
        $this->load->library('rajaongkir');

    }
    
    public function index()
    { 
        $title = 'Pesanan';
        $data = [
			'core' => $this->core($title),
			'plugin' => ['datatables', 'flatpickr', 'magnific-popup'],
            'get_view' => 'penjualan/pesanan/v_pesanan',
            'get_script' => 'penjualan/pesanan/script_pesanan'
        ];
        
        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $post_data = json_decode(json_encode($this->input->post('post_data')), TRUE);

            if ($this->input->post('submit') == 'insert_resi') {
                $this->db->trans_start();

                $querystatus = $this->master_model->send_data([
                    'where' => [
                        'id_data' => $this->input->post('get_id_data_order')
                    ],
                    'data' => [
                        'status_pesanan' => 'dikirim'
                    ],
                    'table' => 'transaksi_detail'
                ]);  
                
                $queryresi = $this->master_model->send_data([
                    'where' => [
                        'id_data' => $this->input->post('get_id_data')
                    ],
                    'data' => [
                        'nomor_resi' => $this->input->post('get_nomor_resi'),
                        'tipe_pengiriman' => 'manual booking'
                    ],
                    'table' => 'resi_data'
                ]);

                $this->db->trans_complete();


                 if ($this->db->trans_status() === false) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Nomor resi gagal di simpan.',
                        ];
                    } else {
                        $output = [
                            'error' => false,
                            'type' => 'success',
                            'message' => 'Nomor resi berhasil di simpan.',
                        ];
                    }
            } elseif ($this->input->post('submit') == 'terima') {

                $query = $this->master_model->send_data([
                    'where' => [
                        'id_data' => $this->input->post('id_data')
                    ],
                    'data' => [
                        'terima_pesanan' => 'terima'
                    ],
                    'table' => 'transaksi'
                ]);

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data Gagal di Terima.'
                    ];
                } else {                  
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Pesanan di Terima.'
                    ];
                }
            } elseif ($this->input->post('submit') == 'tolak') {
                
                $this->db->trans_start();
                $query = $this->master_model->send_data([
                    'where' => [
                        'id_data' => $this->input->post('get_id_data_inv')
                    ],
                    'data' => [
                        'status_pembatalan' => 'Pembatalan Dari Penjual',
                        'terima_pesanan' => 'tolak',
                        'alasan_tolak' => $this->input->post('alasan_tolak'),
                        'status_transaksi' => 'Batal'
                    ],
                    'table' => 'transaksi'
                ]);

                $query1 = $this->master_model->send_data([
                    'where' => [
                        'id_data' => $this->input->post('get_id_data_inv')
                    ],
                    'data' => [
                        'status_pesanan' => 'dibatalkan'
                    ],
                    'table' => 'transaksi_detail'
                ]);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data Gagal di Tolak.'
                    ];
                } else {
                  
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Pesanan di Tolak.'
                    ];
                }
            }

			$this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function detail($id)
    {
        $get_data = $this->master_model->select_data([
            'field' => 'transaksi_detail.*,transaksi.*,customer.*',
            'table' => 'transaksi',
            'join' => [
                [
                    'table' => 'transaksi_detail',
                    'on' => 'transaksi_detail.id_data = transaksi.id_data',
                    'type' => 'left'
                ],
                [
                    'table' => 'customer',
                    'on' => 'transaksi.id_customer = customer.id_customer',
                    'type' => 'left'
                ]
            ],    
            'where' => [
                'transaksi.id_data' => decrypt_text($id)
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

            redirect(base_url().'penjualan/pesanan','refresh');
        }

        $get_data_produk = $this->master_model->select_data([
            'field' => 'transaksi_detail.*,transaksi.*,customer.*, promo_toko.nominal_diskon, promo_toko.persentase_diskon, promo_jaja.kategori_promo,',
            'table' => 'transaksi',
            'join' => [
                [
                    'table' => 'transaksi_detail',
                    'on' => 'transaksi_detail.id_data = transaksi.id_data',
                    'type' => 'left'
                ],
                [
                    'table' => 'promo_toko',
                    'on' => 'transaksi.id_voucher_toko = promo_toko.id_promo',
                    'type' => 'left'
                ],
                [
                    'table' => 'promo_jaja',
                    'on' => 'transaksi.id_voucher = promo_jaja.id_promo',
                    'type' => 'left'
                ],
                [
                    'table' => 'customer',
                    'on' => 'transaksi.id_customer = customer.id_customer',
                    'type' => 'left'
                ]
            ],    
            'where' => [
                'transaksi.id_data' => decrypt_text($id)
            ]
        ])->result();

        $title = 'Transaksi #'.$get_data->invoice;
        $data = [
			'core' => $this->core($title),
			'plugin' => ['flatpickr', 'select2', 'croppie'],
            'get_view' => 'penjualan/pesanan/v_detail_pesanan',
            'get_script' => 'penjualan/pesanan/script_detail_pesanan',
            'get_data' => $get_data,
            'get_data_produk' => $get_data_produk
        ];

        $querystatus = $this->master_model->send_data([
            'where' => [
                'id_data' => $id
            ],
            'data' => [
                'notifikasi_seller' => 'Y'
            ],
            'table' => 'transaksi'
        ]);  

        if(isset($id)){
            $this->ReadNotif($id, 'transaksi', 'notifikasi_seller', 'id_data');
        }
     
        
        
        $this->master->template($data);

    }

    public function update_resi()
    {
        $title = 'Pesanan';
        $data = [
			'core' => $this->core($title),
			'plugin' => ['datatables', 'flatpickr', 'magnific-popup'],
            'get_view' => 'penjualan/pesanan/v_pesanan',
            'get_script' => 'penjualan/pesanan/script_pesanan'
        ];
        
        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function cetak_label($id)
    {
        $get_data = $this->master_model->select_data([
            'field' => 'transaksi_detail.*,transaksi.*,customer.*,resi_data.tipe_pengiriman,resi_data.nomor_resi,daftar_kurir.icon',
            'table' => 'transaksi',
            'join' => [
                [
                    'table' => 'transaksi_detail',
                    'on' => 'transaksi_detail.id_data = transaksi.id_data',
                    'type' => 'left'
                ],
                [
                    'table' => 'customer',
                    'on' => 'transaksi.id_customer = customer.id_customer',
                    'type' => 'left'
                ],
                [
                    'table' => 'resi_data',
                    'on' => 'transaksi.invoice = resi_data.invoice',
                    'type' => 'left'
                ],
                [
                    'table' => 'daftar_kurir',
                    'on' => 'transaksi.code_pengiriman = daftar_kurir.kode_kurir',
                    'type' => 'left'
                ]
            ],    
            'where' => [
                'transaksi.id_data' => $id
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

            redirect(base_url().'penjualan/pesanan','refresh');
        }

        $get_data_produk = $this->master_model->select_data([
            'field' => 'transaksi_detail.*,transaksi.*,customer.*, promo_toko.nominal_diskon, promo_toko.persentase_diskon, promo_jaja.kategori_promo,',
            'table' => 'transaksi',
            'join' => [
                [
                    'table' => 'transaksi_detail',
                    'on' => 'transaksi_detail.id_data = transaksi.id_data',
                    'type' => 'left'
                ],
                [
                    'table' => 'promo_toko',
                    'on' => 'transaksi.id_voucher_toko = promo_toko.id_promo',
                    'type' => 'left'
                ],
                [
                    'table' => 'promo_jaja',
                    'on' => 'transaksi.id_voucher = promo_jaja.id_promo',
                    'type' => 'left'
                ],
                [
                    'table' => 'customer',
                    'on' => 'transaksi.id_customer = customer.id_customer',
                    'type' => 'left'
                ]
            ],    
            'where' => [
                'transaksi.id_data' => $id
            ]
        ])->result();

        $title = 'Cetak Label_#'.$get_data->invoice;
        $data = [
			'core' => $this->core($title),
			'plugin' => ['flatpickr', 'select2', 'croppie'],
            'get_view' => 'penjualan/pesanan/v_cetak_label',
            'get_script' => 'penjualan/pesanan/script_detail_pesanan',
            'get_data' => $get_data,
            'get_data_produk' => $get_data_produk
        ];
        
        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

           
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function Track($invoice)
	{
            $data_resi = $this->master_model->select_data([
                'field' => 'resi_data.*',
                'table' => 'resi_data',    
                'where' => [
                    'invoice' => $invoice
                ]
            ])->row();
    
			$cek_resi = $this->rajaongkir->cek_resi($data_resi->nomor_resi, $data_resi->kurir);
			$html = '';
				if ($cek_resi->rajaongkir->status->code == '200') {
				$manifest = $cek_resi->rajaongkir->result;
					foreach ($manifest->manifest as $key) {
						$html .=  '<ul class="events">';
						$html .=  '<li>';
						$html .=  '<time datetime="'.$key->manifest_time.'"></time> ';
                        $html .=  '<span><strong>'.$key->manifest_description.'</strong>
                         <br> '.$key->manifest_date.' '.$key->manifest_time.'</span>';
						$html .=  '</li>';
						$html .=  '</ul>';
					}
				} else {
					$html .=  '<small class="bg-warning text-center" style="padding: 3px;">';
					$html .= $cek_resi->rajaongkir->status->description;
					$html .=  '</small>';
				}				
				echo $html;
		
    }
    
    public function Cetak($invoice)
	{
        $data_trans = $this->master_model->select_data([
            'field' => 'transaksi_detail.*',
            'table' => 'transaksi_detail',    
            'where' => [
                'invoice' => $invoice
            ]
        ])->row();
    }

    public function read($invoice){
        

        $query1 = $this->master_model->send_data([
            'where' => [
                'invoice' => $invoice
            ],
            'data' => [
                'notifikasi_seller' => 'Y'
            ],
            'table' => 'notifikasi'
        ]);


    }

}