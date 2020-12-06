<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dompetku extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth_seller();
    }

    public function index()
    {
        $title = 'Dompetku';
        $data = [
            'core' => $this->core($title),
            'plugin' => ['datatables', 'flatpickr', 'select2', 'magnific-popup'],
            'get_view' => 'dompetku/v_dompetku',
            'get_script' => 'dompetku/script_dompetku',

            'saldo_aktif' => $this->master_model->select_data([
                'field' => 'SUM(transaksi_detail.sub_total) as saldoAktif',
                'table' => 'transaksi_detail',
                'join' => [
                    [
                        'table' => 'resi_data',
                        'on' => 'transaksi_detail.invoice = resi_data.invoice',
                        'type' => 'left'
                    ]
                ],
                'where' => [
                    'transaksi_detail.id_toko' => $this->data['seller']->id_toko,
                    'transaksi_detail.status_pesanan' => 'selesai'
                ]
            ])->row()->saldoAktif,

            'saldo_akanlepas' => $this->master_model->select_data([
                'field' => 'SUM(nominal) as saldoAkanlepas',
                'table' => 'cairkan_dana',
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status' => 'tunggu'
                ]
            ])->row()->saldoAkanlepas,

            'saldo_terlepas' => $this->master_model->select_data([
                'field' => 'SUM(nominal) as saldoTerlepas',
                'table' => 'cairkan_dana',
                'where' => [
                    'id_toko' => $this->data['seller']->id_toko,
                    'status' => 'setujui'
                ]
            ])->row()->saldoTerlepas
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            if ($this->input->post('submit') == 'tambah') {
                $bank = explode(':', $this->input->post('bank'));

                $query = $this->master_model->send_data([
                    'data' => [
                        'name' => $this->input->post('name'),
                        'bank_code' => $bank[0],
                        'bank_name' => $bank[1],
                        'account' => $this->input->post('account'),
                        'created_time' => date('H:i:s'),
                        'created_date' => date('Y-m-d'),
                        'id_toko' => $this->data['seller']->id_toko
                    ],
                    'table' => 'toko_bank'
                ]);

                if ($query == false) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data Rekening Toko gagal di tambah.',
                    ];
                } else {
                    $last_id = $this->db->insert_id();
                    $name = explode(' ', $this->input->post('name'));

                    $this->master_model->send_data([
                        'where' => [
                            'id_data' => $last_id
                        ],
                        'data' => [
                            'alias_name' => strtolower($name[0]) . $bank[0] . $last_id
                        ],
                        'table' => 'toko_bank'
                    ]);

                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data Rekening Toko berhasil di tambahkan.',
                    ];
                }
            } elseif ($this->input->post('submit') == 'edit') {
                $bank = explode(':', $this->input->post('bank'));

                $query = $this->master_model->send_data([
                    'where' => [
                        'id_data' => decrypt_text($this->input->post('id_data'))
                    ],
                    'data' => [
                        'name' => $this->input->post('name'),
                        'bank_code' => $bank[0],
                        'bank_name' => $bank[1],
                        'account' => $this->input->post('account')
                    ],
                    'table' => 'toko_bank'
                ]);

                if ($query == false) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data Rekening Toko gagal di edit.',
                    ];
                } else {
                    $last_id = decrypt_text($this->input->post('id_data'));
                    $name = explode(' ', $this->input->post('name'));

                    $this->master_model->send_data([
                        'where' => [
                            'id_data' => $last_id
                        ],
                        'data' => [
                            'alias_name' => strtolower($name[0]) . $bank[0] . $last_id,
                        ],
                        'table' => 'toko_bank'
                    ]);

                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data Rekening Toko berhasil di edit.',
                    ];
                }
            } elseif ($this->input->post('submit') == 'delete') {
                $query = $this->master_model->delete_data([
                    'where' => [
                        'id_data' => decrypt_text($this->input->post('id_data'))
                    ],
                    'table' => 'toko_bank'
                ]);

                if ($query == FALSE) {
                    $output = [
                        'error' => true,
                        'type' => 'error',
                        'message' => 'Data rekening gagal di hapus.',
                    ];
                } else {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Data rekening berhasil di hapus.',
                    ];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }
}
