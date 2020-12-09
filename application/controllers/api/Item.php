<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Item extends REST_Controller
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
            if (!empty($this->post('vehicle_children_id'))) {
                $parsing['vehicle'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'vehicle',
                    'where' => [
                        'id' => $this->post('vehicle_id')
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

            if (!empty($this->post('vehicle_children_id'))) {
                $parsing['vehicle_children'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'vehicle_children',
                    'where' => [
                        'id' => $this->post('vehicle_children_id')
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

            if (!empty($this->api_model->select_data([
                'field' => '*',
                'table' => 'item',
                'where' => [
                    'LOWER(name)' => trim(strtolower($this->post('name')))
                ]
            ])->row())) {
                $checking = false;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_CONFLICT,
                            'message' => 'name has input'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if ($checking == true) {
                $query = $this->api_model->send_data([
                    'data' => [
                        'vehicle_id' => (!empty($this->post('vehicle_id'))) ? $this->post('vehicle_id') : null,
                        'vehicle_children_id' => (!empty($this->post('vehicle_children_id'))) ? $this->post('vehicle_children_id') : null,
                        'name' => $this->post('name'),
                        'price' => $this->post('price'),
                        'stock' => $this->post('stock'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'in_active' => 1
                    ],
                    'table' => 'item'
                ]);

                if ($query['error'] == true) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'add data failed',
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
                                'code' => SELF::HTTP_CREATED,
                                'message' => 'add data success'
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    $parsing['item'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'item',
                        'where' => [
                            'id' => $this->db->insert_id()
                        ]
                    ])->row();

                    $data['id'] = encrypt_text($parsing['item']->id);
                    $data['name'] = $parsing['item']->name;
                    $data['price'] = $parsing['item']->price;
                    $data['price_currency_format'] = rupiah($parsing['item']->price);
                    $data['stock'] = $parsing['item']->stock;
                    $data['created_at'] = $parsing['item']->created_at;
                    $data['updated_at'] = $parsing['item']->updated_at;
                    $data['in_active'] = boolval($parsing['item']->in_active);

                    if (!empty($parsing['item']->vehicle_id)) {
                        $parsing['vehicle'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle',
                            'where' => [
                                'id' => $parsing['item']->vehicle_id
                            ]
                        ])->row();
                        $vehicle['id'] = encrypt_text($parsing['vehicle']->id);
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
                            $vehicle_children['id'] = encrypt_text($parsing['vehicle_children']->id);
                            $vehicle_children['vehicle_id'] = $parsing['vehicle_children']->vehicle_id;
                            $vehicle_children['name'] = $parsing['vehicle_children']->name;
                            $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                            $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                            $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                            $vehicle['children'] = $vehicle_children;
                        } else {
                            $vehicle['children'] = null;
                        }

                        $data['vehicle'] = $vehicle;
                    } else {
                        $data['vehicle'] = null;
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
            $parsing['item'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'item',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();

            if (empty($parsing['item'])) {
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

                $data['id'] = encrypt_text($parsing['item']->id);
                $data['name'] = $parsing['item']->name;
                $data['price'] = $parsing['item']->price;
                $data['price_currency_format'] = rupiah($parsing['item']->price);
                $data['stock'] = $parsing['item']->stock;
                $data['created_at'] = $parsing['item']->created_at;
                $data['updated_at'] = $parsing['item']->updated_at;
                $data['in_active'] = boolval($parsing['item']->in_active);

                if (!empty($parsing['item']->vehicle_id)) {
                    $parsing['vehicle'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'vehicle',
                        'where' => [
                            'id' => $parsing['item']->vehicle_id
                        ]
                    ])->row();
                    $vehicle['id'] = encrypt_text($parsing['vehicle']->id);
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
                        $vehicle_children['id'] = encrypt_text($parsing['vehicle_children']->id);
                        $vehicle_children['vehicle_id'] = $parsing['vehicle_children']->vehicle_id;
                        $vehicle_children['name'] = $parsing['vehicle_children']->name;
                        $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                        $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                        $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                        $vehicle['children'] = $vehicle_children;
                    } else {
                        $vehicle['children'] = null;
                    }

                    $data['vehicle'] = $vehicle;
                } else {
                    $data['vehicle'] = null;
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
                $param['item']['field'] = '*';
                $param['item']['table'] = 'item';
                if ($this->get('page') != null && $this->get('page') != null) {
                    $param['item']['limit'] = [
                        $this->get('limit') => ($this->get('page') - 1) * $this->get('limit')
                    ];
                }
                $param['item']['order_by'] = [
                    'name' => 'asc'
                ];
                $parsing['item'] = $this->api_model->select_data($param['item'])->result();

                $total_record = $this->api_model->count_all_data($param['item']);

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

                if (!empty($parsing['item'])) {
                    foreach ($parsing['item'] as $key_item) {
                        $data['id'] = encrypt_text($key_item->id);
                        $data['name'] = $key_item->name;
                        $data['price'] = $key_item->price;
                        $data['price_currency_format'] = rupiah($key_item->price);
                        $data['stock'] = $key_item->stock;
                        $data['created_at'] = $key_item->created_at;
                        $data['updated_at'] = $key_item->updated_at;
                        $data['in_active'] = boolval($key_item->in_active);

                        if (!empty($key_item->vehicle_id)) {
                            $parsing['vehicle'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'vehicle',
                                'where' => [
                                    'id' => $key_item->vehicle_id
                                ]
                            ])->row();
                            $vehicle['id'] = encrypt_text($parsing['vehicle']->id);
                            $vehicle['name'] = $parsing['vehicle']->name;
                            $vehicle['created_at'] = $parsing['vehicle']->created_at;
                            $vehicle['updated_at'] = $parsing['vehicle']->updated_at;
                            $vehicle['in_active'] = boolval($parsing['vehicle']->in_active);

                            if (!empty($key_item->vehicle_children_id)) {
                                $parsing['vehicle_children'] = $this->api_model->select_data([
                                    'field' => '*',
                                    'table' => 'vehicle_children',
                                    'where' => [
                                        'id' => $key_item->vehicle_children_id
                                    ]
                                ])->row();
                                $vehicle_children['id'] = encrypt_text($parsing['vehicle_children']->id);
                                $vehicle_children['vehicle_id'] = $parsing['vehicle_children']->vehicle_id;
                                $vehicle_children['name'] = $parsing['vehicle_children']->name;
                                $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                                $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                                $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                                $vehicle['children'] = $vehicle_children;
                            } else {
                                $vehicle['children'] = null;
                            }

                            $data['vehicle'] = $vehicle;
                        } else {
                            $data['vehicle'] = null;
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

    public function index_put($id = null)
    {
        $checking = true;

        if (!$this->put() || empty($id)) {
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
            $check['item'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'item',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();
            if (empty($check['item'])) {
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
                if (!empty($this->put('vehicle_id'))) {
                    $parsing['vehicle'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'vehicle',
                        'where' => [
                            'id' => $this->put('vehicle_id')
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

                if (!empty($this->put('vehicle_children_id'))) {
                    $parsing['vehicle_children'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'vehicle_children',
                        'where' => [
                            'id' => $this->put('vehicle_children_id')
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

                if (!empty($this->api_model->select_data([
                    'field' => '*',
                    'table' => 'item',
                    'where' => [
                        'LOWER(name)' => trim(strtolower($this->put('name'))),
                        'id !=' => $id
                    ]
                ])->row())) {
                    $checking = false;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_CONFLICT,
                                'message' => 'name has input'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }

                if ($checking == true) {
                    $query = $this->api_model->send_data([
                        'where' => [
                            'id' => decrypt_text($id)
                        ],
                        'data' => [
                            'vehicle_id' => (!empty($this->put('vehicle_id'))) ? $this->put('vehicle_id') : null,
                            'vehicle_children_id' => (!empty($this->put('vehicle_children_id'))) ? $this->put('vehicle_children_id') : null,
                            'name' => $this->put('name'),
                            'price' => $this->put('price'),
                            'stock' => $this->put('stock'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'in_active' => $this->put('in_active')
                        ],
                        'table' => 'item'
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

                        $parsing['item'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'item',
                            'where' => [
                                'id' => decrypt_text($id)
                            ]
                        ])->row();

                        $data['id'] = encrypt_text($parsing['item']->id);
                        $data['name'] = $parsing['item']->name;
                        $data['price'] = $parsing['item']->price;
                        $data['price_currency_format'] = rupiah($parsing['item']->price);
                        $data['stock'] = $parsing['item']->stock;
                        $data['created_at'] = $parsing['item']->created_at;
                        $data['updated_at'] = $parsing['item']->updated_at;
                        $data['in_active'] = boolval($parsing['item']->in_active);

                        if (!empty($parsing['item']->vehicle_id)) {
                            $parsing['vehicle'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'vehicle',
                                'where' => [
                                    'id' => $parsing['item']->vehicle_id
                                ]
                            ])->row();
                            $vehicle['id'] = encrypt_text($parsing['vehicle']->id);
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
                                $vehicle_children['id'] = encrypt_text($parsing['vehicle_children']->id);
                                $vehicle_children['vehicle_id'] = $parsing['vehicle_children']->vehicle_id;
                                $vehicle_children['name'] = $parsing['vehicle_children']->name;
                                $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                                $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                                $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                                $vehicle['children'] = $vehicle_children;
                            } else {
                                $vehicle['children'] = null;
                            }

                            $data['vehicle'] = $vehicle;
                        } else {
                            $data['vehicle'] = null;
                        }

                        $response['result']['data'] = $data;
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function index_delete($id = null)
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
            $check['item'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'item',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();
            if (empty($check['item'])) {
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
                $query = $this->api_model->delete_data([
                    'where' => [
                        'id' => decrypt_text($id)
                    ],
                    'table' => 'item'
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

                    $data['id'] = encrypt_text($check['item']->id);
                    $data['name'] = $check['item']->name;
                    $data['price'] = $check['item']->price;
                    $data['price_currency_format'] = rupiah($check['item']->price);
                    $data['stock'] = $check['item']->stock;
                    $data['created_at'] = $check['item']->created_at;
                    $data['updated_at'] = $check['item']->updated_at;
                    $data['in_active'] = boolval($check['item']->in_active);

                    if (!empty($check['item']->vehicle_id)) {
                        $parsing['vehicle'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle',
                            'where' => [
                                'id' => $check['item']->vehicle_id
                            ]
                        ])->row();
                        $vehicle['id'] = encrypt_text($parsing['vehicle']->id);
                        $vehicle['name'] = $parsing['vehicle']->name;
                        $vehicle['created_at'] = $parsing['vehicle']->created_at;
                        $vehicle['updated_at'] = $parsing['vehicle']->updated_at;
                        $vehicle['in_active'] = boolval($parsing['vehicle']->in_active);

                        if (!empty($check['item']->vehicle_children_id)) {
                            $parsing['vehicle_children'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'vehicle_children',
                                'where' => [
                                    'id' => $check['item']->vehicle_children_id
                                ]
                            ])->row();
                            $vehicle_children['id'] = encrypt_text($parsing['vehicle_children']->id);
                            $vehicle_children['vehicle_id'] = $parsing['vehicle_children']->vehicle_id;
                            $vehicle_children['name'] = $parsing['vehicle_children']->name;
                            $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                            $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                            $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                            $vehicle['children'] = $vehicle_children;
                        } else {
                            $vehicle['children'] = null;
                        }

                        $data['vehicle'] = $vehicle;
                    } else {
                        $data['vehicle'] = null;
                    }

                    $response['result']['data'] = $data;
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }
}
