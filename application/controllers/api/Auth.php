<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller
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
            $parsing['admin'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'admin',
                'where' => [
                    'LOWER(username)' => trim(strtolower($this->post('username')))
                ]
            ])->row();

            $parsing['cashier'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'cashier',
                'where' => [
                    'LOWER(username)' => trim(strtolower($this->post('username')))
                ]
            ])->row();

            $parsing['mechanic'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'mechanic',
                'where' => [
                    'LOWER(username)' => trim(strtolower($this->post('username')))
                ]
            ])->row();

            if (empty($parsing['admin']) && empty($parsing['cashier']) && empty($parsing['mechanic'])) {
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
                if (!empty($parsing['admin'])) {
                    if (!password_verify($this->post('password'), $parsing['admin']->password)) {
                        $checking = false;
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_UNAUTHORIZED,
                                    'message' => 'incorrect username or password'
                                ],
                                'data' => null
                            ],
                            'status' => SELF::HTTP_OK
                        ];
                    }
                } elseif (!empty($parsing['cashier'])) {
                    if (!password_verify($this->post('password'), $parsing['cashier']->password)) {
                        $checking = false;
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_UNAUTHORIZED,
                                    'message' => 'incorrect username or password'
                                ],
                                'data' => null
                            ],
                            'status' => SELF::HTTP_OK
                        ];
                    }
                } elseif (!empty($parsing['mechanic'])) {
                    if (!password_verify($this->post('password'), $parsing['mechanic']->password)) {
                        $checking = false;
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_UNAUTHORIZED,
                                    'message' => 'incorrect username or password'
                                ],
                                'data' => null
                            ],
                            'status' => SELF::HTTP_OK
                        ];
                    }
                } else {
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
            }

            if ($checking == true) {
                if (!empty($parsing['admin'])) {
                    $this->session->set_userdata('admin', encrypt_text($parsing['admin']->id));
                } elseif (!empty($parsing['cashier'])) {
                    $this->session->set_userdata('cashier', encrypt_text($parsing['cashier']->id));
                } elseif (!empty($parsing['mechanic'])) {
                    $this->session->set_userdata('mechanic', encrypt_text($parsing['mechanic']->id));
                }

                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_OK,
                            'message' => 'login success'
                        ],
                        'data' => 'session created'
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }
        }

        $this->response($response['result'], $response['status']);
    }
}
