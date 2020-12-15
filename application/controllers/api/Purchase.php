<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Purchase extends REST_Controller
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

            if ($checking == true) {
                $this->db->trans_start();

                $this->api_model->send_data([
                    'data' => [
                        'supplier_name' => $this->post('supplier_name'),
                        'total_price' => clean_rupiah($this->post('total_price')),
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    'table' => 'purchase'
                ]);

                $last_purchase_id = $this->db->insert_id();

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
                            'stock' => $parsing['item']->stock + $key_item_data['qty'],
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
                            'purchase_id' => $last_purchase_id,
                            'qty' => $key_item_data['qty'],
                            'price' => $parsing['item']->price * $key_item_data['qty'],
                            'item_data' => json_encode($item)
                        ],
                        'table' => 'purchase_detail'
                    ]);
                }

                $this->api_model->send_data([
                    'where' => [
                        'id' => $last_purchase_id
                    ],
                    'data' => [
                        'invoice' => 'INV-P' . date('ymd') . rand(0001, 9999) . $last_purchase_id,
                        'total_qty' => array_sum($total_qty),
                        'total_price' => array_sum($total_price)
                    ],
                    'table' => 'purchase'
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

                    $parsing['purchase'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'purchase',
                        'where' => [
                            'id' => $last_purchase_id
                        ]
                    ])->row();

                    $data['id'] = encrypt_text($parsing['purchase']->id);
                    $data['invoice'] = $parsing['purchase']->invoice;
                    $data['supplier_name'] = $parsing['purchase']->supplier_name;
                    $data['total_qty'] = $parsing['purchase']->total_qty;
                    $data['total_price'] = $parsing['purchase']->total_price;
                    $data['total_price_currency_format'] = rupiah($parsing['purchase']->total_price);
                    $data['created_at'] = $parsing['purchase']->created_at;

                    $parsing['purchase_detail'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'purchase_detail',
                        'where' => [
                            'purchase_id' => $parsing['purchase']->id
                        ]
                    ])->result();
                    if (!empty($parsing['purchase_detail'])) {
                        $data['detail'] = [];
                        foreach ($parsing['purchase_detail'] as $key_purchase_detail) {
                            $detail['id'] = encrypt_text($key_purchase_detail->id);
                            $detail['qty'] = $key_purchase_detail->qty;
                            $detail['price'] = $key_purchase_detail->price;
                            $detail['price_currency_format'] = rupiah($key_purchase_detail->price);
                            $detail['item'] = json_decode($key_purchase_detail->item_data);

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
            $parsing['purchase'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'purchase',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();

            if (empty($parsing['purchase'])) {
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

                $data['id'] = encrypt_text($parsing['purchase']->id);
                $data['invoice'] = $parsing['purchase']->invoice;
                $data['supplier_name'] = $parsing['purchase']->supplier_name;
                $data['total_qty'] = $parsing['purchase']->total_qty;
                $data['total_price'] = $parsing['purchase']->total_price;
                $data['total_price_currency_format'] = rupiah($parsing['purchase']->total_price);
                $data['created_at'] = $parsing['purchase']->created_at;

                $parsing['purchase_detail'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'purchase_detail',
                    'where' => [
                        'purchase_id' => $parsing['purchase']->id
                    ]
                ])->result();
                if (!empty($parsing['purchase_detail'])) {
                    $data['detail'] = [];
                    foreach ($parsing['purchase_detail'] as $key_purchase_detail) {
                        $detail['id'] = encrypt_text($key_purchase_detail->id);
                        $detail['qty'] = $key_purchase_detail->qty;
                        $detail['price'] = $key_purchase_detail->price;
                        $detail['price_currency_format'] = rupiah($key_purchase_detail->price);
                        $detail['item'] = json_decode($key_purchase_detail->item_data);

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
                $param['purchase']['field'] = '*';
                $param['purchase']['table'] = 'purchase';
                if ($this->get('page') != null && $this->get('page') != null) {
                    $param['purchase']['limit'] = [
                        $this->get('limit') => ($this->get('page') - 1) * $this->get('limit')
                    ];
                }
                $param['purchase']['order_by'] = [
                    'created_at' => 'desc'
                ];
                $parsing['purchase'] = $this->api_model->select_data($param['purchase'])->result();

                $total_record = $this->api_model->count_all_data($param['purchase']);

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

                if (!empty($parsing['purchase'])) {
                    foreach ($parsing['purchase'] as $key_purchase) {
                        $data['id'] = encrypt_text($key_purchase->id);
                        $data['invoice'] = $key_purchase->invoice;
                        $data['supplier_name'] = $key_purchase->supplier_name;
                        $data['total_qty'] = $key_purchase->total_qty;
                        $data['total_price'] = $key_purchase->total_price;
                        $data['total_price_currency_format'] = rupiah($key_purchase->total_price);
                        $data['created_at'] = $key_purchase->created_at;

                        $parsing['purchase_detail'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'purchase_detail',
                            'where' => [
                                'purchase_id' => $key_purchase->id
                            ]
                        ])->result();
                        if (!empty($parsing['purchase_detail'])) {
                            $data['detail'] = [];
                            foreach ($parsing['purchase_detail'] as $key_purchase_detail) {
                                $detail['id'] = encrypt_text($key_purchase_detail->id);
                                $detail['qty'] = $key_purchase_detail->qty;
                                $detail['price'] = $key_purchase_detail->price;
                                $detail['price_currency_format'] = rupiah($key_purchase_detail->price);
                                $detail['item'] = json_decode($key_purchase_detail->item_data);

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

    public function detail_get($id = null)
    {
        $checking = true;

        if (!empty($id)) {
            $parsing['purchase_detail'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'purchase_detail',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();

            if (empty($parsing['purchase_detail'])) {
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

                $data['id'] = encrypt_text($parsing['purchase_detail']->id);
                $data['item'] = json_decode($parsing['purchase_detail']->item_data);

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
                $parsing['purchase'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'purchase',
                    'where' => [
                        'id' => decrypt_text($this->get('from_parent'))
                    ]
                ])->row();
                if (empty($parsing['purchase'])) {
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
                    $param['purchase_detail']['field'] = '*';
                    $param['purchase_detail']['table'] = 'purchase_detail';
                    $param['purchase_detail']['where'] = [
                        'purchase_id' => $parsing['purchase']->id
                    ];
                    if ($this->get('page') != null && $this->get('page') != null) {
                        $param['purchase_detail']['limit'] = [
                            $this->get('limit') => ($this->get('page') - 1) * $this->get('limit')
                        ];
                    }
                    $parsing['purchase_detail'] = $this->api_model->select_data($param['purchase_detail'])->result();

                    $total_record = $this->api_model->count_all_data($param['purchase_detail']);

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

                    if (!empty($parsing['purchase_detail'])) {
                        foreach ($parsing['purchase_detail'] as $key_purchase_detail) {
                            $data['id'] = encrypt_text($key_purchase_detail->id);
                            $data['item'] = json_decode($key_purchase_detail->item_data);

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
}
