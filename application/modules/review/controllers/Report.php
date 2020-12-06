<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends MY_Controller {

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
            'get_view' => 'review/report/v_report',
            'get_script' => 'review/report/script_report',
            'report' => $this->master_model->select_data([
                'field' => 'COUNT(id_data) as Jumlah',
                'table' => 'produk_report',        
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
            'field' => '*',
            'table' => 'produk_report',
            'where' => [
                'id_produk' => $id
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

            redirect(base_url() . 'review/report', 'refresh');
        }

        $title = 'Detail Report';
      

        $data = [
			'core' => $this->core($title),
			'plugin' => ['flatpickr', 'select2', 'croppie','datatables', 'flatpickr', 'magnific-popup','checkbox'],
            'get_view' => 'review/report/v_detail_report',
            'get_script' => 'review/report/script_detail_report',
            'get_data' => $get_data
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

       
        }
    }
	
	


}