<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Service extends REST_Controller
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
                        'id' => decrypt_text($this->post('vehicle_id'))
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
                        'id' => decrypt_text($this->post('vehicle_children_id'))
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

            if ($checking == true) {
                $query = $this->api_model->send_data([
                    'data' => [
                        'vehicle_id' => decrypt_text($this->post('vehicle_id')),
                        'vehicle_children_id' => (!empty($this->post('vehicle_children_id'))) ? decrypt_text($this->post('vehicle_children_id')) : null,
                        'name' => $this->post('name'),
                        'price' => clean_rupiah($this->post('price')),
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    'table' => 'service'
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

                    $parsing['service'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'service',
                        'where' => [
                            'id' => $this->db->insert_id()
                        ]
                    ])->row();

                    $data['id'] = encrypt_text($parsing['service']->id);
                    $data['name'] = $parsing['service']->name;
                    $data['price'] = $parsing['service']->price;
                    $data['price_currency_format'] = rupiah($parsing['service']->price);
                    $data['created_at'] = $parsing['service']->created_at;
                    $data['updated_at'] = $parsing['service']->updated_at;

                    $parsing['vehicle'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'vehicle',
                        'where' => [
                            'id' => $parsing['service']->vehicle_id
                        ]
                    ])->row();
                    $vehicle['id'] = encrypt_text($parsing['vehicle']->id);
                    $vehicle['name'] = $parsing['vehicle']->name;
                    $vehicle['created_at'] = $parsing['vehicle']->created_at;
                    $vehicle['updated_at'] = $parsing['vehicle']->updated_at;
                    $vehicle['in_active'] = boolval($parsing['vehicle']->in_active);

                    if (!empty($parsing['service']->vehicle_children_id)) {
                        $parsing['vehicle_children'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle_children',
                            'where' => [
                                'id' => $parsing['service']->vehicle_children_id
                            ]
                        ])->row();
                        $vehicle_children['id'] = encrypt_text($parsing['vehicle_children']->id);
                        $vehicle_children['vehicle_id'] = encrypt_text($parsing['vehicle_children']->vehicle_id);
                        $vehicle_children['name'] = $parsing['vehicle_children']->name;
                        $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                        $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                        $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                        $vehicle['children'] = $vehicle_children;
                    } else {
                        $vehicle['children'] = null;
                    }

                    $data['vehicle'] = $vehicle;

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
            $parsing['service'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'service',
                'where' => [
                    'id' => decrypt_text($id)
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

                $data['id'] = encrypt_text($parsing['service']->id);
                $data['name'] = $parsing['service']->name;
                $data['price'] = $parsing['service']->price;
                $data['price_currency_format'] = rupiah($parsing['service']->price);
                $data['created_at'] = $parsing['service']->created_at;
                $data['updated_at'] = $parsing['service']->updated_at;

                $parsing['vehicle'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'vehicle',
                    'where' => [
                        'id' => $parsing['service']->vehicle_id
                    ]
                ])->row();
                $vehicle['id'] = encrypt_text($parsing['vehicle']->id);
                $vehicle['name'] = $parsing['vehicle']->name;
                $vehicle['created_at'] = $parsing['vehicle']->created_at;
                $vehicle['updated_at'] = $parsing['vehicle']->updated_at;
                $vehicle['in_active'] = boolval($parsing['vehicle']->in_active);

                if (!empty($parsing['service']->vehicle_children_id)) {
                    $parsing['vehicle_children'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'vehicle_children',
                        'where' => [
                            'id' => $parsing['service']->vehicle_children_id
                        ]
                    ])->row();
                    $vehicle_children['id'] = encrypt_text($parsing['vehicle_children']->id);
                    $vehicle_children['vehicle_id'] = encrypt_text($parsing['vehicle_children']->vehicle_id);
                    $vehicle_children['name'] = $parsing['vehicle_children']->name;
                    $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                    $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                    $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                    $vehicle['children'] = $vehicle_children;
                } else {
                    $vehicle['children'] = null;
                }

                $data['vehicle'] = $vehicle;

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
                $param['service']['field'] = '*';
                $param['service']['table'] = 'service';
                if ($this->get('page') != null && $this->get('page') != null) {
                    $param['service']['limit'] = [
                        $this->get('limit') => ($this->get('page') - 1) * $this->get('limit')
                    ];
                }
                $param['service']['order_by'] = [
                    'name' => 'asc'
                ];
                $parsing['service'] = $this->api_model->select_data($param['service'])->result();

                $total_record = $this->api_model->count_all_data($param['service']);

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

                if (!empty($parsing['service'])) {
                    foreach ($parsing['service'] as $key_service) {
                        $data['id'] = encrypt_text($key_service->id);
                        $data['name'] = $key_service->name;
                        $data['price'] = $key_service->price;
                        $data['price_currency_format'] = rupiah($key_service->price);
                        $data['created_at'] = $key_service->created_at;
                        $data['updated_at'] = $key_service->updated_at;

                        $parsing['vehicle'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle',
                            'where' => [
                                'id' => $key_service->vehicle_id
                            ]
                        ])->row();
                        $vehicle['id'] = encrypt_text($parsing['vehicle']->id);
                        $vehicle['name'] = $parsing['vehicle']->name;
                        $vehicle['created_at'] = $parsing['vehicle']->created_at;
                        $vehicle['updated_at'] = $parsing['vehicle']->updated_at;
                        $vehicle['in_active'] = boolval($parsing['vehicle']->in_active);

                        if (!empty($key_service->vehicle_children_id)) {
                            $parsing['vehicle_children'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'vehicle_children',
                                'where' => [
                                    'id' => $key_service->vehicle_children_id
                                ]
                            ])->row();
                            $vehicle_children['id'] = encrypt_text($parsing['vehicle_children']->id);
                            $vehicle_children['vehicle_id'] = encrypt_text($parsing['vehicle_children']->vehicle_id);
                            $vehicle_children['name'] = $parsing['vehicle_children']->name;
                            $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                            $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                            $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                            $vehicle['children'] = $vehicle_children;
                        } else {
                            $vehicle['children'] = null;
                        }

                        $data['vehicle'] = $vehicle;

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
            $check['service'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'service',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();
            if (empty($check['service'])) {
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
                $parsing['vehicle'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'vehicle',
                    'where' => [
                        'id' => decrypt_text($this->put('vehicle_id'))
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

                if (!empty($this->put('vehicle_children_id'))) {
                    $parsing['vehicle_children'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'vehicle_children',
                        'where' => [
                            'id' => decrypt_text($this->put('vehicle_children_id'))
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

                if ($checking == true) {
                    $query = $this->api_model->send_data([
                        'where' => [
                            'id' => decrypt_text($id)
                        ],
                        'data' => [
                            'vehicle_id' => decrypt_text($this->put('vehicle_id')),
                            'vehicle_children_id' => (!empty($this->put('vehicle_children_id'))) ? decrypt_text($this->put('vehicle_children_id')) : null,
                            'name' => $this->put('name'),
                            'price' => clean_rupiah($this->put('price')),
                            'updated_at' => date('Y-m-d H:i:s')
                        ],
                        'table' => 'service'
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

                        $parsing['service'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'service',
                            'where' => [
                                'id' => decrypt_text($id)
                            ]
                        ])->row();

                        $data['id'] = encrypt_text($parsing['service']->id);
                        $data['name'] = $parsing['service']->name;
                        $data['price'] = $parsing['service']->price;
                        $data['price_currency_format'] = rupiah($parsing['service']->price);
                        $data['created_at'] = $parsing['service']->created_at;
                        $data['updated_at'] = $parsing['service']->updated_at;

                        $parsing['vehicle'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle',
                            'where' => [
                                'id' => $parsing['service']->vehicle_id
                            ]
                        ])->row();
                        $vehicle['id'] = encrypt_text($parsing['vehicle']->id);
                        $vehicle['name'] = $parsing['vehicle']->name;
                        $vehicle['created_at'] = $parsing['vehicle']->created_at;
                        $vehicle['updated_at'] = $parsing['vehicle']->updated_at;
                        $vehicle['in_active'] = boolval($parsing['vehicle']->in_active);

                        if (!empty($parsing['service']->vehicle_children_id)) {
                            $parsing['vehicle_children'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'vehicle_children',
                                'where' => [
                                    'id' => $parsing['service']->vehicle_children_id
                                ]
                            ])->row();
                            $vehicle_children['id'] = encrypt_text($parsing['vehicle_children']->id);
                            $vehicle_children['vehicle_id'] = encrypt_text($parsing['vehicle_children']->vehicle_id);
                            $vehicle_children['name'] = $parsing['vehicle_children']->name;
                            $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                            $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                            $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                            $vehicle['children'] = $vehicle_children;
                        } else {
                            $vehicle['children'] = null;
                        }

                        $data['vehicle'] = $vehicle;

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
            $check['service'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'service',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();
            if (empty($check['service'])) {
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
                    'table' => 'service'
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

                    $data['id'] = encrypt_text($check['service']->id);
                    $data['name'] = $check['service']->name;
                    $data['price'] = $check['service']->price;
                    $data['price_currency_format'] = rupiah($check['service']->price);
                    $data['created_at'] = $check['service']->created_at;
                    $data['updated_at'] = $check['service']->updated_at;

                    $parsing['vehicle'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'vehicle',
                        'where' => [
                            'id' => $check['service']->vehicle_id
                        ]
                    ])->row();
                    $vehicle['id'] = encrypt_text($parsing['vehicle']->id);
                    $vehicle['name'] = $parsing['vehicle']->name;
                    $vehicle['created_at'] = $parsing['vehicle']->created_at;
                    $vehicle['updated_at'] = $parsing['vehicle']->updated_at;
                    $vehicle['in_active'] = boolval($parsing['vehicle']->in_active);

                    if (!empty($check['service']->vehicle_children_id)) {
                        $parsing['vehicle_children'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle_children',
                            'where' => [
                                'id' => $check['service']->vehicle_children_id
                            ]
                        ])->row();
                        $vehicle_children['id'] = encrypt_text($parsing['vehicle_children']->id);
                        $vehicle_children['vehicle_id'] = encrypt_text($parsing['vehicle_children']->vehicle_id);
                        $vehicle_children['name'] = $parsing['vehicle_children']->name;
                        $vehicle_children['created_at'] = $parsing['vehicle_children']->created_at;
                        $vehicle_children['updated_at'] = $parsing['vehicle_children']->updated_at;
                        $vehicle_children['in_active'] = boolval($parsing['vehicle_children']->in_active);

                        $vehicle['children'] = $vehicle_children;
                    } else {
                        $vehicle['children'] = null;
                    }

                    $data['vehicle'] = $vehicle;

                    $response['result']['data'] = $data;
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }
}
