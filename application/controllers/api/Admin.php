<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Admin extends REST_Controller
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
                'table' => 'admin',
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

            if ($checking == true) {
                $query = $this->api_model->send_data([
                    'data' => [
                        'name' => $this->post('name'),
                        'username' => $this->post('username'),
                        'password' => password_hash($this->post('password'), PASSWORD_DEFAULT)
                    ],
                    'table' => 'admin'
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

                    $parsing['admin'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'admin',
                        'where' => [
                            'id' => $this->db->insert_id()
                        ]
                    ])->row();

                    $data['id'] = encrypt_text($parsing['admin']->id);
                    $data['name'] = $parsing['admin']->name;
                    $data['username'] = $parsing['admin']->username;

                    $response['result']['data'] = $data;
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function setting_post($type = null)
    {
        $checking = true;

        if (!$this->post() || empty($type)) {
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
            $arr_type = [
                'create', 'update', 'reset'
            ];

            if (!in_array($type, $arr_type)) {
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
                if ($type == 'create') {
                    $query = $this->api_model->send_data([
                        'data' => [
                            'password_default' => $this->post('password_default')
                        ],
                        'table' => 'setting'
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
                                    'code' => SELF::HTTP_OK,
                                    'message' => 'add data success'
                                ],
                                'data' => []
                            ],
                            'status' => SELF::HTTP_OK
                        ];

                        $parsing['cashier'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'cashier'
                        ])->result();
                        foreach ($parsing['cashier'] as $key_cashier) {
                            $this->api_model->send_data([
                                'where' => [
                                    'id' => $key_cashier->id
                                ],
                                'data' => [
                                    'password' => password_hash($this->post('password_default'), PASSWORD_DEFAULT)
                                ],
                                'table' => 'cashier'
                            ]);
                        }

                        $parsing['setting'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'setting',
                            'where' => [
                                'id' => $this->db->insert_id()
                            ]
                        ])->row();

                        $data['id'] = encrypt_text($parsing['setting']->id);
                        $data['password_default'] = $parsing['setting']->password_default;

                        $response['result']['data'] = $data;
                    }
                } elseif ($type == 'update') {
                    $query = $this->api_model->send_data([
                        'where' => [
                            'id' => decrypt_text($this->post('id'))
                        ],
                        'data' => [
                            'password_default' => $this->post('password_default')
                        ],
                        'table' => 'setting'
                    ]);

                    if ($query['error'] == true) {
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_BAD_REQUEST,
                                    'message' => 'update data failed',
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
                                    'message' => 'update data success'
                                ],
                                'data' => []
                            ],
                            'status' => SELF::HTTP_OK
                        ];

                        $parsing['cashier'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'cashier'
                        ])->result();
                        foreach ($parsing['cashier'] as $key_cashier) {
                            $this->api_model->send_data([
                                'where' => [
                                    'id' => $key_cashier->id
                                ],
                                'data' => [
                                    'password' => password_hash($this->post('password_default'), PASSWORD_DEFAULT)
                                ],
                                'table' => 'cashier'
                            ]);
                        }

                        $parsing['setting'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'setting',
                            'where' => [
                                'id' => decrypt_text($this->post('id'))
                            ]
                        ])->row();

                        $data['id'] = encrypt_text($parsing['setting']->id);
                        $data['password_default'] = $parsing['setting']->password_default;

                        $response['result']['data'] = $data;
                    }
                } elseif ($type == 'reset') {
                    $query = $this->api_model->delete_data([
                        'where' => [
                            'id' => decrypt_text($this->post('id'))
                        ],
                        'table' => 'setting'
                    ]);

                    if ($query['error'] == true) {
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_BAD_REQUEST,
                                    'message' => 'reset data failed',
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
                                    'message' => 'reset data success'
                                ],
                                'data' => null
                            ],
                            'status' => SELF::HTTP_OK
                        ];
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }
}
