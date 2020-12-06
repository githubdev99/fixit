<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth_seller();
    }

    public function index()
    {
        $title = 'Seller Dashboard';
        $data = [
            'core' => $this->core($title),
            'plugin' => ['flatpickr', 'moment', 'apexcharts'],
            'get_view' => 'home/v_home',
            'get_script' => 'home/script_home',
            'get_data_produk' => $this->master_model->select_data([
                'field' => 'transaksi_detail.*, transaksi.*, SUM(transaksi_detail.qty) as total, produk_cover.*',
                'table' => 'transaksi_detail',
                'join' => [
                    [
                        'table' => 'produk_cover',
                        'on' => 'transaksi_detail.id_produk = produk_cover.id_produk',
                        'type' => 'left'
                    ],
                    [
                        'table' => 'transaksi',
                        'on' => 'transaksi_detail.invoice = transaksi.invoice',
                        'type' => 'left'
                    ]
                ],
                'where' => [
                    'transaksi_detail.id_toko' => $this->data['seller']->id_toko,
                    'transaksi.status_transaksi' =>  'Telah dibayar'
                ],
                'group_by' => 'transaksi_detail.id_produk',
                'order_by' => [
                    'total' =>  'DESC'
                ],
                'limit' => '5'
            ])->result(),
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
        $this->master->template($data);
    }

    public function buka_toko()
    {
        $title = 'Buka Toko';
        $data = [
            'core' => $this->core($title),
            'plugin' => ['flatpickr', 'select2'],
            'get_view' => 'home/v_buka_toko',
            'get_script' => 'home/script_buka_toko'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            $checking = TRUE;

            if ($this->input->post('submit') == 'buka_toko') {
                $check_data = $this->master_model->select_data([
                    'field' => 'nama_toko',
                    'table' => 'toko',
                    'where' => [
                        'LOWER(nama_toko)' => trim(strtolower($this->input->post('nama_toko')))
                    ]
                ])->result();

                if (!empty($check_data)) {
                    $checking = FALSE;
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Nama toko ' . $this->input->post('nama_toko') . ' sudah pernah di gunakan.'
                    ];
                }

                if ($checking == TRUE) {
                    $query = $this->master_model->send_data([
                        'data' => [
                            'nama_toko' => $this->input->post('nama_toko'),
                            'created_date' => date('Y-m-d'),
                            'created_time' => date('H:i:s'),
                            'id_user' => $this->session->userdata('id_customer'),
                            'nama_user' => $this->data['customer']->nama_lengkap,
                            'greating_message' => $this->input->post('greating_message'),
                            'deskripsi_toko' => $this->input->post('deskripsi_toko'),
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
                            'message' => 'Data toko gagal di buat, silahkan coba lagi.',
                        ];
                    } else {
                        $last_id = $this->db->insert_id();

                        $this->master_model->send_data([
                            'where' => [
                                'id_toko' => $last_id
                            ],
                            'data' => [
                                'uid' => uniqid() . 'seller' . $last_id
                            ],
                            'table' => 'toko'
                        ]);

                        $output = [
                            'error' => false,
                            'type' => 'info',
                            'message' => 'Toko sedang di buat, mohon tunggu...',
                            'callback' => base_url()
                        ];

                        $this->alert_popup([
                            'name' => 'success',
                            'swal' => [
                                'title' => 'Toko berhasil di buat, selamat datang di Jaja.id!',
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
