<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Datatable extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cashier_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'name', 'birth_date', 'phone_number', 'username', 'gender', 'created_at', 'updated_at'
        ];
        $param['column_order'] = [
            null, 'name', 'phone_number', 'gender', 'created_at', 'updated_at', null
        ];
        $param['field'] = '*';
        $param['table'] = 'cashier';

        $param['order_by'] = [
            'name' => 'asc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];

                $column[] = $no;
                $column[] = $key->name;
                $column[] = $key->username;
                $column[] = $key->phone_number;
                $column[] = ($key->gender == 'male') ? 'Laki-Laki' : 'Perempuan';
                $column[] = date_indo(date('d-m-Y', strtotime($key->birth_date)));
                $column[] = '
                <a href="' . base_url() . 'admin/cashier/detail/' . encrypt_text($key->id) . '" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data"><i class="fas fa-info"></i></a>
                <a href="' . base_url() . 'admin/cashier/form/' . encrypt_text($key->id) . '" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" title="Edit Data"><i class="fas fa-edit"></i></a>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Data" onclick="show_modal({ modal: ' . "'delete'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-trash-alt"></i></button>
				';

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function vehicle_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'name', 'created_at', 'updated_at', 'in_active'
        ];
        $param['column_order'] = [
            null, 'name', 'created_at', 'updated_at', 'in_active', null
        ];
        $param['field'] = '*';
        $param['table'] = 'vehicle';

        if ($this->input->post('params')['in_active'] != 'all') {
            $param['where'] = [
                'in_active' => $this->input->post('params')['in_active']
            ];
        }

        $param['order_by'] = [
            'name' => 'asc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];

                $created_at = explode(' ', $key->created_at);
                $updated_at = (!empty($key->updated_at)) ? explode(' ', $key->updated_at) : null;

                if ($key->in_active != 0) {
                    $in_active = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="vehicle_' . encrypt_text($key->id) . '" onclick="show_modal({ modal: ' . "'not_active'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })" checked><label class="custom-control-label" for="vehicle_' . encrypt_text($key->id) . '"></label></div>';
                } else {
                    $in_active = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="vehicle_' . encrypt_text($key->id) . '" onclick="show_modal({ modal: ' . "'active'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><label class="custom-control-label" for="vehicle_' . encrypt_text($key->id) . '"></label></div>';
                }

                $column[] = $no;
                $column[] = $key->name;
                $column[] = date_indo(date('d-m-Y', strtotime($created_at[0]))) . '<br>' . $created_at[1];
                $column[] = (!empty($updated_at)) ? date_indo(date('d-m-Y', strtotime($updated_at[0]))) . '<br>' . $updated_at[1] : 'Belum Update';
                $column[] = $in_active;
                $column[] = '
                <a href="' . base_url() . 'admin/vehicle/detail/' . encrypt_text($key->id) . '" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data"><i class="fas fa-info"></i></a>
                <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" title="Edit Data" onclick="show_modal({ modal: ' . "'edit'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Data" onclick="show_modal({ modal: ' . "'delete'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-trash-alt"></i></button>
				';

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function vehicle_children_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'name', 'created_at', 'updated_at', 'in_active'
        ];
        $param['column_order'] = [
            null, 'name', 'created_at', 'updated_at', 'in_active', null
        ];
        $param['field'] = '*';
        $param['table'] = 'vehicle_children';

        $arr_default = [
            'vehicle_id' => decrypt_text($this->input->post('params')['vehicle_id'])
        ];

        if ($this->input->post('params')['in_active'] != 'all') {
            $arr_in_active = [
                'in_active' => $this->input->post('params')['in_active']
            ];
        } else {
            $arr_in_active = [];
        }

        $param['where'] = array_merge($arr_default, $arr_in_active);

        $param['order_by'] = [
            'name' => 'asc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];

                $created_at = explode(' ', $key->created_at);
                $updated_at = (!empty($key->updated_at)) ? explode(' ', $key->updated_at) : null;

                if ($key->in_active != 0) {
                    $in_active = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="vehicle_children_' . encrypt_text($key->id) . '" onclick="show_modal_child({ modal: ' . "'not_active'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })" checked><label class="custom-control-label" for="vehicle_children_' . encrypt_text($key->id) . '"></label></div>';
                } else {
                    $in_active = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="vehicle_children_' . encrypt_text($key->id) . '" onclick="show_modal_child({ modal: ' . "'active'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><label class="custom-control-label" for="vehicle_children_' . encrypt_text($key->id) . '"></label></div>';
                }

                $column[] = $no;
                $column[] = $key->name;
                $column[] = date_indo(date('d-m-Y', strtotime($created_at[0]))) . '<br>' . $created_at[1];
                $column[] = (!empty($updated_at)) ? date_indo(date('d-m-Y', strtotime($updated_at[0]))) . '<br>' . $updated_at[1] : 'Belum Update';
                $column[] = $in_active;
                $column[] = '
                <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" title="Edit Data" onclick="show_modal_child({ modal: ' . "'edit'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Data" onclick="show_modal_child({ modal: ' . "'delete'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-trash-alt"></i></button>
				';

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function item_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'vehicle.name', 'vehicle_children.name', 'item.name', 'item.price', 'item.stock', 'item.created_at', 'item.updated_at', 'item.in_active'
        ];
        $param['column_order'] = [
            null, 'item.name', 'vehicle.name', 'item.price', 'item.stock', 'item.in_active', null
        ];
        $param['field'] = 'item.*, vehicle.name as vehicle_name, vehicle_children.name as vehicle_children_name';
        $param['table'] = 'item';

        if ($this->input->post('params')['in_active'] != 'all') {
            $param['where'] = [
                'item.in_active' => $this->input->post('params')['in_active']
            ];
        }

        $param['join'] = [
            [
                'table' => 'vehicle',
                'on' => 'vehicle.id = item.vehicle_id',
                'type' => 'left'
            ],
            [
                'table' => 'vehicle_children',
                'on' => 'vehicle_children.id = item.vehicle_children_id',
                'type' => 'left'
            ]
        ];

        $param['order_by'] = [
            'item.name' => 'asc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];
                $jenis = '';

                if (!empty($this->core['admin'])) {
                    $disabled = '';
                } else {
                    $disabled = 'disabled';
                }

                if ($key->in_active != 0) {
                    $in_active = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="item_' . encrypt_text($key->id) . '" onclick="show_modal({ modal: ' . "'not_active'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })" checked ' . $disabled . '><label class="custom-control-label" for="item_' . encrypt_text($key->id) . '"></label></div>';
                } else {
                    $in_active = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="item_' . encrypt_text($key->id) . '" onclick="show_modal({ modal: ' . "'active'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })" ' . $disabled . '><label class="custom-control-label" for="item_' . encrypt_text($key->id) . '"></label></div>';
                }

                if (!empty($key->vehicle_id)) {
                    $jenis .= 'Kendaraan : ' . $key->vehicle_name;

                    if (!empty($key->vehicle_children_id)) {
                        $jenis .= '<br>Detail : ' . $key->vehicle_children_name;
                    }
                } else {
                    $jenis = 'Umum';
                }

                $column[] = $no;
                $column[] = $key->name;
                $column[] = $jenis;
                $column[] = rupiah($key->price);
                $column[] = $key->stock;
                $column[] = $in_active;

                if (!empty($this->core['admin'])) {
                    $column[] = '
                    <a href="' . base_url() . 'admin/item/detail/' . encrypt_text($key->id) . '" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data"><i class="fas fa-info"></i></a>
                    <a href="' . base_url() . 'admin/item/form/' . encrypt_text($key->id) . '" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" title="Edit Data"><i class="fas fa-edit"></i></a>
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Data" onclick="show_modal({ modal: ' . "'delete'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-trash-alt"></i></button>
                    ';
                } else {
                    $column[] = '
                    <a href="' . base_url() . 'cashier/item/detail/' . encrypt_text($key->id) . '" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data"><i class="fas fa-info"></i></a>
                    ';
                }

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function service_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'vehicle.name', 'vehicle_children.name', 'service.name', 'service.price', 'service.created_at', 'service.updated_at',
        ];
        $param['column_order'] = [
            null, 'service.name', 'vehicle.name', 'service.price', null
        ];
        $param['field'] = 'service.*, vehicle.name as vehicle_name, vehicle_children.name as vehicle_children_name';
        $param['table'] = 'service';

        $param['join'] = [
            [
                'table' => 'vehicle',
                'on' => 'vehicle.id = service.vehicle_id',
                'type' => 'left'
            ],
            [
                'table' => 'vehicle_children',
                'on' => 'vehicle_children.id = service.vehicle_children_id',
                'type' => 'left'
            ]
        ];

        $param['order_by'] = [
            'service.name' => 'asc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];
                $jenis = '';

                $created_at = explode(' ', $key->created_at);
                $updated_at = (!empty($key->updated_at)) ? explode(' ', $key->updated_at) : null;

                if (!empty($key->vehicle_id)) {
                    $jenis .= 'Kendaraan : ' . $key->vehicle_name;

                    if (!empty($key->vehicle_children_id)) {
                        $jenis .= '<br>Detail : ' . $key->vehicle_children_name;
                    }
                } else {
                    $jenis = 'Umum';
                }

                $column[] = $no;
                $column[] = $key->name;
                $column[] = $jenis;
                $column[] = rupiah($key->price);
                $column[] = date_indo(date('d-m-Y', strtotime($created_at[0]))) . '<br>' . $created_at[1];
                $column[] = (!empty($updated_at)) ? date_indo(date('d-m-Y', strtotime($updated_at[0]))) . '<br>' . $updated_at[1] : 'Belum Update';
                $column[] = '
                <a href="' . base_url() . 'admin/service/detail/' . encrypt_text($key->id) . '" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data"><i class="fas fa-info"></i></a>
                <a href="' . base_url() . 'admin/service/form/' . encrypt_text($key->id) . '" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" title="Edit Data"><i class="fas fa-edit"></i></a>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Data" onclick="show_modal({ modal: ' . "'delete'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-trash-alt"></i></button>
				';

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function purchase_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'invoice', 'supplier_name', 'total_price', 'created_at'
        ];
        $param['column_order'] = [
            null, 'invoice', 'supplier_name', 'total_price', null, 'created_at', null
        ];
        $param['field'] = '*';
        $param['table'] = 'purchase';

        $param['order_by'] = [
            'created_at' => 'desc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];

                $created_at = explode(' ', $key->created_at);
                $count_detail = $this->api_model->count_all_data([
                    'where' => [
                        'purchase_id' => $key->id
                    ],
                    'table' => 'purchase_detail'
                ]);

                $column[] = $no;
                $column[] = $key->invoice;
                $column[] = $key->supplier_name;
                $column[] = $key->total_qty;
                $column[] = rupiah($key->total_price);
                $column[] = '<a href="javascript:;" class="text-blue-href" onclick="show_modal({ modal: ' . "'detail'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })">' . $count_detail . ' Barang</a>';
                $column[] = date_indo(date('d-m-Y', strtotime($created_at[0]))) . '<br>' . $created_at[1];

                if (!empty($this->core['admin'])) {
                    $column[] = '
                    <a href="' . base_url() . 'admin/purchase/detail/' . encrypt_text($key->id) . '" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data"><i class="fas fa-info"></i></a>
                    ';
                } else {
                    $column[] = '
                    <a href="' . base_url() . 'cashier/purchase/detail/' . encrypt_text($key->id) . '" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data"><i class="fas fa-info"></i></a>
                    ';
                }

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function purchase_detail_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'purchase_id', 'item_data', 'qty', 'price'
        ];
        $param['column_order'] = [
            null, 'item_data', 'qty', 'price'
        ];
        $param['field'] = '*';
        $param['table'] = 'purchase_detail';

        $param['where'] = [
            'purchase_id' => decrypt_text($this->input->post('params')['purchase_id'])
        ];

        $param['order_by'] = [
            'qty' => 'desc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];

                $jenis = '';

                $item_data = json_decode($key->item_data, true);

                if (!empty($item_data['vehicle'])) {
                    $jenis .= '<br> - Kendaraan : ' . $item_data['vehicle']['name'];

                    if (!empty($item_data['vehicle']['children'])) {
                        $jenis .= '<br> - Detail : ' . $item_data['vehicle']['children']['name'];
                    }
                } else {
                    $jenis = 'Umum';
                }

                $column[] = $no;
                $column[] = '
                Nama : ' . $item_data['name'] . '<br>
                Jenis : ' . $jenis . '<br>
                Harga : ' . $item_data['price_currency_format'] . '<br>
                Stok Tersedia : ' . $item_data['stock'];
                $column[] = $key->qty;
                $column[] = rupiah($key->price);

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function transaction_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'cashier.name', 'transaction.invoice', 'transaction.queue', 'transaction.customer_name', 'transaction.total_price', 'transaction.total_payment', 'transaction.total_change', 'transaction.status', 'transaction.created_at'
        ];
        $param['column_order'] = [
            null, 'transaction.invoice', 'cashier.name', 'transaction.queue', 'transaction.total_price', 'transaction.total_payment', null, 'transaction.status', null
        ];
        $param['field'] = 'transaction.*, cashier.name as cashier_name';
        $param['table'] = 'transaction';

        $param['join'] = [
            [
                'table' => 'cashier',
                'on' => 'cashier.id = transaction.cashier_id',
                'type' => 'inner'
            ]
        ];

        $param['order_by'] = [
            'transaction.created_at' => 'desc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];
                $service = '';

                $count_detail = $this->api_model->count_all_data([
                    'where' => [
                        'transaction_id' => $key->id,
                        'item_data !=' => null
                    ],
                    'table' => 'transaction_detail'
                ]);

                $parsing['transaction_detail'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'transaction_detail',
                    'where' => [
                        'transaction_id' => $key->id,
                        'service_data !=' => null
                    ]
                ])->row();
                if (!empty($parsing['transaction_detail'])) {
                    $service = json_decode($parsing['transaction_detail']->service_data, true)['name'] . '<br>';
                } else {
                    $service = '';
                }

                if ($key->status == 'waiting') {
                    $status = '<span class="badge badge-soft-secondary">Menunggu</span>';
                } else {
                    $status = '<span class="badge badge-success">Selesai</span>';
                }

                $column[] = $no;
                $column[] = $key->invoice;
                $column[] = $key->cashier_name;
                $column[] = $key->queue . ' - ' . $key->customer_name;
                $column[] = rupiah($key->total_price);
                $column[] = '
                ' . $service . '
                <a href="javascript:;" class="text-blue-href" onclick="show_modal({ modal: ' . "'detail'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })">' . $count_detail . ' Barang</a>';
                $column[] = $status;

                if (!empty($this->core['admin'])) {
                } else {
                    $column[] = '
                    <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" title="Pembayaran" onclick="show_modal({ modal: ' . "'payment'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-wallet"></i></button>
                    ';
                }

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function transaction_detail_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'transaction_id', 'item_data', 'qty', 'price'
        ];
        $param['column_order'] = [
            null, 'item_data', 'qty', 'price'
        ];
        $param['field'] = '*';
        $param['table'] = 'transaction_detail';

        $param['where'] = [
            'transaction_id' => decrypt_text($this->input->post('params')['transaction_id']),
            'item_data !=' => null
        ];

        $param['order_by'] = [
            'qty' => 'desc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];

                $jenis = '';

                $item_data = json_decode($key->item_data, true);

                if (!empty($item_data['vehicle'])) {
                    $jenis .= '<br> - Kendaraan : ' . $item_data['vehicle']['name'];

                    if (!empty($item_data['vehicle']['children'])) {
                        $jenis .= '<br> - Detail : ' . $item_data['vehicle']['children']['name'];
                    }
                } else {
                    $jenis = 'Umum';
                }

                $column[] = $no;
                $column[] = '
                Nama : ' . $item_data['name'] . '<br>
                Jenis : ' . $jenis . '<br>
                Harga : ' . $item_data['price_currency_format'] . '<br>
                Stok Tersedia : ' . $item_data['stock'];
                $column[] = $key->qty;
                $column[] = rupiah($key->price);

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }
}
