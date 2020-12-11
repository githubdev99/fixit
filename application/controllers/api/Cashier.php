<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Cashier extends REST_Controller
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
            if (!empty($this->api_model->select_data([
                'field' => '*',
                'table' => 'cashier',
                'where' => [
                    'LOWER(username)' => trim(strtolower($this->post('username')))
                ]
            ])->row())) {
                $checking = false;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_CONFLICT,
                            'message' => 'username has registered'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if (!in_array($this->post('gender'), $this->core['enum']['gender'])) {
                $checking = false;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'gender not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            $parsing['setting'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'setting'
            ])->row();
            if (empty($parsing['setting'])) {
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
            }

            if ($checking == true) {
                $query = $this->api_model->send_data([
                    'data' => [
                        'name' => $this->post('name'),
                        'birth_date' => date('Y-m-d', strtotime($this->post('birth_date'))),
                        'phone_number' => $this->post('phone_number'),
                        'username' => $this->post('username'),
                        'password' => password_hash($parsing['setting']->password_default, PASSWORD_DEFAULT),
                        'gender' => $this->post('gender'),
                        'address' => $this->post('address'),
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    'table' => 'cashier'
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

                    $parsing['cashier'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'cashier',
                        'where' => [
                            'id' => $this->db->insert_id()
                        ]
                    ])->row();

                    $data['id'] = encrypt_text($parsing['cashier']->id);
                    $data['name'] = $parsing['cashier']->name;
                    $data['birth_date'] = $parsing['cashier']->birth_date;
                    $data['phone_number'] = $parsing['cashier']->phone_number;
                    $data['username'] = $parsing['cashier']->username;
                    $data['gender'] = $parsing['cashier']->gender;
                    $data['address'] = $parsing['cashier']->address;
                    $data['created_at'] = $parsing['cashier']->created_at;
                    $data['updated_at'] = $parsing['cashier']->updated_at;

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
            $parsing['cashier'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'cashier',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();

            if (empty($parsing['cashier'])) {
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

                $data['id'] = encrypt_text($parsing['cashier']->id);
                $data['name'] = $parsing['cashier']->name;
                $data['birth_date'] = $parsing['cashier']->birth_date;
                $data['phone_number'] = $parsing['cashier']->phone_number;
                $data['username'] = $parsing['cashier']->username;
                $data['gender'] = $parsing['cashier']->gender;
                $data['address'] = $parsing['cashier']->address;
                $data['created_at'] = $parsing['cashier']->created_at;
                $data['updated_at'] = $parsing['cashier']->updated_at;

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
                $param['cashier']['field'] = '*';
                $param['cashier']['table'] = 'cashier';
                if ($this->get('page') != null && $this->get('page') != null) {
                    $param['cashier']['limit'] = [
                        $this->get('limit') => ($this->get('page') - 1) * $this->get('limit')
                    ];
                }
                $param['cashier']['order_by'] = [
                    'name' => 'asc'
                ];
                $parsing['cashier'] = $this->api_model->select_data($param['cashier'])->result();

                $total_record = $this->api_model->count_all_data($param['cashier']);

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

                if (!empty($parsing['cashier'])) {
                    foreach ($parsing['cashier'] as $key_cashier) {
                        $data['id'] = encrypt_text($key_cashier->id);
                        $data['name'] = $key_cashier->name;
                        $data['birth_date'] = $key_cashier->birth_date;
                        $data['phone_number'] = $key_cashier->phone_number;
                        $data['username'] = $key_cashier->username;
                        $data['gender'] = $key_cashier->gender;
                        $data['address'] = $key_cashier->address;
                        $data['created_at'] = $key_cashier->created_at;
                        $data['updated_at'] = $key_cashier->updated_at;

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
            $check['cashier'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'cashier',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();
            if (empty($check['cashier'])) {
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
                    'table' => 'cashier',
                    'where' => [
                        'LOWER(username)' => trim(strtolower($this->put('username'))),
                        'id !=' => $id
                    ]
                ])->row())) {
                    $checking = false;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_CONFLICT,
                                'message' => 'username has registered'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }

                if (!in_array($this->put('gender'), $this->core['enum']['gender'])) {
                    $checking = false;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'gender not found'
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
                            'name' => $this->put('name'),
                            'birth_date' => date('Y-m-d', strtotime($this->put('birth_date'))),
                            'phone_number' => $this->put('phone_number'),
                            'gender' => $this->put('gender'),
                            'address' => $this->put('address'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ],
                        'table' => 'cashier'
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

                        $parsing['cashier'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'cashier',
                            'where' => [
                                'id' => decrypt_text($id)
                            ]
                        ])->row();

                        $data['id'] = encrypt_text($parsing['cashier']->id);
                        $data['name'] = $parsing['cashier']->name;
                        $data['birth_date'] = $parsing['cashier']->birth_date;
                        $data['phone_number'] = $parsing['cashier']->phone_number;
                        $data['username'] = $parsing['cashier']->username;
                        $data['gender'] = $parsing['cashier']->gender;
                        $data['address'] = $parsing['cashier']->address;
                        $data['created_at'] = $parsing['cashier']->created_at;
                        $data['updated_at'] = $parsing['cashier']->updated_at;

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
            $check['cashier'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'cashier',
                'where' => [
                    'id' => decrypt_text($id)
                ]
            ])->row();

            if (empty($check['cashier'])) {
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
                    'table' => 'cashier'
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

                    $data['id'] = encrypt_text($check['cashier']->id);
                    $data['name'] = $check['cashier']->name;
                    $data['birth_date'] = $check['cashier']->birth_date;
                    $data['phone_number'] = $check['cashier']->phone_number;
                    $data['username'] = $check['cashier']->username;
                    $data['gender'] = $check['cashier']->gender;
                    $data['address'] = $check['cashier']->address;
                    $data['created_at'] = $check['cashier']->created_at;
                    $data['updated_at'] = $check['cashier']->updated_at;

                    $response['result']['data'] = $data;
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }
}
