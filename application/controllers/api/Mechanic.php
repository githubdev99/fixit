<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Mechanic extends REST_Controller
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
                'table' => 'mechanic',
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

            if ($checking == true) {
                $query = $this->api_model->send_data([
                    'data' => [
                        'name' => $this->post('name'),
                        'birth_date' => date('Y-m-d', strtotime($this->post('birth_date'))),
                        'phone_number' => $this->post('phone_number'),
                        'username' => $this->post('username'),
                        'password' => password_hash($this->post('password'), PASSWORD_DEFAULT),
                        'gender' => $this->post('gender'),
                        'created_at' => date('Y-m-d H:i:s')
                    ],
                    'table' => 'mechanic'
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

                    $parsing['mechanic'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'mechanic',
                        'where' => [
                            'id' => $this->db->insert_id()
                        ]
                    ])->row();

                    $data['id'] = $parsing['mechanic']->id;
                    $data['name'] = $parsing['mechanic']->name;
                    $data['birth_date'] = $parsing['mechanic']->birth_date;
                    $data['phone_number'] = $parsing['mechanic']->phone_number;
                    $data['username'] = $parsing['mechanic']->username;
                    $data['gender'] = $parsing['mechanic']->gender;
                    $data['created_at'] = $parsing['mechanic']->created_at;
                    $data['updated_at'] = $parsing['mechanic']->updated_at;

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
            $parsing['mechanic'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'mechanic',
                'where' => [
                    'id' => $id
                ]
            ])->row();

            if (empty($parsing['mechanic'])) {
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

                $data['id'] = $parsing['mechanic']->id;
                $data['name'] = $parsing['mechanic']->name;
                $data['birth_date'] = $parsing['mechanic']->birth_date;
                $data['phone_number'] = $parsing['mechanic']->phone_number;
                $data['username'] = $parsing['mechanic']->username;
                $data['gender'] = $parsing['mechanic']->gender;
                $data['created_at'] = $parsing['mechanic']->created_at;
                $data['updated_at'] = $parsing['mechanic']->updated_at;

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
                $param['mechanic']['field'] = '*';
                $param['mechanic']['table'] = 'mechanic';
                if ($this->get('page') != null && $this->get('page') != null) {
                    $param['mechanic']['limit'] = [
                        $this->get('limit') => ($this->get('page') - 1) * $this->get('limit')
                    ];
                }
                $param['mechanic']['order_by'] = [
                    'name' => 'asc'
                ];
                $parsing['mechanic'] = $this->api_model->select_data($param['mechanic'])->result();

                $total_record = $this->api_model->count_all_data($param['mechanic']);

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

                if (!empty($parsing['mechanic'])) {
                    foreach ($parsing['mechanic'] as $key_mechanic) {
                        $data['id'] = $key_mechanic->id;
                        $data['name'] = $key_mechanic->name;
                        $data['birth_date'] = $key_mechanic->birth_date;
                        $data['phone_number'] = $key_mechanic->phone_number;
                        $data['username'] = $key_mechanic->username;
                        $data['gender'] = $key_mechanic->gender;
                        $data['created_at'] = $key_mechanic->created_at;
                        $data['updated_at'] = $key_mechanic->updated_at;

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
            $check['mechanic'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'mechanic',
                'where' => [
                    'id' => $id
                ]
            ])->row();
            if (empty($check['mechanic'])) {
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
                    'table' => 'mechanic',
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
                            'id' => $id
                        ],
                        'data' => [
                            'name' => $this->put('name'),
                            'birth_date' => date('Y-m-d', strtotime($this->put('birth_date'))),
                            'phone_number' => $this->put('phone_number'),
                            'gender' => $this->put('gender'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ],
                        'table' => 'mechanic'
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

                        $parsing['mechanic'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'mechanic',
                            'where' => [
                                'id' => $id
                            ]
                        ])->row();

                        $data['id'] = $parsing['mechanic']->id;
                        $data['name'] = $parsing['mechanic']->name;
                        $data['birth_date'] = $parsing['mechanic']->birth_date;
                        $data['phone_number'] = $parsing['mechanic']->phone_number;
                        $data['username'] = $parsing['mechanic']->username;
                        $data['gender'] = $parsing['mechanic']->gender;
                        $data['created_at'] = $parsing['mechanic']->created_at;
                        $data['updated_at'] = $parsing['mechanic']->updated_at;

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
            $check['mechanic'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'mechanic',
                'where' => [
                    'id' => $id
                ]
            ])->row();

            if (empty($check['mechanic'])) {
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
                        'id' => $id
                    ],
                    'table' => 'mechanic'
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

                    $data['id'] = $check['mechanic']->id;
                    $data['name'] = $check['mechanic']->name;
                    $data['birth_date'] = $check['mechanic']->birth_date;
                    $data['phone_number'] = $check['mechanic']->phone_number;
                    $data['username'] = $check['mechanic']->username;
                    $data['gender'] = $check['mechanic']->gender;
                    $data['created_at'] = $check['mechanic']->created_at;
                    $data['updated_at'] = $check['mechanic']->updated_at;

                    $response['result']['data'] = $data;
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }
}
