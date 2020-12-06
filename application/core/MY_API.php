<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class MY_Controller extends REST_Controller
{
    protected $core = [];

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Jakarta');

        $this->core['sortBy'] = [
            [
                'name' => 'Harga Terendah',
                'value' => 'price_asc'
            ],
            [
                'name' => 'Harga Tertinggi',
                'value' => 'price_desc'
            ],
            [
                'name' => 'Paling Baru',
                'value' => 'date_asc'
            ],
            [
                'name' => 'Paling Lama',
                'value' => 'date_desc'
            ]
        ];

        foreach ($this->core['sortBy'] as $key_sortBy) {
            $this->core['sortBy_value'][] = $key_sortBy['value'];
        }
    }

    public function parsing_breadcrumbs($where)
    {
        $param['field'] = '*';
        $param['table'] = 'tbl_breadcrumbs';
        $param['where'] = array_merge($where);

        return $this->api_model->select_data($param)->result();
    }

    public function auth()
    {
        $headers = $this->input->request_headers();
        $response = [];

        if (!empty($headers['Authorization'])) {
            $get_token = $this->authorization->validate_token();
            if ($get_token['error'] == FALSE) {
                $this->core['customer'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'tbl_customer',
                    'where' => [
                        'id' => $get_token['data']->id
                    ]
                ])->row();

                if (empty($this->core['customer'])) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'data not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_NOT_FOUND
                    ];
                }
            } else {
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => $get_token['data']
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_UNAUTHORIZED
                ];
            }
        }

        return $response;
    }
}
