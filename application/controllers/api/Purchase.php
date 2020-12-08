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
            $parsing['item'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'item',
                'where' => [
                    'id' => $this->post('item_id')
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
            } else {
                if ($checking == true) {
                    $query = $this->api_model->send_data([
                        'data' => [
                            'name' => $this->post('name'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'in_active' => 1
                        ],
                        'table' => 'vehicle'
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

                        $parsing['vehicle'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle',
                            'where' => [
                                'id' => $this->db->insert_id()
                            ]
                        ])->row();

                        $data['id'] = $parsing['vehicle']->id;
                        $data['name'] = $parsing['vehicle']->name;
                        $data['created_at'] = $parsing['vehicle']->created_at;
                        $data['updated_at'] = $parsing['vehicle']->updated_at;
                        $data['in_active'] = boolval($parsing['vehicle']->in_active);

                        $parsing['vehicle_children'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle_children',
                            'where' => [
                                'vehicle_id' => $parsing['vehicle']->id
                            ]
                        ])->result();
                        if (!empty($parsing['vehicle_children'])) {
                            $data['children'] = [];
                            foreach ($parsing['vehicle_children'] as $key_vehicle_children) {
                                $children['id'] = $key_vehicle_children->id;
                                $children['vehicle_id'] = $key_vehicle_children->vehicle_id;
                                $children['name'] = $key_vehicle_children->name;
                                $children['created_at'] = $key_vehicle_children->created_at;
                                $children['updated_at'] = $key_vehicle_children->updated_at;
                                $children['in_active'] = boolval($key_vehicle_children->in_active);

                                $data['children'][] = $children;
                            }
                        } else {
                            $data['children'] = null;
                        }

                        $response['result']['data'] = $data;
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function index_get($id = null)
    {
        $checking = true;

        if (!empty($id)) {
            $parsing['vehicle'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'vehicle',
                'where' => [
                    'id' => $id
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

                $data['id'] = $parsing['vehicle']->id;
                $data['name'] = $parsing['vehicle']->name;
                $data['created_at'] = $parsing['vehicle']->created_at;
                $data['updated_at'] = $parsing['vehicle']->updated_at;
                $data['in_active'] = boolval($parsing['vehicle']->in_active);

                $parsing['vehicle_children'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'vehicle_children',
                    'where' => [
                        'vehicle_id' => $parsing['vehicle']->id
                    ]
                ])->result();
                if (!empty($parsing['vehicle_children'])) {
                    $data['children'] = [];
                    foreach ($parsing['vehicle_children'] as $key_vehicle_children) {
                        $children['id'] = $key_vehicle_children->id;
                        $children['vehicle_id'] = $key_vehicle_children->vehicle_id;
                        $children['name'] = $key_vehicle_children->name;
                        $children['created_at'] = $key_vehicle_children->created_at;
                        $children['updated_at'] = $key_vehicle_children->updated_at;
                        $children['in_active'] = boolval($key_vehicle_children->in_active);

                        $data['children'][] = $children;
                    }
                } else {
                    $data['children'] = null;
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
                $param['vehicle']['field'] = '*';
                $param['vehicle']['table'] = 'vehicle';
                if ($this->get('page') != null && $this->get('page') != null) {
                    $param['vehicle']['limit'] = [
                        $this->get('limit') => ($this->get('page') - 1) * $this->get('limit')
                    ];
                }
                $param['vehicle']['order_by'] = [
                    'name' => 'asc'
                ];
                $parsing['vehicle'] = $this->api_model->select_data($param['vehicle'])->result();

                $total_record = $this->api_model->count_all_data($param['vehicle']);

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

                if (!empty($parsing['vehicle'])) {
                    foreach ($parsing['vehicle'] as $key_vehicle) {
                        $data['id'] = $key_vehicle->id;
                        $data['name'] = $key_vehicle->name;
                        $data['created_at'] = $key_vehicle->created_at;
                        $data['updated_at'] = $key_vehicle->updated_at;
                        $data['in_active'] = boolval($key_vehicle->in_active);

                        $parsing['vehicle_children'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle_children',
                            'where' => [
                                'vehicle_id' => $key_vehicle->id
                            ]
                        ])->result();
                        if (!empty($parsing['vehicle_children'])) {
                            $data['children'] = [];
                            foreach ($parsing['vehicle_children'] as $key_vehicle_children) {
                                $children['id'] = $key_vehicle_children->id;
                                $children['vehicle_id'] = $key_vehicle_children->vehicle_id;
                                $children['name'] = $key_vehicle_children->name;
                                $children['created_at'] = $key_vehicle_children->created_at;
                                $children['updated_at'] = $key_vehicle_children->updated_at;
                                $children['in_active'] = boolval($key_vehicle_children->in_active);

                                $data['children'][] = $children;
                            }
                        } else {
                            $data['children'] = null;
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
            $check['vehicle'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'vehicle',
                'where' => [
                    'id' => $id
                ]
            ])->row();
            if (empty($check['vehicle'])) {
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
                if (!empty($this->api_model->select_data([
                    'field' => '*',
                    'table' => 'vehicle',
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
                            'id' => $id
                        ],
                        'data' => [
                            'name' => $this->put('name'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'in_active' => $this->put('in_active')
                        ],
                        'table' => 'vehicle'
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

                        $parsing['vehicle'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle',
                            'where' => [
                                'id' => $id
                            ]
                        ])->row();

                        $data['id'] = $parsing['vehicle']->id;
                        $data['name'] = $parsing['vehicle']->name;
                        $data['created_at'] = $parsing['vehicle']->created_at;
                        $data['updated_at'] = $parsing['vehicle']->updated_at;
                        $data['in_active'] = boolval($parsing['vehicle']->in_active);

                        $parsing['vehicle_children'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'vehicle_children',
                            'where' => [
                                'vehicle_id' => $parsing['vehicle']->id
                            ]
                        ])->result();
                        if (!empty($parsing['vehicle_children'])) {
                            $data['children'] = [];
                            foreach ($parsing['vehicle_children'] as $key_vehicle_children) {
                                $children['id'] = $key_vehicle_children->id;
                                $children['vehicle_id'] = $key_vehicle_children->vehicle_id;
                                $children['name'] = $key_vehicle_children->name;
                                $children['created_at'] = $key_vehicle_children->created_at;
                                $children['updated_at'] = $key_vehicle_children->updated_at;
                                $children['in_active'] = boolval($key_vehicle_children->in_active);

                                $data['children'][] = $children;
                            }
                        } else {
                            $data['children'] = null;
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
            $check['vehicle'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'vehicle',
                'where' => [
                    'id' => $id
                ]
            ])->row();

            if (empty($check['vehicle'])) {
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
                $parsing['vehicle_children'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'vehicle_children',
                    'where' => [
                        'vehicle_id' => $check['vehicle']->id
                    ]
                ])->result();
            }

            if ($checking == true) {
                $query = $this->api_model->delete_data([
                    'where' => [
                        'id' => $id
                    ],
                    'table' => 'vehicle'
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

                    $data['id'] = $check['vehicle']->id;
                    $data['name'] = $check['vehicle']->name;
                    $data['created_at'] = $check['vehicle']->created_at;
                    $data['updated_at'] = $check['vehicle']->updated_at;
                    $data['in_active'] = boolval($check['vehicle']->in_active);

                    $response['result']['data'] = $data;
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }
}
