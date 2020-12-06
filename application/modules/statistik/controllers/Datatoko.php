<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datatoko extends MY_Controller {

	public function __construct()
	{
        parent::__construct();
        $this->auth_seller();
    }
    
    public function index()
    {
        $get_data_produk = $this->master_model->select_data([
            'field' => 'transaksi_detail.*, SUM(transaksi_detail.qty) as total, produk_cover.*',
            'table' => 'transaksi_detail',
            'join' => [
                [
                    'table' => 'produk_cover',
                    'on' => 'transaksi_detail.id_produk = produk_cover.id_produk',
                    'type' => 'left'
                ]
            ],
            'where' => [
                'transaksi_detail.id_toko' => $this->data['seller']->id_toko,
                'transaksi_detail.status_pesanan' =>  'selesai'
            ],
            'group_by' => 'transaksi_detail.id_produk',
            'order_by' => [
                'total' =>  'DESC'
            ],
            'limit' => '5'
        ])->result();

        // for($i=1; $i<=12; $i++){
        //     $grafik_penjualan = $this->master_model->select_data([
        //         'field' => 'transaksi.*, sum(total_pembayaran) as total',
        //         'table' => 'transaksi',               
        //         'where' => [
        //             'id_toko' => $this->data['seller']->id_toko,
        //             'status_transaksi' =>  'Telah dibayar',
        //             'MONTH(tgl_pembayaran)' =>  $i
        //         ],    
        //     ])->row()->total;

        // }
     

        

        $title = 'Data Toko';
        $data = [
			'core' => $this->core($title),
			'plugin' => ['datatables', 'flatpickr', 'magnific-popup', 'select2', 'moment', 'apexcharts'],
            'get_view' => 'statistik/datatoko/v_data_toko',
            'get_script' => 'statistik/datatoko/script_data_toko',
            'get_data_produk' => $get_data_produk,

            'jumlah_penjualan' => $this->master_model->select_data([
                'field' => 'SUM(total_pembayaran) as jumlahPenjualan',
                'table' => 'transaksi',
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' => 'Telah dibayar'
                    ]
            ])->row()->jumlahPenjualan,

            'jumlah_produk_dilihat' => $this->master_model->select_data([
                'field' => 'monitor_produk.*,produk.*, COUNT(monitor_produk.id_produk) as total_dilihat',
                'table' => 'monitor_produk',
                'join' => [
                    [
                        'table' => 'produk',
                        'on' => 'monitor_produk.id_produk = produk.id_produk',
                        'type' => 'left'
                    ]
                ],
                'where' => [
                    'produk.id_toko' => $this->data['seller']->id_toko
                    ]
            ])->row()->total_dilihat,

            'jumlah_pengunjung' => $this->master_model->select_data([
                'field' => 'monitor_pengunjung_toko.*,COUNT(id_data) as total_pengunjung',
                'table' => 'monitor_pengunjung_toko',
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko
                    ]
            ])->row()->total_pengunjung,

            'jumlah_pesanan' => $this->master_model->select_data([
                'field' => 'transaksi.*,COUNT(invoice) as total_pesanan',
                'table' => 'transaksi',
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' => 'Telah dibayar'
                    ]
            ])->row()->total_pesanan,

            'jan' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '01'
                 ],    
             ])->row()->total,

             'feb' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '02'
                 ],    
             ])->row()->total,
             'mar' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '03'
                 ],    
             ])->row()->total,

             'apr' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '04'
                 ],    
             ])->row()->total,
             'mei' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '05'
                 ],    
             ])->row()->total,
             'jun' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '06'
                 ],    
             ])->row()->total,
             'jul' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '07'
                 ],    
             ])->row()->total,  
            'agus' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '08'
                 ],    
             ])->row()->total,
             'sept' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '09'
                 ],    
             ])->row()->total,
             'okt' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '10'
                 ],    
             ])->row()->total,
             'nov' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '11'
                 ],    
             ])->row()->total,
             'des' => $this->master_model->select_data([
                'field' => 'transaksi.*, sum(total_pembayaran) as total',
                'table' => 'transaksi',               
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status_transaksi' =>  'Telah dibayar',
                    'MONTH(tgl_pembayaran)' =>  '12'
                 ],    
             ])->row()->total

        ];

        if (!$this->input->post()) {

            $this->master->template($data);
        } else {           
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
        }

    }


    

  

}