<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Transaction extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $checking = true;

        if (!$this->post()) {
            $checking = false;
            $response = [
                'result' => [
                    'status' => [
                        'code' => SELF::HTTP_BAD_REQUEST,
                        'message' => 'bad request'
                    ],
                    'data' => null
                ],
                'status' => SELF::HTTP_OK
            ];
        } else {
            if (!empty($this->post('vehicle_data')['id'])) {
                $parsing['vehicle'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'vehicle',
                    'where' => [
                        'id' => decrypt_text($this->post('vehicle_data')['id'])
                    ]
                ])->row();
                if (empty($parsing['vehicle'])) {
                    $checking = false;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'data not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if (!empty($this->post('vehicle_data')['children_id'])) {
                $parsing['vehicle_children'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'vehicle_children',
                    'where' => [
                        'id' => decrypt_text($this->post('vehicle_data')['children_id'])
                    ]
                ])->row();
                if (empty($parsing['vehicle_children'])) {
                    $checking = false;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'data not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if (!empty($this->post('service_data'))) {
                $parsing['service'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'service',
                    'where' => [
                        'id' => decrypt_text($this->post('service_data'))
                    ]
                ])->row();
                if (empty($parsing['service'])) {
                    $checking = false;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'data not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if (!empty($this->post('item_data'))) {
                foreach ($this->post('item_data') as $check_item_data) {
                    if (empty($this->api_model->select_data([
                        'field' => '*',
                        'table' => 'item',
                        'where' => [
                            'id' => decrypt_text($check_item_data['id'])
                        ]
                    ])->row())) {
                        $checking = false;
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_NOT_FOUND,
                                    'message' => 'data not found'
                                ],
                                'data' => null
                            ],
                            'status' => SELF::HTTP_OK
                        ];
                    }
                }
            }

            if ($checking == true) {
                $this->db->trans_start();

                $get_no = $this->api_model->select_data([
                    'field' => 'substr(MAX(queue), 2, 1) AS no',
                    'table' => 'transaction',
                    'where' => [
                        'YEAR(created_at)' => date('Y'),
                        'MONTH(created_at)' => date('m'),
                        'DAY(created_at)' => date('d')
                    ]
                ])->row()->no;

                if (!empty($get_no)) {
                    $no = $get_no + 1;
                } else {
                    $no = $get_no + 1;
                }

                $this->api_model->send_data([
                    'data' => [
                        'cashier_id' => decrypt_text($this->post('cashier_id')),
                        'queue' => 'F' . $no,
                        'customer_name' => $this->post('customer_name'),
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    'table' => 'transaction'
                ]);

                $last_transaction_id = $this->db->insert_id();

                $total_qty = [];
                $total_price = [];
                foreach ($this->post('item_data') as $key_item_data) {
                    $parsing['item'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'item',
                        'where' => [
                            'id' => decrypt_text($key_item_data['id'])
                        ]
                    ])->row();

                    $total_qty[] = $key_item_data['qty'];
                    $total_price[] = $parsing['item']->price * $key_item_data['qty'];

                    $this->api_model->send_data([
                        'where' => [
                            'id' => decrypt_text($key_item_data['id'])
                        ],
                        'data' => [
                            'stock' => $parsing['item']->stock - $key_item_data['qty'],
                            'updated_at' => date('Y-m-d H:i:s')
                        ],
                        'table' => 'item'
                    ]);

                    $parsing['item'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'item',
                        'where' => [
                            'id' => decrypt_text($key_item_data['id'])
                        ]
                    ])->row();

                    $item['name'] = $parsing['item']->name;
                    $item['price'] = $parsing['item']->price;
                    $item['price_currency_format'] = rupiah($parsing['item']->price);
                    $item['stock'] = $parsing['item']->stock;
                    $item['created_at'] = $parsing['item']->created_at;
                    $item['updated_at'] = $parsing['item']->updated_at;
                    $item['in_active'] = boolval($parsing['item']->in_active);

                    if (!empty($parsing['item']->vehicle_id)) {
                        $parsing['vehicle'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle',
                            'where' => [
                                'id' => $parsing['item']->vehicle_id
                            ]
                        ])->row();
                        $vehicle['name'] = $parsing['vehicle']->name;
                        $vehicle['created_at'] = $parsing['vehicle']->created_at;
                        $vehicle['updated_at'] = $parsing['vehicle']->updated_at;
                        $vehicle['in_active'] = boolval($parsing['vehicle']->in_active);

                        if (!empty($parsing['item']->vehicle_children_id)) {
                            $parsing['vehicle_children'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'vehicle_children',
                                'where' => [
                                    'id' => $parsing['item']->vehicle_children_id
                                ]
                            ])->row();
                            $vehicle_children['name'] = $parsing['vehicle_children']->name;
                            $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                            $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                            $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                            $vehicle['children'] = $vehicle_children;
                        } else {
                            $vehicle['children'] = null;
                        }

                        $item['vehicle'] = $vehicle;
                    } else {
                        $item['vehicle'] = null;
                    }

                    $this->api_model->send_data([
                        'data' => [
                            'transaction_id' => $last_transaction_id,
                            'qty' => $key_item_data['qty'],
                            'price' => $parsing['item']->price * $key_item_data['qty'],
                            'item_data' => json_encode($item)
                        ],
                        'table' => 'transaction_detail'
                    ]);
                }

                if (!empty($this->post('vehicle_data')['id'])) {
                    $vehicle['name'] = $parsing['vehicle']->name;
                    $vehicle['created_at'] = $parsing['vehicle']->created_at;
                    $vehicle['updated_at'] = $parsing['vehicle']->updated_at;
                    $vehicle['in_active'] = boolval($parsing['vehicle']->in_active);

                    if (!empty($this->post('vehicle_data')['children_id'])) {
                        $vehicle_children['name'] = $parsing['vehicle_children']->name;
                        $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                        $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                        $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                        $vehicle['children'] = $vehicle_children;
                    } else {
                        $vehicle['children'] = null;
                    }

                    $vehicle_data = $vehicle;
                } else {
                    $vehicle_data = null;
                }

                $this->api_model->send_data([
                    'data' => [
                        'transaction_id' => $last_transaction_id,
                        'vehicle_data' => json_encode($vehicle_data)
                    ],
                    'table' => 'transaction_detail'
                ]);

                if (!empty($parsing['service'])) {
                    $service['id'] = encrypt_text($parsing['service']->id);
                    $service['name'] = $parsing['service']->name;
                    $service['price'] = $parsing['service']->price;
                    $service['price_currency_format'] = rupiah($parsing['service']->price);
                    $service['created_at'] = $parsing['service']->created_at;
                    $service['updated_at'] = $parsing['service']->updated_at;

                    if (!empty($vehicle_data)) {
                        $service['vehicle'] = $vehicle_data;
                    } else {
                        $service['vehicle'] = null;
                    }

                    $service_data = $service;
                } else {
                    $service_data = null;
                }

                $total_price[] = $parsing['service']->price;
                $this->api_model->send_data([
                    'data' => [
                        'transaction_id' => $last_transaction_id,
                        'service_data' => json_encode($service_data),
                        'price' => $parsing['service']->price
                    ],
                    'table' => 'transaction_detail'
                ]);

                $this->api_model->send_data([
                    'where' => [
                        'id' => $last_transaction_id
                    ],
                    'data' => [
                        'invoice' => 'INV-T' . date('ymd') . rand(0001, 9999) . $last_transaction_id,
                        'total_price' => array_sum($total_price)
                    ],
                    'table' => 'transaction'
                ]);

                $this->db->trans_complete();

                if ($this->db->trans_status() === false) {
                    $db_error = $this->db->error();
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'add data failed',
                                'from_system' => 'Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                } else {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_CREATED,
                                'message' => 'add data success'
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    $parsing['transaction'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'transaction',
                        'where' => [
                            'id' => $last_transaction_id
                        ]
                    ])->row();

                    $data['id'] = encrypt_text($parsing['transaction']->id);
                    $data['customer_name'] = $parsing['transaction']->customer_name;
                    $data['invoice'] = $parsing['transaction']->invoice;
                    $data['queue'] = $parsing['transaction']->queue;
                    $data['total_price'] = $parsing['transaction']->total_price;
                    $data['total_price_currency_format'] = rupiah($parsing['transaction']->total_price);
                    $data['status'] = $parsing['transaction']->status;
                    $data['created_at'] = $parsing['transaction']->created_at;

                    $parsing['cashier'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'cashier',
                        'where' => [
                            'id' => $parsing['transaction']->cashier_id
                        ]
                    ])->row();
                    $cashier['id'] = encrypt_text($parsing['cashier']->id);
                    $cashier['name'] = $parsing['cashier']->name;
                    $cashier['birth_date'] = $parsing['cashier']->birth_date;
                    $cashier['phone_number'] = $parsing['cashier']->phone_number;
                    $cashier['username'] = $parsing['cashier']->username;
                    $cashier['gender'] = $parsing['cashier']->gender;
                    $cashier['address'] = $parsing['cashier']->address;
                    $cashier['created_at'] = $parsing['cashier']->created_at;
                    $cashier['updated_at'] = $parsing['cashier']->updated_at;

                    $data['cashier'] = $cashier;

                    $parsing['transaction_detail'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'transaction_detail',
                        'where' => [
                            'transaction_id' => $parsing['transaction']->id
                        ]
                    ])->result();
                    if (!empty($parsing['transaction_detail'])) {
                        $data['detail'] = [];
                        foreach ($parsing['transaction_detail'] as $key_transaction_detail) {
                            $detail['id'] = encrypt_text($key_transaction_detail->id);
                            $detail['qty'] = $key_transaction_detail->qty;
                            $detail['price'] = $key_transaction_detail->price;
                            $detail['price_currency_format'] = rupiah($key_transaction_detail->price);
                            $detail['vehicle'] = json_decode($key_transaction_detail->vehicle_data);
                            $detail['service'] = json_decode($key_transaction_detail->service_data);
                            $detail['item'] = json_decode($key_transaction_detail->item_data);

                            $data['detail'][] = $detail;
                        }
                    } else {
                        $data['detail'] = null;
                    }

                    $response['result']['data'] = $data;
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function index_get($id = null)
    {
        $checking = true;

        if (!empty($id)) {
            $parsing['transaction'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'transaction',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();

            if (empty($parsing['transaction'])) {
                $checking = false;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'data not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if ($checking == true) {
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_OK,
                            'message' => 'get data success'
                        ],
                        'data' => []
                    ],
                    'status' => SELF::HTTP_OK
                ];

                $data['id'] = encrypt_text($parsing['transaction']->id);
                $data['customer_name'] = $parsing['transaction']->customer_name;
                $data['invoice'] = $parsing['transaction']->invoice;
                $data['queue'] = $parsing['transaction']->queue;
                $data['total_price'] = $parsing['transaction']->total_price;
                $data['total_price_currency_format'] = rupiah($parsing['transaction']->total_price);
                $data['status'] = $parsing['transaction']->status;
                $data['created_at'] = $parsing['transaction']->created_at;

                $parsing['cashier'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'cashier',
                    'where' => [
                        'id' => $parsing['transaction']->cashier_id
                    ]
                ])->row();
                $cashier['id'] = encrypt_text($parsing['cashier']->id);
                $cashier['name'] = $parsing['cashier']->name;
                $cashier['birth_date'] = $parsing['cashier']->birth_date;
                $cashier['phone_number'] = $parsing['cashier']->phone_number;
                $cashier['username'] = $parsing['cashier']->username;
                $cashier['gender'] = $parsing['cashier']->gender;
                $cashier['address'] = $parsing['cashier']->address;
                $cashier['created_at'] = $parsing['cashier']->created_at;
                $cashier['updated_at'] = $parsing['cashier']->updated_at;

                $data['cashier'] = $cashier;

                $parsing['transaction_detail'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'transaction_detail',
                    'where' => [
                        'transaction_id' => $parsing['transaction']->id
                    ]
                ])->result();
                if (!empty($parsing['transaction_detail'])) {
                    $data['detail'] = [];
                    foreach ($parsing['transaction_detail'] as $key_transaction_detail) {
                        $detail['id'] = encrypt_text($key_transaction_detail->id);
                        $detail['qty'] = $key_transaction_detail->qty;
                        $detail['price'] = $key_transaction_detail->price;
                        $detail['price_currency_format'] = rupiah($key_transaction_detail->price);
                        $detail['vehicle'] = json_decode($key_transaction_detail->vehicle_data);
                        $detail['service'] = json_decode($key_transaction_detail->service_data);
                        $detail['item'] = json_decode($key_transaction_detail->item_data);

                        $data['detail'][] = $detail;
                    }
                } else {
                    $data['detail'] = null;
                }

                $response['result']['data'] = $data;
            }
        } else {
            if ($this->get('page') != null && $this->get('page') != null) {
                if ($this->get('page') < 1 || $this->get('limit') < 1) {
                    $checking = false;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'value must more than 1'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($checking == true) {
                $param['transaction']['field'] = '*';
                $param['transaction']['table'] = 'transaction';
                if ($this->get('page') != null && $this->get('page') != null) {
                    $param['transaction']['limit'] = [
                        $this->get('limit') => ($this->get('page') - 1) * $this->get('limit')
                    ];
                }
                $param['transaction']['order_by'] = [
                    'created_at' => 'desc'
                ];
                $parsing['transaction'] = $this->api_model->select_data($param['transaction'])->result();

                $total_record = $this->api_model->count_all_data($param['transaction']);

                if ($this->get('page') != null && $this->get('page') != null) {
                    $limit = (int) $this->get('limit');
                    $current_page = (int) $this->get('page');
                    $total_page = ceil($total_record / $limit);
                } else {
                    $limit = null;
                    $current_page = null;
                    $total_page = null;
                }

                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_OK,
                            'message' => 'get data success'
                        ],
                        'meta' => [
                            'record' => [
                                'limit' => $limit,
                                'total' => $total_record
                            ],
                            'page' => [
                                'current' => $current_page,
                                'total' => $total_page
                            ]
                        ],
                        'data' => []
                    ],
                    'status' => SELF::HTTP_OK
                ];

                if (!empty($parsing['transaction'])) {
                    foreach ($parsing['transaction'] as $key_transaction) {
                        $data['id'] = encrypt_text($key_transaction->id);
                        $data['customer_name'] = $key_transaction->customer_name;
                        $data['invoice'] = $key_transaction->invoice;
                        $data['queue'] = $key_transaction->queue;
                        $data['total_price'] = $key_transaction->total_price;
                        $data['total_price_currency_format'] = rupiah($key_transaction->total_price);
                        $data['status'] = $key_transaction->status;
                        $data['created_at'] = $key_transaction->created_at;

                        $parsing['cashier'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'cashier',
                            'where' => [
                                'id' => $key_transaction->cashier_id
                            ]
                        ])->row();
                        $cashier['id'] = encrypt_text($parsing['cashier']->id);
                        $cashier['name'] = $parsing['cashier']->name;
                        $cashier['birth_date'] = $parsing['cashier']->birth_date;
                        $cashier['phone_number'] = $parsing['cashier']->phone_number;
                        $cashier['username'] = $parsing['cashier']->username;
                        $cashier['gender'] = $parsing['cashier']->gender;
                        $cashier['address'] = $parsing['cashier']->address;
                        $cashier['created_at'] = $parsing['cashier']->created_at;
                        $cashier['updated_at'] = $parsing['cashier']->updated_at;

                        $data['cashier'] = $cashier;

                        $parsing['transaction_detail'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'transaction_detail',
                            'where' => [
                                'transaction_id' => $key_transaction->id
                            ]
                        ])->result();
                        if (!empty($parsing['transaction_detail'])) {
                            $data['detail'] = [];
                            foreach ($parsing['transaction_detail'] as $key_transaction_detail) {
                                $detail['id'] = encrypt_text($key_transaction_detail->id);
                                $detail['qty'] = $key_transaction_detail->qty;
                                $detail['price'] = $key_transaction_detail->price;
                                $detail['price_currency_format'] = rupiah($key_transaction_detail->price);
                                $detail['vehicle'] = json_decode($key_transaction_detail->vehicle_data);
                                $detail['service'] = json_decode($key_transaction_detail->service_data);
                                $detail['item'] = json_decode($key_transaction_detail->item_data);

                                $data['detail'][] = $detail;
                            }
                        } else {
                            $data['detail'] = null;
                        }

                        $response['result']['data'][] = $data;
                    }
                } else {
                    $response['result']['data'] = null;
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function index_delete($id = null)
    {
        $checking = true;

        if (empty($id)) {
            $response = [
                'result' => [
                    'status' => [
                        'code' => SELF::HTTP_BAD_REQUEST,
                        'message' => 'bad request'
                    ],
                    'data' => null
                ],
                'status' => SELF::HTTP_OK
            ];
        } else {
            $check['transaction'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'transaction',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();
            if (empty($check['transaction'])) {
                $checking = false;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'data not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            } else {
                $parsing['transaction_detail'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'transaction_detail',
                    'where' => [
                        'transaction_id' => decrypt_text($id)
                    ]
                ])->result();
            }

            if ($checking == true) {
                $query = $this->api_model->delete_data([
                    'where' => [
                        'id' => decrypt_text($id)
                    ],
                    'table' => 'transaction'
                ]);

                if ($query['error'] == true) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'delete data failed',
                                'from_system' => $query['system']
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                } else {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_OK,
                                'message' => 'delete data success'
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    $data['id'] = encrypt_text($check['transaction']->id);
                    $data['customer_name'] = $check['transaction']->customer_name;
                    $data['invoice'] = $check['transaction']->invoice;
                    $data['queue'] = $check['transaction']->queue;
                    $data['total_price'] = $check['transaction']->total_price;
                    $data['total_price_currency_format'] = rupiah($check['transaction']->total_price);
                    $data['status'] = $check['transaction']->status;
                    $data['created_at'] = $check['transaction']->created_at;

                    $parsing['cashier'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'cashier',
                        'where' => [
                            'id' => $check['transaction']->cashier_id
                        ]
                    ])->row();
                    $cashier['id'] = encrypt_text($parsing['cashier']->id);
                    $cashier['name'] = $parsing['cashier']->name;
                    $cashier['birth_date'] = $parsing['cashier']->birth_date;
                    $cashier['phone_number'] = $parsing['cashier']->phone_number;
                    $cashier['username'] = $parsing['cashier']->username;
                    $cashier['gender'] = $parsing['cashier']->gender;
                    $cashier['address'] = $parsing['cashier']->address;
                    $cashier['created_at'] = $parsing['cashier']->created_at;
                    $cashier['updated_at'] = $parsing['cashier']->updated_at;

                    $data['cashier'] = $cashier;

                    if (!empty($parsing['transaction_detail'])) {
                        $data['detail'] = [];
                        foreach ($parsing['transaction_detail'] as $key_transaction_detail) {
                            $detail['id'] = encrypt_text($key_transaction_detail->id);
                            $detail['qty'] = $key_transaction_detail->qty;
                            $detail['price'] = $key_transaction_detail->price;
                            $detail['price_currency_format'] = rupiah($key_transaction_detail->price);
                            $detail['vehicle'] = json_decode($key_transaction_detail->vehicle_data);
                            $detail['service'] = json_decode($key_transaction_detail->service_data);
                            $detail['item'] = json_decode($key_transaction_detail->item_data);

                            $data['detail'][] = $detail;
                        }
                    } else {
                        $data['detail'] = null;
                    }

                    $response['result']['data'] = $data;
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function detail_get($id = null)
    {
        $checking = true;

        if (!empty($id)) {
            $parsing['transaction_detail'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'transaction_detail',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();

            if (empty($parsing['transaction_detail'])) {
                $checking = false;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'data not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if ($checking == true) {
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_OK,
                            'message' => 'get data success'
                        ],
                        'data' => []
                    ],
                    'status' => SELF::HTTP_OK
                ];

                $data['id'] = encrypt_text($parsing['transaction_detail']->id);
                $data['qty'] = $parsing['transaction_detail']->qty;
                $data['price'] = $parsing['transaction_detail']->price;
                $data['price_currency_format'] = rupiah($parsing['transaction_detail']->price);
                $data['vehicle'] = json_decode($parsing['transaction_detail']->vehicle_data);
                $data['service'] = json_decode($parsing['transaction_detail']->service_data);
                $data['item'] = json_decode($parsing['transaction_detail']->item_data);

                $response['result']['data'] = $data;
            }
        } else {
            if (empty($this->get('from_parent'))) {
                $checking = false;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_BAD_REQUEST,
                            'message' => 'bad request'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            } else {
                $parsing['transaction'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'transaction',
                    'where' => [
                        'id' => decrypt_text($this->get('from_parent'))
                    ]
                ])->row();
                if (empty($parsing['transaction'])) {
                    $checking = false;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'data not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }

                if ($this->get('page') != null && $this->get('page') != null) {
                    if ($this->get('page') < 1 || $this->get('limit') < 1) {
                        $checking = false;
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_BAD_REQUEST,
                                    'message' => 'value must more than 1'
                                ],
                                'data' => null
                            ],
                            'status' => SELF::HTTP_OK
                        ];
                    }
                }

                if ($checking == true) {
                    $param['transaction_detail']['field'] = '*';
                    $param['transaction_detail']['table'] = 'transaction_detail';
                    $param['transaction_detail']['where'] = [
                        'transaction_id' => $parsing['transaction']->id
                    ];
                    if ($this->get('page') != null && $this->get('page') != null) {
                        $param['transaction_detail']['limit'] = [
                            $this->get('limit') => ($this->get('page') - 1) * $this->get('limit')
                        ];
                    }
                    $parsing['transaction_detail'] = $this->api_model->select_data($param['transaction_detail'])->result();

                    $total_record = $this->api_model->count_all_data($param['transaction_detail']);

                    if ($this->get('page') != null && $this->get('page') != null) {
                        $limit = (int) $this->get('limit');
                        $current_page = (int) $this->get('page');
                        $total_page = ceil($total_record / $limit);
                    } else {
                        $limit = null;
                        $current_page = null;
                        $total_page = null;
                    }

                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_OK,
                                'message' => 'get data success'
                            ],
                            'meta' => [
                                'record' => [
                                    'limit' => $limit,
                                    'total' => $total_record
                                ],
                                'page' => [
                                    'current' => $current_page,
                                    'total' => $total_page
                                ]
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    if (!empty($parsing['transaction_detail'])) {
                        foreach ($parsing['transaction_detail'] as $key_transaction_detail) {
                            $data['id'] = encrypt_text($key_transaction_detail->id);
                            $data['qty'] = $key_transaction_detail->qty;
                            $data['price'] = $key_transaction_detail->price;
                            $data['price_currency_format'] = rupiah($key_transaction_detail->price);
                            $data['vehicle'] = json_decode($key_transaction_detail->vehicle_data);
                            $data['service'] = json_decode($key_transaction_detail->service_data);
                            $data['item'] = json_decode($key_transaction_detail->item_data);

                            $response['result']['data'][] = $data;
                        }
                    } else {
                        $response['result']['data'] = null;
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function payment_put($id = null)
    {
        $checking = true;

        if (empty($id)) {
            $checking = false;
            $response = [
                'result' => [
                    'status' => [
                        'code' => SELF::HTTP_BAD_REQUEST,
                        'message' => 'bad request'
                    ],
                    'data' => null
                ],
                'status' => SELF::HTTP_OK
            ];
        } else {
            $check['transaction'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'transaction',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();
            if (empty($check['transaction'])) {
                $checking = false;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'data not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            } else {
                if ($checking == true) {
                    $query = $this->api_model->send_data([
                        'where' => [
                            'id' => decrypt_text($id)
                        ],
                        'data' => [
                            'status' => 'complete'
                        ],
                        'table' => 'transaction'
                    ]);

                    if ($query['error'] == true) {
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_BAD_REQUEST,
                                    'message' => 'edit data failed',
                                    'from_system' => $query['system']
                                ],
                                'data' => null
                            ],
                            'status' => SELF::HTTP_OK
                        ];
                    } else {
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_OK,
                                    'message' => 'edit data success'
                                ],
                                'data' => []
                            ],
                            'status' => SELF::HTTP_OK
                        ];

                        $parsing['transaction'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'transaction',
                            'where' => [
                                'id' => decrypt_text($id)
                            ]
                        ])->row();

                        $data['id'] = encrypt_text($parsing['transaction']->id);
                        $data['customer_name'] = $parsing['transaction']->customer_name;
                        $data['invoice'] = $parsing['transaction']->invoice;
                        $data['queue'] = $parsing['transaction']->queue;
                        $data['total_price'] = $parsing['transaction']->total_price;
                        $data['total_price_currency_format'] = rupiah($parsing['transaction']->total_price);
                        $data['status'] = $parsing['transaction']->status;
                        $data['created_at'] = $parsing['transaction']->created_at;

                        $parsing['cashier'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'cashier',
                            'where' => [
                                'id' => $parsing['transaction']->cashier_id
                            ]
                        ])->row();
                        $cashier['id'] = encrypt_text($parsing['cashier']->id);
                        $cashier['name'] = $parsing['cashier']->name;
                        $cashier['birth_date'] = $parsing['cashier']->birth_date;
                        $cashier['phone_number'] = $parsing['cashier']->phone_number;
                        $cashier['username'] = $parsing['cashier']->username;
                        $cashier['gender'] = $parsing['cashier']->gender;
                        $cashier['address'] = $parsing['cashier']->address;
                        $cashier['created_at'] = $parsing['cashier']->created_at;
                        $cashier['updated_at'] = $parsing['cashier']->updated_at;

                        $data['cashier'] = $cashier;

                        $parsing['transaction_detail'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'transaction_detail',
                            'where' => [
                                'transaction_id' => $parsing['transaction']->id
                            ]
                        ])->result();
                        if (!empty($parsing['transaction_detail'])) {
                            $data['detail'] = [];
                            foreach ($parsing['transaction_detail'] as $key_transaction_detail) {
                                $detail['id'] = encrypt_text($key_transaction_detail->id);
                                $detail['qty'] = $key_transaction_detail->qty;
                                $detail['price'] = $key_transaction_detail->price;
                                $detail['price_currency_format'] = rupiah($key_transaction_detail->price);
                                $detail['vehicle'] = json_decode($key_transaction_detail->vehicle_data);
                                $detail['service'] = json_decode($key_transaction_detail->service_data);
                                $detail['item'] = json_decode($key_transaction_detail->item_data);

                                $data['detail'][] = $detail;
                            }
                        } else {
                            $data['detail'] = null;
                        }

                        $response['result']['data'] = $data;
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }
}
