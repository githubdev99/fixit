<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rating extends MY_Controller {

	public function __construct()
	{
        parent::__construct();
        // $this->auth_seller();
    }
    
    public function index()
    {
        $title = 'Review';
        $data = [
			'core' => $this->core($title),
			'plugin' => ['datatables', 'flatpickr', 'magnific-popup', 'select2', 'croppie'],
            'get_view' => 'review/rating/v_rating',
            'get_script' => 'review/rating/script_rating',
            'avg' => $this->master_model->select_data([
                'field' => 'AVG(rating) as Rate',
                'table' => 'produk_rating',
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko
                ]
            ])->row()->Rate,
            'jum' => $this->master_model->select_data([
                'field' => 'COUNT(id_produk) as Jumlah',
                'table' => 'produk_rating',
        
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko
                ],
              
            ])->row()->Jumlah,
            'ulasan' => $this->master_model->select_data([
                'field' => 'COUNT(rating_id) as Jumlah',
                'table' => 'produk_rating',        
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko
                ],              
            ])->row()->Jumlah
           
        ];
        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

           
        }
    }
    
    public function edit($id)
    {
        $get_data = $this->master_model->select_data([
            'field' => 'produk_rating.*,produk.*,customer.*,produk_cover.*,kategori_produk.*',
            'table' => 'produk_rating',
            'join' => [
                [
                    'table' => 'produk',
                    'on' => 'produk.id_produk = produk_rating.id_produk',
                    'type' => 'left'
                ],
                [
                    'table' => 'customer',
                    'on' => 'customer.id_customer = produk_rating.id_customer',
                    'type' => 'left'
                ],
                [
                    'table' => 'produk_cover',
                    'on' => 'produk_cover.id_produk = produk_rating.id_produk',
                    'type' => 'left'
                ],
                [
                    'table' => 'kategori_produk',
                    'on' => 'kategori_produk.id_kategori = produk.id_kategori',
                    'type' => 'left'
                ]
            ],
            'where' => [
                'produk_rating.id_produk' => $id
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

            redirect(base_url() . 'review/rating', 'refresh');
        }

        $title = 'Detail rating';
      

        $data = [
			'core' => $this->core($title),
			'plugin' => ['flatpickr', 'select2', 'croppie','datatables', 'flatpickr', 'magnific-popup','checkbox'],
            'get_view' => 'review/rating/v_detail_rating',
            'get_script' => 'review/rating/script_detail_rating',
            'get_data' => $get_data,
  
            'avg' => $this->master_model->select_data([
                'field' => 'AVG(rating) as Rate',
                'table' => 'produk_rating',
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'id_produk' => $id
                ]
            ])->row()->Rate,
            'ulasan' => $this->master_model->select_data([
                'field' => 'COUNT(rating_id) as Jumlah',
                'table' => 'produk_rating',        
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'id_produk' => $id
                ],              
            ])->row()->Jumlah
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

            if ($this->input->post('submit') == 'submit') {                
         
                $query = $this->master_model->send_data([
                    'where' => [
                        'rating_id' => $this->input->post('rating_id')
                    ],
                    'data' => [
                        'date_respon' => date('Y-m-d H:i:s'),
                        'respon' => $this->input->post('respon')
                    ],
                    'table' => 'produk_rating'
                ]); 
               

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Gagal memberi tanggapan.'
                    ];
                } else {
                  
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Berhasil memberi tanggapan.'
                    ];
                }
            }

			$this->output->set_content_type('application/json')->set_output(json_encode($output));

       
        }
    }


    

	
	


}