<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index_put()
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (!$this->put()) {
                $checking = FALSE;
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

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            $parsing['gender'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'tbl_gender',
                'where' => [
                    'key' => $this->put('gender')
                ]
            ])->row();

            if (empty($parsing['gender'])) {
                $checking = FALSE;
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

            if ($checking == TRUE) {
                $query = $this->api_model->send_data([
                    'where' => [
                        'id' => $this->core['customer']->id
                    ],
                    'data' => [
                        'name' => $this->put('name'),
                        'phone_number' => $this->put('phoneNumber'),
                        'gender_id' => $parsing['gender']->id,
                    ],
                    'table' => 'tbl_customer'
                ]);

                if ($query['error'] == TRUE) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'update_customer.failed',
                                'from_system' => $query['system']
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                } else {
                    if (!empty($this->auth())) {
                        $response = $this->auth();
                    } else {
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_OK,
                                    'message' => 'update_customer.success',
                                ],
                                'data' => []
                            ],
                            'status' => SELF::HTTP_OK
                        ];

                        $data['id'] = $this->core['customer']->id;
                        $data['name'] = $this->core['customer']->name;
                        $data['email'] = $this->core['customer']->email;
                        $data['phoneNumber'] = $this->core['customer']->phone_number;
                        $data['birthDate'] = $this->core['customer']->birth_date;

                        $gender['id'] = $parsing['gender']->id;
                        $gender['key'] = $parsing['gender']->key;
                        $gender['name'] = $parsing['gender']->name;
                        $data['gender'] = $gender;

                        $parsing['address'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'tbl_address',
                            'where' => [
                                'customer_id' => $this->core['customer']->id
                            ],
                            'order_by' => [
                                'is_primary' => 'desc'
                            ]
                        ])->result();

                        $data['address'] = [];
                        foreach ($parsing['address'] as $key_address) {
                            $address['id'] = $key_address->id;
                            $address['addressAs'] = $key_address->address_as;
                            $address['receiverName'] = $key_address->receiver_name;
                            $address['phoneNumber'] = $key_address->phone_number;
                            $address['mobilePhoneNumber'] = $key_address->mobile_phone_number;
                            $address['completeAddress'] = $key_address->complete_address;

                            $parsing['province'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_province',
                                'where' => [
                                    'province_id' => $key_address->province_id
                                ]
                            ])->row();
                            if (!empty($parsing['province'])) {
                                $province['id'] = $parsing['province']->province_id;
                                $province['name'] = $parsing['province']->province;
                                $province['kd'] = $parsing['province']->province_kd;

                                $address['province'] = $province;
                            } else {
                                $address['province'] = null;
                            }

                            $parsing['city'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_city',
                                'where' => [
                                    'city_id' => $key_address->city_id
                                ]
                            ])->row();
                            if (!empty($parsing['city'])) {
                                $city['id'] = $parsing['city']->city_id;
                                $city['name'] = $parsing['city']->city;
                                $city['kd'] = $parsing['city']->city_kd;

                                $address['city'] = $city;
                            } else {
                                $address['city'] = null;
                            }

                            $parsing['district'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_district',
                                'where' => [
                                    'district_id' => $key_address->district_id
                                ]
                            ])->row();
                            if (!empty($parsing['district'])) {
                                $district['id'] = $parsing['district']->district_id;
                                $district['name'] = $parsing['district']->district;
                                $district['kd'] = $parsing['district']->district_kd;

                                $address['district'] = $district;
                            } else {
                                $address['district'] = null;
                            }

                            $parsing['sub_district'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_sub_district',
                                'where' => [
                                    'sub_district_id' => $key_address->sub_district_id
                                ]
                            ])->row();
                            if (!empty($parsing['sub_district'])) {
                                $sub_district['id'] = $parsing['sub_district']->sub_district_id;
                                $sub_district['name'] = $parsing['sub_district']->sub_district;
                                $sub_district['kd'] = $parsing['sub_district']->sub_district_kd;

                                $address['subDistrict'] = $sub_district;
                            } else {
                                $address['subDistrict'] = null;
                            }

                            $address['postalCode'] = $key_address->postal_code;
                            $address['isPrimary'] = boolval($key_address->is_primary);

                            $data['address'][] = $address;
                        }

                        $response['result']['data'] = $data;
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function register_post()
    {
        $checking = TRUE;

        if (!$this->post()) {
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
            $check['customer'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'tbl_customer',
                'where' => [
                    'LOWER(email)' => trim(strtolower($this->post('email')))
                ]
            ])->row();

            if (!empty($check['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_CONFLICT,
                            'message' => 'email has registered'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            $parsing['gender'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'tbl_gender',
                'where' => [
                    'key' => $this->post('gender')
                ]
            ])->row();

            if (empty($parsing['gender'])) {
                $checking = FALSE;
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

            if ($checking == TRUE) {
                $query = $this->api_model->send_data([
                    'data' => [
                        'name' => $this->post('name'),
                        'birth_date' => $this->post('birthDate'),
                        'phone_number' => $this->post('phoneNumber'),
                        'email' => $this->post('email'),
                        'password' => password_hash($this->post('password'), PASSWORD_DEFAULT),
                        'gender_id' => $parsing['gender']->id,
                    ],
                    'table' => 'tbl_customer'
                ]);

                if ($query['error'] == TRUE) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'add_customer.failed',
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
                                'message' => 'add_customer.success'
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    $parsing['customer'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'tbl_customer',
                        'where' => [
                            'email' => $this->post('email')
                        ]
                    ])->row();

                    $data['id'] = $parsing['customer']->id;
                    $data['name'] = $parsing['customer']->name;
                    $data['email'] = $parsing['customer']->email;
                    $data['phoneNumber'] = $parsing['customer']->phone_number;
                    $data['birthDate'] = $parsing['customer']->birth_date;

                    $gender['id'] = $parsing['gender']->id;
                    $gender['key'] = $parsing['gender']->key;
                    $gender['name'] = $parsing['gender']->name;
                    $data['gender'] = $gender;

                    $data['address'] = null;

                    $response['result']['data'] = $data;
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function login_post()
    {
        $checking = TRUE;

        if (!$this->post()) {
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
            $parsing['customer'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'tbl_customer',
                'where' => [
                    'LOWER(email)' => trim(strtolower($this->post('email')))
                ]
            ])->row();

            if (empty($parsing['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'email not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if (!password_verify($this->post('password'), $parsing['customer']->password)) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'incorrect email or password'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if ($checking == TRUE) {
                $auth_token = $this->authorization->generate_token([
                    'id' => $parsing['customer']->id
                ]);

                if ($auth_token['error'] == FALSE) {
                    $parsing['gender'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'tbl_gender',
                        'where' => [
                            'id' => $parsing['customer']->gender_id
                        ]
                    ])->row();

                    $parsing['address'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'tbl_address',
                        'where' => [
                            'customer_id' => $parsing['customer']->id
                        ]
                    ])->result();

                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_OK,
                                'message' => 'login.success'
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    $data['name'] = $parsing['customer']->name;
                    $data['email'] = $parsing['customer']->email;
                    $data['phoneNumber'] = $parsing['customer']->phone_number;
                    $data['birthDate'] = $parsing['customer']->birth_date;

                    $gender['id'] = $parsing['gender']->id;
                    $gender['key'] = $parsing['gender']->key;
                    $gender['name'] = $parsing['gender']->name;
                    $data['gender'] = $gender;

                    if (!empty($parsing['address'])) {
                        $data['address'] = [];
                        foreach ($parsing['address'] as $key_address) {
                            $address['id'] = $key_address->id;
                            $address['addressAs'] = $key_address->address_as;
                            $address['receiverName'] = $key_address->receiver_name;
                            $address['phoneNumber'] = $key_address->phone_number;
                            $address['mobilePhoneNumber'] = $key_address->mobile_phone_number;
                            $address['completeAddress'] = $key_address->complete_address;

                            $parsing['province'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_province',
                                'where' => [
                                    'province_id' => $key_address->province_id
                                ]
                            ])->row();
                            if (!empty($parsing['province'])) {
                                $province['id'] = $parsing['province']->province_id;
                                $province['name'] = $parsing['province']->province;
                                $province['kd'] = $parsing['province']->province_kd;

                                $address['province'] = $province;
                            } else {
                                $address['province'] = null;
                            }

                            $parsing['city'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_city',
                                'where' => [
                                    'city_id' => $key_address->city_id
                                ]
                            ])->row();
                            if (!empty($parsing['city'])) {
                                $city['id'] = $parsing['city']->city_id;
                                $city['name'] = $parsing['city']->city;
                                $city['kd'] = $parsing['city']->city_kd;

                                $address['city'] = $city;
                            } else {
                                $address['city'] = null;
                            }

                            $parsing['district'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_district',
                                'where' => [
                                    'district_id' => $key_address->district_id
                                ]
                            ])->row();
                            if (!empty($parsing['district'])) {
                                $district['id'] = $parsing['district']->district_id;
                                $district['name'] = $parsing['district']->district;
                                $district['kd'] = $parsing['district']->district_kd;

                                $address['district'] = $district;
                            } else {
                                $address['district'] = null;
                            }

                            $parsing['sub_district'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_sub_district',
                                'where' => [
                                    'sub_district_id' => $key_address->sub_district_id
                                ]
                            ])->row();
                            if (!empty($parsing['sub_district'])) {
                                $sub_district['id'] = $parsing['sub_district']->sub_district_id;
                                $sub_district['name'] = $parsing['sub_district']->sub_district;
                                $sub_district['kd'] = $parsing['sub_district']->sub_district_kd;

                                $address['subDistrict'] = $sub_district;
                            } else {
                                $address['subDistrict'] = null;
                            }

                            $address['postalCode'] = $key_address->postal_code;
                            $address['isPrimary'] = boolval($key_address->is_primary);

                            $data['address'][] = $address;
                        }
                    } else {
                        $data['address'] = null;
                    }

                    $data['token'] = $auth_token['data'];

                    $response['result']['data'] = $data;
                } else {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_UNAUTHORIZED,
                                'message' => $auth_token['data']
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function wishlist_get()
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            $param['wishlist']['field'] = '*';
            $param['wishlist']['table'] = 'tbl_wishlist';
            $param['wishlist']['where'] = [
                'customer_id' => $this->core['customer']->id
            ];
            if ($this->get('page') != null && $this->get('page') != null) {
                $param['wishlist']['limit'] = [
                    $this->get('size') => ($this->get('page') - 1) * $this->get('size')
                ];
            }
            $parsing['wishlist'] = $this->api_model->select_data($param['wishlist'])->result();

            if (empty($parsing['wishlist'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'wishlist empty'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if ($this->get('page') != null && $this->get('page') != null) {
                if ($this->get('page') < 1 || $this->get('size') < 1) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'value must more than 1'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($checking == TRUE) {
                $total_record = $this->api_model->count_all_data($param['wishlist']);

                if ($this->get('page') != null && $this->get('page') != null) {
                    $limit = (int) $this->get('size');
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
                            'message' => 'get wishlist success'
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

                if (!empty($parsing['wishlist'])) {
                    foreach ($parsing['wishlist'] as $key_wishlist) {
                        $data['id'] = $key_wishlist->id;

                        $parsing['products'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'tbl_products',
                            'where' => [
                                'id' => $key_wishlist->products_id
                            ]
                        ])->row();

                        if (!empty($parsing['products'])) {
                            $product['id'] = $parsing['products']->id;
                            $product['name'] = $parsing['products']->name;
                            $product['description'] = $parsing['products']->description;
                            $product['information'] = $parsing['products']->information;
                            $product['url'] = $parsing['products']->url;
                            $product['slug'] = $parsing['products']->slug;
                            $product['isFlashsale'] = boolval($parsing['products']->is_flashsale);
                            $product['isComingSoon'] = boolval($parsing['products']->is_coming_soon);
                            if (!empty($this->core['customer'])) {
                                if ($this->api_model->count_all_data([
                                    'where' => [
                                        'customer_id' => $this->core['customer']->id,
                                        'products_id' => $parsing['products']->id
                                    ],
                                    'table' => 'tbl_wishlist'
                                ]) > 0) {
                                    $isWishlist = true;
                                } else {
                                    $isWishlist = false;
                                }
                            } else {
                                $isWishlist = false;
                            }

                            $product['isWishlist'] = $isWishlist;

                            $parsing['products_images'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'tbl_images',
                                'where' => [
                                    'products_id ' => $parsing['products']->id
                                ]
                            ])->result();
                            if (!empty($parsing['products_images'])) {
                                $product['images'] = [];
                                foreach ($parsing['products_images'] as $key_products_images) {
                                    $products_images['id'] = $key_products_images->id;
                                    $products_images['name'] = $key_products_images->name;
                                    $products_images['link'] = $key_products_images->link;

                                    $product['images'][] = $products_images;
                                }
                            } else {
                                $product['images'] = null;
                            }

                            $parsing['products_variants'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'tbl_variants',
                                'where' => [
                                    'products_id ' => $parsing['products']->id
                                ]
                            ]);
                            if (!empty($parsing['products_variants'])) {
                                if ($parsing['products_variants']->num_rows() > 1) {
                                    $product['variant'] = [];
                                    foreach ($parsing['products_variants']->result() as $key_products_variants) {
                                        $variant['id'] = $key_products_variants->id;
                                        $variant['name'] = $key_products_variants->name;
                                        $variant['discountLabel'] = $key_products_variants->discount_label;
                                        $variant['minimumOrder'] = $key_products_variants->minimum_order;
                                        $variant['maximumOrder'] = $key_products_variants->maximum_order;
                                        $variant['discountPrice'] = $key_products_variants->discount_price;
                                        $variant['discountPriceCurrencyFormat'] = rupiah($key_products_variants->discount_price);
                                        $variant['quantity'] = $key_products_variants->quantity;
                                        $variant['basePrice'] = $key_products_variants->base_price;
                                        $variant['basePriceCurrencyFormat'] = rupiah($key_products_variants->base_price);
                                        $variant['discountPercent'] = $key_products_variants->discount_percent;
                                        $variant['isOutOfStock'] = boolval($key_products_variants->is_out_of_stock);
                                        $variant['mustChooseOption'] = boolval($key_products_variants->must_choose_option);

                                        $product['variant'][] = $variant;
                                    }
                                } else {
                                    $variant['id'] = $parsing['products_variants']->row()->id;
                                    $variant['name'] = $parsing['products_variants']->row()->name;
                                    $variant['discountLabel'] = $parsing['products_variants']->row()->discount_label;
                                    $variant['minimumOrder'] = $parsing['products_variants']->row()->minimum_order;
                                    $variant['maximumOrder'] = $parsing['products_variants']->row()->maximum_order;
                                    $variant['discountPrice'] = $parsing['products_variants']->row()->discount_price;
                                    $variant['discountPriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->discount_price);
                                    $variant['quantity'] = $parsing['products_variants']->row()->quantity;
                                    $variant['basePrice'] = $parsing['products_variants']->row()->base_price;
                                    $variant['basePriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->base_price);
                                    $variant['discountPercent'] = $parsing['products_variants']->row()->discount_percent;
                                    $variant['isOutOfStock'] = boolval($parsing['products_variants']->row()->is_out_of_stock);
                                    $variant['mustChooseOption'] = boolval($parsing['products_variants']->row()->must_choose_option);

                                    $product['variant'] = $variant;
                                }
                            } else {
                                $product['variant'] = null;
                            }

                            $parsing['breadcrumbs_products'] = $this->parsing_breadcrumbs(['products_id' => $parsing['products']->id]);
                            if (!empty($parsing['breadcrumbs_products'])) {
                                $product['breadcrumbs'] = [];
                                foreach ($parsing['breadcrumbs_products'] as $key_breadcrumbs_products) {
                                    $breadcrumbs_products['id'] = $key_breadcrumbs_products->id;
                                    $breadcrumbs_products['name'] = $key_breadcrumbs_products->name;
                                    $breadcrumbs_products['slug'] = $key_breadcrumbs_products->slug;

                                    $product['breadcrumbs'][] = $breadcrumbs_products;
                                }
                            } else {
                                $product['breadcrumbs'] = null;
                            }

                            $product['catalog'] = $parsing['products']->catalog;
                            $product['idChild'] = $parsing['products']->categories_id;

                            $data['product'] = $product;
                        } else {
                            $data['product'] = null;
                        }

                        $response['result']['data'][] = $data;
                    }
                } else {
                    $response['result']['data'] = [];
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function wishlist_post()
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (!$this->post()) {
                $checking = FALSE;
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

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            $check['products'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'tbl_products',
                'where' => [
                    'id' => $this->post('productId')
                ]
            ])->row();

            if (empty($check['products'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'products not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if ($checking == TRUE) {
                $query = $this->api_model->send_data([
                    'data' => [
                        'customer_id' => $this->core['customer']->id,
                        'products_id' => $this->post('productId')
                    ],
                    'table' => 'tbl_wishlist'
                ]);

                if ($query['error'] == TRUE) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'add_wishlist.failed',
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
                                'message' => 'add_wishlist.success'
                            ]
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function wishlist_delete($id = NULL)
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if (empty($id)) {
                $checking = FALSE;
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
                $check['wishlist'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'tbl_wishlist',
                    'where' => [
                        'customer_id' => $this->core['customer']->id,
                        'products_id' => $id
                    ]
                ])->row();

                if (empty($check['wishlist'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'wishlist not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($this->input->get('page') != null && $this->input->get('page') != null) {
                if ($this->input->get('page') < 1 || $this->input->get('size') < 1) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'value must more than 1'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($checking == TRUE) {
                $query = $this->api_model->delete_data([
                    'where' => [
                        'customer_id' => $this->core['customer']->id,
                        'products_id' => $id
                    ],
                    'table' => 'tbl_wishlist'
                ]);

                if ($query['error'] == TRUE) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'remove wishlist failed',
                                'from_system' => $query['system']
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                } else {
                    $param['wishlist']['field'] = '*';
                    $param['wishlist']['table'] = 'tbl_wishlist';
                    $param['wishlist']['where'] = [
                        'customer_id' => $this->core['customer']->id
                    ];
                    if ($this->input->get('page') != null && $this->input->get('page') != null) {
                        $param['wishlist']['limit'] = [
                            $this->input->get('size') => ($this->input->get('page') - 1) * $this->input->get('size')
                        ];
                    }
                    $parsing['wishlist'] = $this->api_model->select_data($param['wishlist'])->result();

                    $total_record = $this->api_model->count_all_data($param['wishlist']);

                    if ($this->input->get('page') != null && $this->input->get('page') != null) {
                        $limit = (int) $this->input->get('size');
                        $current_page = (int) $this->input->get('page');
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
                                'message' => 'remove wishlist success'
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

                    if (!empty($parsing['wishlist'])) {
                        foreach ($parsing['wishlist'] as $key_wishlist) {
                            $data['id'] = $key_wishlist->id;

                            $parsing['products'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'tbl_products',
                                'where' => [
                                    'id' => $key_wishlist->products_id
                                ]
                            ])->row();

                            if (!empty($parsing['products'])) {
                                $product['id'] = $parsing['products']->id;
                                $product['name'] = $parsing['products']->name;
                                $product['description'] = $parsing['products']->description;
                                $product['information'] = $parsing['products']->information;
                                $product['url'] = $parsing['products']->url;
                                $product['slug'] = $parsing['products']->slug;
                                $product['isFlashsale'] = boolval($parsing['products']->is_flashsale);
                                $product['isComingSoon'] = boolval($parsing['products']->is_coming_soon);
                                if (!empty($this->core['customer'])) {
                                    if ($this->api_model->count_all_data([
                                        'where' => [
                                            'customer_id' => $this->core['customer']->id,
                                            'products_id' => $parsing['products']->id
                                        ],
                                        'table' => 'tbl_wishlist'
                                    ]) > 0) {
                                        $isWishlist = true;
                                    } else {
                                        $isWishlist = false;
                                    }
                                } else {
                                    $isWishlist = false;
                                }

                                $product['isWishlist'] = $isWishlist;

                                $parsing['products_images'] = $this->api_model->select_data([
                                    'field' => '*',
                                    'table' => 'tbl_images',
                                    'where' => [
                                        'products_id ' => $parsing['products']->id
                                    ]
                                ])->result();
                                if (!empty($parsing['products_images'])) {
                                    $product['images'] = [];
                                    foreach ($parsing['products_images'] as $key_products_images) {
                                        $products_images['id'] = $key_products_images->id;
                                        $products_images['name'] = $key_products_images->name;
                                        $products_images['link'] = $key_products_images->link;

                                        $product['images'][] = $products_images;
                                    }
                                } else {
                                    $product['images'] = null;
                                }

                                $parsing['products_variants'] = $this->api_model->select_data([
                                    'field' => '*',
                                    'table' => 'tbl_variants',
                                    'where' => [
                                        'products_id ' => $parsing['products']->id
                                    ]
                                ]);
                                if (!empty($parsing['products_variants'])) {
                                    if ($parsing['products_variants']->num_rows() > 1) {
                                        $product['variant'] = [];
                                        foreach ($parsing['products_variants']->result() as $key_products_variants) {
                                            $variant['id'] = $key_products_variants->id;
                                            $variant['name'] = $key_products_variants->name;
                                            $variant['discountLabel'] = $key_products_variants->discount_label;
                                            $variant['minimumOrder'] = $key_products_variants->minimum_order;
                                            $variant['maximumOrder'] = $key_products_variants->maximum_order;
                                            $variant['discountPrice'] = $key_products_variants->discount_price;
                                            $variant['discountPriceCurrencyFormat'] = rupiah($key_products_variants->discount_price);
                                            $variant['quantity'] = $key_products_variants->quantity;
                                            $variant['basePrice'] = $key_products_variants->base_price;
                                            $variant['basePriceCurrencyFormat'] = rupiah($key_products_variants->base_price);
                                            $variant['discountPercent'] = $key_products_variants->discount_percent;
                                            $variant['isOutOfStock'] = boolval($key_products_variants->is_out_of_stock);
                                            $variant['mustChooseOption'] = boolval($key_products_variants->must_choose_option);

                                            $product['variant'][] = $variant;
                                        }
                                    } else {
                                        $variant['id'] = $parsing['products_variants']->row()->id;
                                        $variant['name'] = $parsing['products_variants']->row()->name;
                                        $variant['discountLabel'] = $parsing['products_variants']->row()->discount_label;
                                        $variant['minimumOrder'] = $parsing['products_variants']->row()->minimum_order;
                                        $variant['maximumOrder'] = $parsing['products_variants']->row()->maximum_order;
                                        $variant['discountPrice'] = $parsing['products_variants']->row()->discount_price;
                                        $variant['discountPriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->discount_price);
                                        $variant['quantity'] = $parsing['products_variants']->row()->quantity;
                                        $variant['basePrice'] = $parsing['products_variants']->row()->base_price;
                                        $variant['basePriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->base_price);
                                        $variant['discountPercent'] = $parsing['products_variants']->row()->discount_percent;
                                        $variant['isOutOfStock'] = boolval($parsing['products_variants']->row()->is_out_of_stock);
                                        $variant['mustChooseOption'] = boolval($parsing['products_variants']->row()->must_choose_option);

                                        $product['variant'] = $variant;
                                    }
                                } else {
                                    $product['variant'] = null;
                                }

                                $parsing['breadcrumbs_products'] = $this->parsing_breadcrumbs(['products_id' => $parsing['products']->id]);
                                if (!empty($parsing['breadcrumbs_products'])) {
                                    $product['breadcrumbs'] = [];
                                    foreach ($parsing['breadcrumbs_products'] as $key_breadcrumbs_products) {
                                        $breadcrumbs_products['id'] = $key_breadcrumbs_products->id;
                                        $breadcrumbs_products['name'] = $key_breadcrumbs_products->name;
                                        $breadcrumbs_products['slug'] = $key_breadcrumbs_products->slug;

                                        $product['breadcrumbs'][] = $breadcrumbs_products;
                                    }
                                } else {
                                    $product['breadcrumbs'] = null;
                                }

                                $product['catalog'] = $parsing['products']->catalog;
                                $product['idChild'] = $parsing['products']->categories_id;

                                $data['product'] = $product;
                            } else {
                                $data['product'] = null;
                            }

                            $response['result']['data'][] = $data;
                        }
                    } else {
                        $response['result']['data'] = [];
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function cart_get()
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'customer not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            $param['cart']['field'] = '*';
            $param['cart']['table'] = 'tbl_cart';
            $param['cart']['where'] = [
                'customer_id' => $this->core['customer']->id
            ];
            if ($this->get('page') != null && $this->get('page') != null) {
                $param['cart']['limit'] = [
                    $this->get('size') => ($this->get('page') - 1) * $this->get('size')
                ];
            }
            $parsing['cart'] = $this->api_model->select_data($param['cart'])->result();

            if (empty($parsing['cart'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'cart empty'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if ($this->get('page') != null && $this->get('page') != null) {
                if ($this->get('page') < 1 || $this->get('size') < 1) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'value must more than 1'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($checking == TRUE) {
                $count['cart'] = $this->api_model->select_data([
                    'field' => 'tbl_variants.discount_price, tbl_cart.qty',
                    'table' => 'tbl_cart',
                    'join' => [
                        [
                            'table' => 'tbl_variants',
                            'on' => 'tbl_variants.products_id = tbl_cart.products_id',
                            'type' => 'inner'
                        ]
                    ],
                    'where' => [
                        'tbl_cart.customer_id' => $this->core['customer']->id,
                    ]
                ])->result();
                if (!empty($count['cart'])) {
                    $subTotal = [];
                    foreach ($count['cart'] as $key_count_cart) {
                        $subTotal[] = $key_count_cart->discount_price * $key_count_cart->qty;
                    }
                } else {
                    $subTotal = null;
                }

                $total_record = $this->api_model->count_all_data($param['cart']);

                if ($this->get('page') != null && $this->get('page') != null) {
                    $limit = (int) $this->get('size');
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
                            'message' => 'get cart success'
                        ],
                        'meta' => [
                            'record' => [
                                'limit' => $limit,
                                'total' => $total_record
                            ],
                            'page' => [
                                'current' => $current_page,
                                'total' => $total_page
                            ],
                            'subTotal' => (is_array($subTotal)) ? array_sum($subTotal) : $subTotal,
                            'subTotalCurrencyFormat' => (is_array($subTotal)) ? rupiah(array_sum($subTotal)) : $subTotal
                        ],
                        'data' => []
                    ],
                    'status' => SELF::HTTP_OK
                ];

                if (!empty($parsing['cart'])) {
                    foreach ($parsing['cart'] as $key_cart) {
                        $data['id'] = $key_cart->id;
                        $data['qty'] = $key_cart->qty;

                        $parsing['products'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'tbl_products',
                            'where' => [
                                'id' => $key_cart->products_id
                            ]
                        ])->row();

                        if (!empty($parsing['products'])) {
                            $product['id'] = $parsing['products']->id;
                            $product['name'] = $parsing['products']->name;
                            $product['description'] = $parsing['products']->description;
                            $product['information'] = $parsing['products']->information;
                            $product['url'] = $parsing['products']->url;
                            $product['slug'] = $parsing['products']->slug;
                            $product['isFlashsale'] = boolval($parsing['products']->is_flashsale);
                            $product['isComingSoon'] = boolval($parsing['products']->is_coming_soon);
                            $product['createdAt'] = $parsing['products']->created_at;

                            $parsing['products_images'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'tbl_images',
                                'where' => [
                                    'products_id ' => $parsing['products']->id
                                ]
                            ])->result();
                            if (!empty($parsing['products_images'])) {
                                $product['images'] = [];
                                foreach ($parsing['products_images'] as $key_products_images) {
                                    $products_images['id'] = $key_products_images->id;
                                    $products_images['name'] = $key_products_images->name;
                                    $products_images['link'] = $key_products_images->link;

                                    $product['images'][] = $products_images;
                                }
                            } else {
                                $product['images'] = null;
                            }

                            $parsing['products_variants'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'tbl_variants',
                                'where' => [
                                    'products_id ' => $parsing['products']->id
                                ]
                            ]);
                            if (!empty($parsing['products_variants'])) {
                                if ($parsing['products_variants']->num_rows() > 1) {
                                    $product['variant'] = [];
                                    foreach ($parsing['products_variants']->result() as $key_products_variants) {
                                        $variant['id'] = $key_products_variants->id;
                                        $variant['name'] = $key_products_variants->name;
                                        $variant['discountLabel'] = $key_products_variants->discount_label;
                                        $variant['minimumOrder'] = $key_products_variants->minimum_order;
                                        $variant['maximumOrder'] = $key_products_variants->maximum_order;
                                        $variant['discountPrice'] = $key_products_variants->discount_price;
                                        $variant['discountPriceCurrencyFormat'] = rupiah($key_products_variants->discount_price);
                                        $variant['quantity'] = $key_products_variants->quantity;
                                        $variant['basePrice'] = $key_products_variants->base_price;
                                        $variant['basePriceCurrencyFormat'] = rupiah($key_products_variants->base_price);
                                        $variant['discountPercent'] = $key_products_variants->discount_percent;
                                        $variant['isOutOfStock'] = boolval($key_products_variants->is_out_of_stock);
                                        $variant['mustChooseOption'] = boolval($key_products_variants->must_choose_option);

                                        $product['variant'][] = $variant;
                                    }
                                } else {
                                    $variant['id'] = $parsing['products_variants']->row()->id;
                                    $variant['name'] = $parsing['products_variants']->row()->name;
                                    $variant['discountLabel'] = $parsing['products_variants']->row()->discount_label;
                                    $variant['minimumOrder'] = $parsing['products_variants']->row()->minimum_order;
                                    $variant['maximumOrder'] = $parsing['products_variants']->row()->maximum_order;
                                    $variant['discountPrice'] = $parsing['products_variants']->row()->discount_price;
                                    $variant['discountPriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->discount_price);
                                    $variant['quantity'] = $parsing['products_variants']->row()->quantity;
                                    $variant['basePrice'] = $parsing['products_variants']->row()->base_price;
                                    $variant['basePriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->base_price);
                                    $variant['discountPercent'] = $parsing['products_variants']->row()->discount_percent;
                                    $variant['isOutOfStock'] = boolval($parsing['products_variants']->row()->is_out_of_stock);
                                    $variant['mustChooseOption'] = boolval($parsing['products_variants']->row()->must_choose_option);

                                    $product['variant'] = $variant;
                                }
                            } else {
                                $product['variant'] = null;
                            }

                            $parsing['breadcrumbs_products'] = $this->parsing_breadcrumbs(['products_id' => $parsing['products']->id]);
                            if (!empty($parsing['breadcrumbs_products'])) {
                                $product['breadcrumbs'] = [];
                                foreach ($parsing['breadcrumbs_products'] as $key_breadcrumbs_products) {
                                    $breadcrumbs_products['id'] = $key_breadcrumbs_products->id;
                                    $breadcrumbs_products['name'] = $key_breadcrumbs_products->name;
                                    $breadcrumbs_products['slug'] = $key_breadcrumbs_products->slug;

                                    $product['breadcrumbs'][] = $breadcrumbs_products;
                                }
                            } else {
                                $product['breadcrumbs'] = null;
                            }

                            $product['catalog'] = $parsing['products']->catalog;
                            $product['idChild'] = $parsing['products']->categories_id;

                            $data['product'] = $product;
                        } else {
                            $data['product'] = null;
                        }

                        $response['result']['data'][] = $data;
                    }
                } else {
                    $response['result']['data'] = [];
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function cart_post()
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (!$this->post()) {
                $checking = FALSE;
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

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            $check['products'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'tbl_products',
                'where' => [
                    'id' => $this->post('productId')
                ]
            ])->row();

            if (empty($check['products'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'products not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            $check['cart'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'tbl_cart',
                'where' => [
                    'customer_id' => $this->core['customer']->id,
                    'products_id' => $this->post('productId')
                ]
            ])->row();

            if (!empty($check['cart'])) {
                $check['variants'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'tbl_variants',
                    'where' => [
                        'products_id' => $this->post('productId')
                    ]
                ])->row();

                $qty = $this->post('qty') + $check['cart']->qty;

                if ($qty > $check['variants']->quantity) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'quantity cannot more than stock'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                } elseif ($qty < 1) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'quantity cannot less than 1'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($this->input->get('page') != null && $this->input->get('page') != null) {
                if ($this->input->get('page') < 1 || $this->input->get('size') < 1) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'value must more than 1'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($checking == TRUE) {
                if (!empty($check['cart'])) {
                    $query = $this->api_model->send_data([
                        'where' => [
                            'customer_id' => $this->core['customer']->id,
                            'products_id' => $this->post('productId')
                        ],
                        'data' => [
                            'qty' => $qty
                        ],
                        'table' => 'tbl_cart'
                    ]);
                } else {
                    $query = $this->api_model->send_data([
                        'data' => [
                            'customer_id' => $this->core['customer']->id,
                            'products_id' => $this->post('productId'),
                            'qty' => $this->post('qty')
                        ],
                        'table' => 'tbl_cart'
                    ]);
                }

                if ($query['error'] == TRUE) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'add_cart.failed',
                                'from_system' => $query['system']
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                } else {
                    $count['cart'] = $this->api_model->select_data([
                        'field' => 'tbl_variants.discount_price, tbl_cart.qty',
                        'table' => 'tbl_cart',
                        'join' => [
                            [
                                'table' => 'tbl_variants',
                                'on' => 'tbl_variants.products_id = tbl_cart.products_id',
                                'type' => 'inner'
                            ]
                        ],
                        'where' => [
                            'tbl_cart.customer_id' => $this->core['customer']->id,
                        ]
                    ])->result();
                    if (!empty($count['cart'])) {
                        $subTotal = [];
                        foreach ($count['cart'] as $key_count_cart) {
                            $subTotal[] = $key_count_cart->discount_price * $key_count_cart->qty;
                        }
                    } else {
                        $subTotal = null;
                    }

                    $param['cart']['field'] = '*';
                    $param['cart']['table'] = 'tbl_cart';
                    $param['cart']['where'] = [
                        'customer_id' => $this->core['customer']->id
                    ];
                    if ($this->input->get('page') != null && $this->input->get('page') != null) {
                        $param['cart']['limit'] = [
                            $this->input->get('size') => ($this->input->get('page') - 1) * $this->input->get('size')
                        ];
                    }
                    $parsing['cart'] = $this->api_model->select_data($param['cart'])->result();

                    $total_record = $this->api_model->count_all_data($param['cart']);

                    if ($this->input->get('page') != null && $this->input->get('page') != null) {
                        $limit = (int) $this->input->get('size');
                        $current_page = (int) $this->input->get('page');
                        $total_page = ceil($total_record / $limit);
                    } else {
                        $limit = null;
                        $current_page = null;
                        $total_page = null;
                    }

                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_CREATED,
                                'message' => 'add_cart.success'
                            ],
                            'meta' => [
                                'record' => [
                                    'limit' => $limit,
                                    'total' => $total_record
                                ],
                                'page' => [
                                    'current' => $current_page,
                                    'total' => $total_page
                                ],
                                'subTotal' => (is_array($subTotal)) ? array_sum($subTotal) : $subTotal,
                                'subTotalCurrencyFormat' => (is_array($subTotal)) ? rupiah(array_sum($subTotal)) : $subTotal
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    if (!empty($parsing['cart'])) {
                        foreach ($parsing['cart'] as $key_cart) {
                            $data['id'] = $key_cart->id;
                            $data['qty'] = $key_cart->qty;

                            $parsing['products'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'tbl_products',
                                'where' => [
                                    'id' => $key_cart->products_id
                                ]
                            ])->row();

                            if (!empty($parsing['products'])) {
                                $product['id'] = $parsing['products']->id;
                                $product['name'] = $parsing['products']->name;
                                $product['description'] = $parsing['products']->description;
                                $product['information'] = $parsing['products']->information;
                                $product['url'] = $parsing['products']->url;
                                $product['slug'] = $parsing['products']->slug;
                                $product['isFlashsale'] = boolval($parsing['products']->is_flashsale);
                                $product['isComingSoon'] = boolval($parsing['products']->is_coming_soon);
                                $product['createdAt'] = $parsing['products']->created_at;

                                $parsing['products_images'] = $this->api_model->select_data([
                                    'field' => '*',
                                    'table' => 'tbl_images',
                                    'where' => [
                                        'products_id ' => $parsing['products']->id
                                    ]
                                ])->result();
                                if (!empty($parsing['products_images'])) {
                                    $product['images'] = [];
                                    foreach ($parsing['products_images'] as $key_products_images) {
                                        $products_images['id'] = $key_products_images->id;
                                        $products_images['name'] = $key_products_images->name;
                                        $products_images['link'] = $key_products_images->link;

                                        $product['images'][] = $products_images;
                                    }
                                } else {
                                    $product['images'] = null;
                                }

                                $parsing['products_variants'] = $this->api_model->select_data([
                                    'field' => '*',
                                    'table' => 'tbl_variants',
                                    'where' => [
                                        'products_id ' => $parsing['products']->id
                                    ]
                                ]);
                                if (!empty($parsing['products_variants'])) {
                                    if ($parsing['products_variants']->num_rows() > 1) {
                                        $product['variant'] = [];
                                        foreach ($parsing['products_variants']->result() as $key_products_variants) {
                                            $variant['id'] = $key_products_variants->id;
                                            $variant['name'] = $key_products_variants->name;
                                            $variant['discountLabel'] = $key_products_variants->discount_label;
                                            $variant['minimumOrder'] = $key_products_variants->minimum_order;
                                            $variant['maximumOrder'] = $key_products_variants->maximum_order;
                                            $variant['discountPrice'] = $key_products_variants->discount_price;
                                            $variant['discountPriceCurrencyFormat'] = rupiah($key_products_variants->discount_price);
                                            $variant['quantity'] = $key_products_variants->quantity;
                                            $variant['basePrice'] = $key_products_variants->base_price;
                                            $variant['basePriceCurrencyFormat'] = rupiah($key_products_variants->base_price);
                                            $variant['discountPercent'] = $key_products_variants->discount_percent;
                                            $variant['isOutOfStock'] = boolval($key_products_variants->is_out_of_stock);
                                            $variant['mustChooseOption'] = boolval($key_products_variants->must_choose_option);

                                            $product['variant'][] = $variant;
                                        }
                                    } else {
                                        $variant['id'] = $parsing['products_variants']->row()->id;
                                        $variant['name'] = $parsing['products_variants']->row()->name;
                                        $variant['discountLabel'] = $parsing['products_variants']->row()->discount_label;
                                        $variant['minimumOrder'] = $parsing['products_variants']->row()->minimum_order;
                                        $variant['maximumOrder'] = $parsing['products_variants']->row()->maximum_order;
                                        $variant['discountPrice'] = $parsing['products_variants']->row()->discount_price;
                                        $variant['discountPriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->discount_price);
                                        $variant['quantity'] = $parsing['products_variants']->row()->quantity;
                                        $variant['basePrice'] = $parsing['products_variants']->row()->base_price;
                                        $variant['basePriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->base_price);
                                        $variant['discountPercent'] = $parsing['products_variants']->row()->discount_percent;
                                        $variant['isOutOfStock'] = boolval($parsing['products_variants']->row()->is_out_of_stock);
                                        $variant['mustChooseOption'] = boolval($parsing['products_variants']->row()->must_choose_option);

                                        $product['variant'] = $variant;
                                    }
                                } else {
                                    $product['variant'] = null;
                                }

                                $parsing['breadcrumbs_products'] = $this->parsing_breadcrumbs(['products_id' => $parsing['products']->id]);
                                if (!empty($parsing['breadcrumbs_products'])) {
                                    $product['breadcrumbs'] = [];
                                    foreach ($parsing['breadcrumbs_products'] as $key_breadcrumbs_products) {
                                        $breadcrumbs_products['id'] = $key_breadcrumbs_products->id;
                                        $breadcrumbs_products['name'] = $key_breadcrumbs_products->name;
                                        $breadcrumbs_products['slug'] = $key_breadcrumbs_products->slug;

                                        $product['breadcrumbs'][] = $breadcrumbs_products;
                                    }
                                } else {
                                    $product['breadcrumbs'] = null;
                                }

                                $product['catalog'] = $parsing['products']->catalog;
                                $product['idChild'] = $parsing['products']->categories_id;

                                $data['product'] = $product;
                            } else {
                                $data['product'] = null;
                            }

                            $response['result']['data'][] = $data;
                        }
                    } else {
                        $response['result']['data'] = [];
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function cart_delete($id = NULL)
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if (empty($id)) {
                $checking = FALSE;
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
                $check['cart'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'tbl_cart',
                    'where' => [
                        'customer_id' => $this->core['customer']->id,
                        'products_id' => $id
                    ]
                ])->row();

                if (empty($check['cart'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'cart not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($this->input->get('page') != null && $this->input->get('page') != null) {
                if ($this->input->get('page') < 1 || $this->input->get('size') < 1) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'value must more than 1'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($checking == TRUE) {
                $query = $this->api_model->delete_data([
                    'where' => [
                        'customer_id' => $this->core['customer']->id,
                        'products_id' => $id
                    ],
                    'table' => 'tbl_cart'
                ]);

                if ($query['error'] == TRUE) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'remove_cart.failed',
                                'from_system' => $query['system']
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                } else {
                    $count['cart'] = $this->api_model->select_data([
                        'field' => 'tbl_variants.discount_price, tbl_cart.qty',
                        'table' => 'tbl_cart',
                        'join' => [
                            [
                                'table' => 'tbl_variants',
                                'on' => 'tbl_variants.products_id = tbl_cart.products_id',
                                'type' => 'inner'
                            ]
                        ],
                        'where' => [
                            'tbl_cart.customer_id' => $this->core['customer']->id,
                        ]
                    ])->result();
                    if (!empty($count['cart'])) {
                        $subTotal = [];
                        foreach ($count['cart'] as $key_count_cart) {
                            $subTotal[] = $key_count_cart->discount_price * $key_count_cart->qty;
                        }
                    } else {
                        $subTotal = null;
                    }

                    $param['cart']['field'] = '*';
                    $param['cart']['table'] = 'tbl_cart';
                    $param['cart']['where'] = [
                        'customer_id' => $this->core['customer']->id
                    ];
                    if ($this->input->get('page') != null && $this->input->get('page') != null) {
                        $param['cart']['limit'] = [
                            $this->input->get('size') => ($this->input->get('page') - 1) * $this->input->get('size')
                        ];
                    }
                    $parsing['cart'] = $this->api_model->select_data($param['cart'])->result();

                    $total_record = $this->api_model->count_all_data($param['cart']);

                    if ($this->input->get('page') != null && $this->input->get('page') != null) {
                        $limit = (int) $this->input->get('size');
                        $current_page = (int) $this->input->get('page');
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
                                'message' => 'remove_cart.success'
                            ],
                            'meta' => [
                                'record' => [
                                    'limit' => $limit,
                                    'total' => $total_record
                                ],
                                'page' => [
                                    'current' => $current_page,
                                    'total' => $total_page
                                ],
                                'subTotal' => (is_array($subTotal)) ? array_sum($subTotal) : $subTotal,
                                'subTotalCurrencyFormat' => (is_array($subTotal)) ? rupiah(array_sum($subTotal)) : $subTotal
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    if (!empty($parsing['cart'])) {
                        foreach ($parsing['cart'] as $key_cart) {
                            $data['id'] = $key_cart->id;
                            $data['qty'] = $key_cart->qty;

                            $parsing['products'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'tbl_products',
                                'where' => [
                                    'id' => $key_cart->products_id
                                ]
                            ])->row();

                            if (!empty($parsing['products'])) {
                                $product['id'] = $parsing['products']->id;
                                $product['name'] = $parsing['products']->name;
                                $product['description'] = $parsing['products']->description;
                                $product['information'] = $parsing['products']->information;
                                $product['url'] = $parsing['products']->url;
                                $product['slug'] = $parsing['products']->slug;
                                $product['isFlashsale'] = boolval($parsing['products']->is_flashsale);
                                $product['isComingSoon'] = boolval($parsing['products']->is_coming_soon);
                                $product['createdAt'] = $parsing['products']->created_at;

                                $parsing['products_images'] = $this->api_model->select_data([
                                    'field' => '*',
                                    'table' => 'tbl_images',
                                    'where' => [
                                        'products_id ' => $parsing['products']->id
                                    ]
                                ])->result();
                                if (!empty($parsing['products_images'])) {
                                    $product['images'] = [];
                                    foreach ($parsing['products_images'] as $key_products_images) {
                                        $products_images['id'] = $key_products_images->id;
                                        $products_images['name'] = $key_products_images->name;
                                        $products_images['link'] = $key_products_images->link;

                                        $product['images'][] = $products_images;
                                    }
                                } else {
                                    $product['images'] = null;
                                }

                                $parsing['products_variants'] = $this->api_model->select_data([
                                    'field' => '*',
                                    'table' => 'tbl_variants',
                                    'where' => [
                                        'products_id ' => $parsing['products']->id
                                    ]
                                ]);
                                if (!empty($parsing['products_variants'])) {
                                    if ($parsing['products_variants']->num_rows() > 1) {
                                        $product['variant'] = [];
                                        foreach ($parsing['products_variants']->result() as $key_products_variants) {
                                            $variant['id'] = $key_products_variants->id;
                                            $variant['name'] = $key_products_variants->name;
                                            $variant['discountLabel'] = $key_products_variants->discount_label;
                                            $variant['minimumOrder'] = $key_products_variants->minimum_order;
                                            $variant['maximumOrder'] = $key_products_variants->maximum_order;
                                            $variant['discountPrice'] = $key_products_variants->discount_price;
                                            $variant['discountPriceCurrencyFormat'] = rupiah($key_products_variants->discount_price);
                                            $variant['quantity'] = $key_products_variants->quantity;
                                            $variant['basePrice'] = $key_products_variants->base_price;
                                            $variant['basePriceCurrencyFormat'] = rupiah($key_products_variants->base_price);
                                            $variant['discountPercent'] = $key_products_variants->discount_percent;
                                            $variant['isOutOfStock'] = boolval($key_products_variants->is_out_of_stock);
                                            $variant['mustChooseOption'] = boolval($key_products_variants->must_choose_option);

                                            $product['variant'][] = $variant;
                                        }
                                    } else {
                                        $variant['id'] = $parsing['products_variants']->row()->id;
                                        $variant['name'] = $parsing['products_variants']->row()->name;
                                        $variant['discountLabel'] = $parsing['products_variants']->row()->discount_label;
                                        $variant['minimumOrder'] = $parsing['products_variants']->row()->minimum_order;
                                        $variant['maximumOrder'] = $parsing['products_variants']->row()->maximum_order;
                                        $variant['discountPrice'] = $parsing['products_variants']->row()->discount_price;
                                        $variant['discountPriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->discount_price);
                                        $variant['quantity'] = $parsing['products_variants']->row()->quantity;
                                        $variant['basePrice'] = $parsing['products_variants']->row()->base_price;
                                        $variant['basePriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->base_price);
                                        $variant['discountPercent'] = $parsing['products_variants']->row()->discount_percent;
                                        $variant['isOutOfStock'] = boolval($parsing['products_variants']->row()->is_out_of_stock);
                                        $variant['mustChooseOption'] = boolval($parsing['products_variants']->row()->must_choose_option);

                                        $product['variant'] = $variant;
                                    }
                                } else {
                                    $product['variant'] = null;
                                }

                                $parsing['breadcrumbs_products'] = $this->parsing_breadcrumbs(['products_id' => $parsing['products']->id]);
                                if (!empty($parsing['breadcrumbs_products'])) {
                                    $product['breadcrumbs'] = [];
                                    foreach ($parsing['breadcrumbs_products'] as $key_breadcrumbs_products) {
                                        $breadcrumbs_products['id'] = $key_breadcrumbs_products->id;
                                        $breadcrumbs_products['name'] = $key_breadcrumbs_products->name;
                                        $breadcrumbs_products['slug'] = $key_breadcrumbs_products->slug;

                                        $product['breadcrumbs'][] = $breadcrumbs_products;
                                    }
                                } else {
                                    $product['breadcrumbs'] = null;
                                }

                                $product['catalog'] = $parsing['products']->catalog;
                                $product['idChild'] = $parsing['products']->categories_id;

                                $data['product'] = $product;
                            } else {
                                $data['product'] = null;
                            }

                            $response['result']['data'][] = $data;
                        }
                    } else {
                        $response['result']['data'] = [];
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function qty_post()
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (!$this->post()) {
                $checking = FALSE;
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

            $check['cart'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'tbl_cart',
                'where' => [
                    'customer_id' => $this->core['customer']->id,
                    'products_id' => $this->post('productId')
                ]
            ])->row();

            if (empty($check['cart'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'cart not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            $forAct = [
                'change', 'add', 'less'
            ];

            if (!in_array($this->input->get('forAct'), $forAct)) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'action not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            } else {
                $check['variants'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'tbl_variants',
                    'where' => [
                        'products_id' => $this->post('productId')
                    ]
                ])->row();

                if ($this->input->get('forAct') == 'change') {
                    $qty = $this->post('qty');
                } elseif ($this->input->get('forAct') == 'add') {
                    $qty = $check['cart']->qty + 1;
                } elseif ($this->input->get('forAct') == 'less') {
                    $qty = $check['cart']->qty - 1;
                }

                if ($qty > $check['variants']->quantity) {
                    if ($this->input->get('forAct') == 'change') {
                        $qty = $check['variants']->quantity;
                    } else {
                        $checking = FALSE;
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_BAD_REQUEST,
                                    'message' => 'quantity cannot more than stock'
                                ],
                                'data' => null
                            ],
                            'status' => SELF::HTTP_OK
                        ];
                    }
                } elseif ($qty < 1) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'quantity cannot less than 1'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            $check['products'] = $this->api_model->select_data([
                'field' => '*',
                'table' => 'tbl_products',
                'where' => [
                    'id' => $this->post('productId')
                ]
            ])->row();

            if (empty($check['products'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_NOT_FOUND,
                            'message' => 'products not found'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if ($this->input->get('page') != null && $this->input->get('page') != null) {
                if ($this->input->get('page') < 1 || $this->input->get('size') < 1) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'value must more than 1'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($checking == TRUE) {
                $query = $this->api_model->send_data([
                    'where' => [
                        'customer_id' => $this->core['customer']->id,
                        'products_id' => $this->post('productId')
                    ],
                    'data' => [
                        'qty' => $qty
                    ],
                    'table' => 'tbl_cart'
                ]);

                if ($query['error'] == TRUE) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'change_cart.failed',
                                'from_system' => $query['system']
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                } else {
                    $count['cart'] = $this->api_model->select_data([
                        'field' => 'tbl_variants.discount_price, tbl_cart.qty',
                        'table' => 'tbl_cart',
                        'join' => [
                            [
                                'table' => 'tbl_variants',
                                'on' => 'tbl_variants.products_id = tbl_cart.products_id',
                                'type' => 'inner'
                            ]
                        ],
                        'where' => [
                            'tbl_cart.customer_id' => $this->core['customer']->id,
                        ]
                    ])->result();
                    if (!empty($count['cart'])) {
                        $subTotal = [];
                        foreach ($count['cart'] as $key_count_cart) {
                            $subTotal[] = $key_count_cart->discount_price * $key_count_cart->qty;
                        }
                    } else {
                        $subTotal = null;
                    }

                    $param['cart']['field'] = '*';
                    $param['cart']['table'] = 'tbl_cart';
                    $param['cart']['where'] = [
                        'customer_id' => $this->core['customer']->id
                    ];
                    if ($this->input->get('page') != null && $this->input->get('page') != null) {
                        $param['cart']['limit'] = [
                            $this->input->get('size') => ($this->input->get('page') - 1) * $this->input->get('size')
                        ];
                    }
                    $parsing['cart'] = $this->api_model->select_data($param['cart'])->result();

                    $total_record = $this->api_model->count_all_data($param['cart']);

                    if ($this->input->get('page') != null && $this->input->get('page') != null) {
                        $limit = (int) $this->input->get('size');
                        $current_page = (int) $this->input->get('page');
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
                                'message' => 'change_cart.success'
                            ],
                            'meta' => [
                                'record' => [
                                    'limit' => $limit,
                                    'total' => $total_record
                                ],
                                'page' => [
                                    'current' => $current_page,
                                    'total' => $total_page
                                ],
                                'subTotal' => (is_array($subTotal)) ? array_sum($subTotal) : $subTotal,
                                'subTotalCurrencyFormat' => (is_array($subTotal)) ? rupiah(array_sum($subTotal)) : $subTotal
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    if (!empty($parsing['cart'])) {
                        foreach ($parsing['cart'] as $key_cart) {
                            $data['id'] = $key_cart->id;
                            $data['qty'] = $key_cart->qty;

                            $parsing['products'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'tbl_products',
                                'where' => [
                                    'id' => $key_cart->products_id
                                ]
                            ])->row();

                            if (!empty($parsing['products'])) {
                                $product['id'] = $parsing['products']->id;
                                $product['name'] = $parsing['products']->name;
                                $product['description'] = $parsing['products']->description;
                                $product['information'] = $parsing['products']->information;
                                $product['url'] = $parsing['products']->url;
                                $product['slug'] = $parsing['products']->slug;
                                $product['isFlashsale'] = boolval($parsing['products']->is_flashsale);
                                $product['isComingSoon'] = boolval($parsing['products']->is_coming_soon);
                                $product['createdAt'] = $parsing['products']->created_at;

                                $parsing['products_images'] = $this->api_model->select_data([
                                    'field' => '*',
                                    'table' => 'tbl_images',
                                    'where' => [
                                        'products_id ' => $parsing['products']->id
                                    ]
                                ])->result();
                                if (!empty($parsing['products_images'])) {
                                    $product['images'] = [];
                                    foreach ($parsing['products_images'] as $key_products_images) {
                                        $products_images['id'] = $key_products_images->id;
                                        $products_images['name'] = $key_products_images->name;
                                        $products_images['link'] = $key_products_images->link;

                                        $product['images'][] = $products_images;
                                    }
                                } else {
                                    $product['images'] = null;
                                }

                                $parsing['products_variants'] = $this->api_model->select_data([
                                    'field' => '*',
                                    'table' => 'tbl_variants',
                                    'where' => [
                                        'products_id ' => $parsing['products']->id
                                    ]
                                ]);
                                if (!empty($parsing['products_variants'])) {
                                    if ($parsing['products_variants']->num_rows() > 1) {
                                        $product['variant'] = [];
                                        foreach ($parsing['products_variants']->result() as $key_products_variants) {
                                            $variant['id'] = $key_products_variants->id;
                                            $variant['name'] = $key_products_variants->name;
                                            $variant['discountLabel'] = $key_products_variants->discount_label;
                                            $variant['minimumOrder'] = $key_products_variants->minimum_order;
                                            $variant['maximumOrder'] = $key_products_variants->maximum_order;
                                            $variant['discountPrice'] = $key_products_variants->discount_price;
                                            $variant['discountPriceCurrencyFormat'] = rupiah($key_products_variants->discount_price);
                                            $variant['quantity'] = $key_products_variants->quantity;
                                            $variant['basePrice'] = $key_products_variants->base_price;
                                            $variant['basePriceCurrencyFormat'] = rupiah($key_products_variants->base_price);
                                            $variant['discountPercent'] = $key_products_variants->discount_percent;
                                            $variant['isOutOfStock'] = boolval($key_products_variants->is_out_of_stock);
                                            $variant['mustChooseOption'] = boolval($key_products_variants->must_choose_option);

                                            $product['variant'][] = $variant;
                                        }
                                    } else {
                                        $variant['id'] = $parsing['products_variants']->row()->id;
                                        $variant['name'] = $parsing['products_variants']->row()->name;
                                        $variant['discountLabel'] = $parsing['products_variants']->row()->discount_label;
                                        $variant['minimumOrder'] = $parsing['products_variants']->row()->minimum_order;
                                        $variant['maximumOrder'] = $parsing['products_variants']->row()->maximum_order;
                                        $variant['discountPrice'] = $parsing['products_variants']->row()->discount_price;
                                        $variant['discountPriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->discount_price);
                                        $variant['quantity'] = $parsing['products_variants']->row()->quantity;
                                        $variant['basePrice'] = $parsing['products_variants']->row()->base_price;
                                        $variant['basePriceCurrencyFormat'] = rupiah($parsing['products_variants']->row()->base_price);
                                        $variant['discountPercent'] = $parsing['products_variants']->row()->discount_percent;
                                        $variant['isOutOfStock'] = boolval($parsing['products_variants']->row()->is_out_of_stock);
                                        $variant['mustChooseOption'] = boolval($parsing['products_variants']->row()->must_choose_option);

                                        $product['variant'] = $variant;
                                    }
                                } else {
                                    $product['variant'] = null;
                                }

                                $parsing['breadcrumbs_products'] = $this->parsing_breadcrumbs(['products_id' => $parsing['products']->id]);
                                if (!empty($parsing['breadcrumbs_products'])) {
                                    $product['breadcrumbs'] = [];
                                    foreach ($parsing['breadcrumbs_products'] as $key_breadcrumbs_products) {
                                        $breadcrumbs_products['id'] = $key_breadcrumbs_products->id;
                                        $breadcrumbs_products['name'] = $key_breadcrumbs_products->name;
                                        $breadcrumbs_products['slug'] = $key_breadcrumbs_products->slug;

                                        $product['breadcrumbs'][] = $breadcrumbs_products;
                                    }
                                } else {
                                    $product['breadcrumbs'] = null;
                                }

                                $product['catalog'] = $parsing['products']->catalog;
                                $product['idChild'] = $parsing['products']->categories_id;

                                $data['product'] = $product;
                            } else {
                                $data['product'] = null;
                            }

                            $response['result']['data'][] = $data;
                        }
                    } else {
                        $response['result']['data'] = [];
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function address_get($config = NULL)
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            } else {
                if (!empty($config)) {
                    if ($config != 'default') {
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

                        $where['address']['is_primary'] = [];
                    } else {
                        $where['address']['is_primary'] = [
                            'is_primary' => true
                        ];
                    }
                } else {
                    $where['address']['is_primary'] = [];
                }

                $where['address']['default'] = [
                    'customer_id' => $this->core['customer']->id
                ];

                $parsing['address'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'tbl_address',
                    'where' => array_merge($where['address']['default'], $where['address']['is_primary']),
                    'order_by' => [
                        'is_primary' => 'desc'
                    ]
                ])->result();

                if (empty($parsing['address'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'address empty'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($checking == TRUE) {
                if (!empty($config)) {
                    if ($config != 'default') {
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
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_OK,
                                    'message' => 'get_primary_address.success'
                                ],
                                'data' => []
                            ],
                            'status' => SELF::HTTP_OK
                        ];

                        $data['id'] = $parsing['address'][0]->id;
                        $data['addressAs'] = $parsing['address'][0]->address_as;
                        $data['receiverName'] = $parsing['address'][0]->receiver_name;
                        $data['phoneNumber'] = $parsing['address'][0]->phone_number;
                        $data['mobilePhoneNumber'] = $parsing['address'][0]->mobile_phone_number;
                        $data['completeAddress'] = $parsing['address'][0]->complete_address;

                        $parsing['province'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_province',
                            'where' => [
                                'province_id' => $parsing['address'][0]->province_id
                            ]
                        ])->row();
                        if (!empty($parsing['province'])) {
                            $province['id'] = $parsing['province']->province_id;
                            $province['name'] = $parsing['province']->province;
                            $province['kd'] = $parsing['province']->province_kd;

                            $data['province'] = $province;
                        } else {
                            $data['province'] = null;
                        }

                        $parsing['city'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_city',
                            'where' => [
                                'city_id' => $parsing['address'][0]->city_id
                            ]
                        ])->row();
                        if (!empty($parsing['city'])) {
                            $city['id'] = $parsing['city']->city_id;
                            $city['name'] = $parsing['city']->city;
                            $city['kd'] = $parsing['city']->city_kd;

                            $data['city'] = $city;
                        } else {
                            $data['city'] = null;
                        }

                        $parsing['district'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_district',
                            'where' => [
                                'district_id' => $parsing['address'][0]->district_id
                            ]
                        ])->row();
                        if (!empty($parsing['district'])) {
                            $district['id'] = $parsing['district']->district_id;
                            $district['name'] = $parsing['district']->district;
                            $district['kd'] = $parsing['district']->district_kd;

                            $data['district'] = $district;
                        } else {
                            $data['district'] = null;
                        }

                        $parsing['sub_district'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_sub_district',
                            'where' => [
                                'sub_district_id' => $parsing['address'][0]->sub_district_id
                            ]
                        ])->row();
                        if (!empty($parsing['sub_district'])) {
                            $sub_district['id'] = $parsing['sub_district']->sub_district_id;
                            $sub_district['name'] = $parsing['sub_district']->sub_district;
                            $sub_district['kd'] = $parsing['sub_district']->sub_district_kd;

                            $data['subDistrict'] = $sub_district;
                        } else {
                            $data['subDistrict'] = null;
                        }

                        $data['postalCode'] = $parsing['address'][0]->postal_code;
                        $data['isPrimary'] = boolval($parsing['address'][0]->is_primary);

                        $response['result']['data'] = $data;
                    }
                } else {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_OK,
                                'message' => 'get_list_address.success'
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    if (!empty($parsing['address'])) {
                        foreach ($parsing['address'] as $key_address) {
                            $data['id'] = $key_address->id;
                            $data['addressAs'] = $key_address->address_as;
                            $data['receiverName'] = $key_address->receiver_name;
                            $data['phoneNumber'] = $key_address->phone_number;
                            $data['mobilePhoneNumber'] = $key_address->mobile_phone_number;
                            $data['completeAddress'] = $key_address->complete_address;

                            $parsing['province'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_province',
                                'where' => [
                                    'province_id' => $key_address->province_id
                                ]
                            ])->row();
                            if (!empty($parsing['province'])) {
                                $province['id'] = $parsing['province']->province_id;
                                $province['name'] = $parsing['province']->province;
                                $province['kd'] = $parsing['province']->province_kd;

                                $data['province'] = $province;
                            } else {
                                $data['province'] = null;
                            }

                            $parsing['city'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_city',
                                'where' => [
                                    'city_id' => $key_address->city_id
                                ]
                            ])->row();
                            if (!empty($parsing['city'])) {
                                $city['id'] = $parsing['city']->city_id;
                                $city['name'] = $parsing['city']->city;
                                $city['kd'] = $parsing['city']->city_kd;

                                $data['city'] = $city;
                            } else {
                                $data['city'] = null;
                            }

                            $parsing['district'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_district',
                                'where' => [
                                    'district_id' => $key_address->district_id
                                ]
                            ])->row();
                            if (!empty($parsing['district'])) {
                                $district['id'] = $parsing['district']->district_id;
                                $district['name'] = $parsing['district']->district;
                                $district['kd'] = $parsing['district']->district_kd;

                                $data['district'] = $district;
                            } else {
                                $data['district'] = null;
                            }

                            $parsing['sub_district'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_sub_district',
                                'where' => [
                                    'sub_district_id' => $key_address->sub_district_id
                                ]
                            ])->row();
                            if (!empty($parsing['sub_district'])) {
                                $sub_district['id'] = $parsing['sub_district']->sub_district_id;
                                $sub_district['name'] = $parsing['sub_district']->sub_district;
                                $sub_district['kd'] = $parsing['sub_district']->sub_district_kd;

                                $data['subDistrict'] = $sub_district;
                            } else {
                                $data['subDistrict'] = null;
                            }

                            $data['postalCode'] = $key_address->postal_code;
                            $data['isPrimary'] = boolval($key_address->is_primary);

                            $response['result']['data'][] = $data;
                        }
                    } else {
                        $response['result']['data'] = [];
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function address_post($config = NULL)
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (!$this->post()) {
                $checking = FALSE;
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

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if (!empty($config)) {
                if ($config != 'default') {
                    $checking = FALSE;
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

                $check['address'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'tbl_address',
                    'where' => [
                        'id' => $this->post('addressId')
                    ]
                ])->row();

                if (empty($check['address'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'address not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            } else {
                $check['province'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'ro_province',
                    'where' => [
                        'province_id' => $this->post('provinceId')
                    ]
                ])->row();

                if (empty($check['province'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'province not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }

                $check['city'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'ro_city',
                    'where' => [
                        'city_id' => $this->post('cityId')
                    ]
                ])->row();

                if (empty($check['city'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'city not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }

                $check['district'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'ro_district',
                    'where' => [
                        'district_id' => $this->post('districtId')
                    ]
                ])->row();

                if (empty($check['district'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'district not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }

                $check['sub_district'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'ro_sub_district',
                    'where' => [
                        'sub_district_id' => $this->post('subDistrictId')
                    ]
                ])->row();

                if (empty($check['sub_district'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'sub district not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($checking == TRUE) {
                $this->db->trans_start();

                if (!empty($config)) {
                    $this->api_model->send_data([
                        'where' => [
                            'customer_id' => $this->core['customer']->id
                        ],
                        'data' => [
                            'is_primary' => false
                        ],
                        'table' => 'tbl_address'
                    ]);

                    $this->api_model->send_data([
                        'where' => [
                            'id' => $this->post('addressId')
                        ],
                        'data' => [
                            'is_primary' => true
                        ],
                        'table' => 'tbl_address'
                    ]);
                } else {
                    $this->api_model->send_data([
                        'data' => [
                            'address_as' => $this->post('addressAs'),
                            'receiver_name' => $this->post('receiverName'),
                            'phone_number' => $this->post('phoneNumber'),
                            'mobile_phone_number' => $this->post('mobilePhoneNumber'),
                            'province_id' => $this->post('provinceId'),
                            'city_id' => $this->post('cityId'),
                            'district_id' => $this->post('districtId'),
                            'sub_district_id' => $this->post('subDistrictId'),
                            'postal_code' => $this->post('postalCode'),
                            'complete_address' => $this->post('completeAddress'),
                            'customer_id' => $this->core['customer']->id,
                            'is_primary' => ($this->api_model->count_all_data([
                                'where' => [
                                    'customer_id' => $this->core['customer']->id
                                ],
                                'table' => 'tbl_address'
                            ]) < 1) ? true : false
                        ],
                        'table' => 'tbl_address'
                    ]);
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $db_error = $this->db->error();
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => (!empty($config)) ? 'set primary address failed' : 'add_address_customer.failed',
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
                                'code' => SELF::HTTP_OK,
                                'message' => (!empty($config)) ? 'set primary address success' : 'add_address_customer.success'
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    $parsing['address'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'tbl_address',
                        'where' => [
                            'customer_id' => $this->core['customer']->id
                        ],
                        'order_by' => [
                            'is_primary' => 'desc'
                        ]
                    ])->result();

                    foreach ($parsing['address'] as $key_address) {
                        $data['id'] = $key_address->id;
                        $data['addressAs'] = $key_address->address_as;
                        $data['receiverName'] = $key_address->receiver_name;
                        $data['phoneNumber'] = $key_address->phone_number;
                        $data['mobilePhoneNumber'] = $key_address->mobile_phone_number;
                        $data['completeAddress'] = $key_address->complete_address;

                        $parsing['province'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_province',
                            'where' => [
                                'province_id' => $key_address->province_id
                            ]
                        ])->row();
                        if (!empty($parsing['province'])) {
                            $province['id'] = $parsing['province']->province_id;
                            $province['name'] = $parsing['province']->province;
                            $province['kd'] = $parsing['province']->province_kd;

                            $data['province'] = $province;
                        } else {
                            $data['province'] = null;
                        }

                        $parsing['city'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_city',
                            'where' => [
                                'city_id' => $key_address->city_id
                            ]
                        ])->row();
                        if (!empty($parsing['city'])) {
                            $city['id'] = $parsing['city']->city_id;
                            $city['name'] = $parsing['city']->city;
                            $city['kd'] = $parsing['city']->city_kd;

                            $data['city'] = $city;
                        } else {
                            $data['city'] = null;
                        }

                        $parsing['district'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_district',
                            'where' => [
                                'district_id' => $key_address->district_id
                            ]
                        ])->row();
                        if (!empty($parsing['district'])) {
                            $district['id'] = $parsing['district']->district_id;
                            $district['name'] = $parsing['district']->district;
                            $district['kd'] = $parsing['district']->district_kd;

                            $data['district'] = $district;
                        } else {
                            $data['district'] = null;
                        }

                        $parsing['sub_district'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_sub_district',
                            'where' => [
                                'sub_district_id' => $key_address->sub_district_id
                            ]
                        ])->row();
                        if (!empty($parsing['sub_district'])) {
                            $sub_district['id'] = $parsing['sub_district']->sub_district_id;
                            $sub_district['name'] = $parsing['sub_district']->sub_district;
                            $sub_district['kd'] = $parsing['sub_district']->sub_district_kd;

                            $data['subDistrict'] = $sub_district;
                        } else {
                            $data['subDistrict'] = null;
                        }

                        $data['postalCode'] = $key_address->postal_code;
                        $data['isPrimary'] = boolval($key_address->is_primary);

                        $response['result']['data'][] = $data;
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function address_put($id = NULL)
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (!$this->put()) {
                $checking = FALSE;
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

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if (empty($id)) {
                $checking = FALSE;
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
                $check['address'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'tbl_address',
                    'where' => [
                        'id' => $id
                    ]
                ])->row();

                if (empty($check['address'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'address not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }

                $check['province'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'ro_province',
                    'where' => [
                        'province_id' => $this->put('provinceId')
                    ]
                ])->row();

                if (empty($check['province'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'province not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }

                $check['city'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'ro_city',
                    'where' => [
                        'city_id' => $this->put('cityId')
                    ]
                ])->row();

                if (empty($check['city'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'city not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }

                $check['district'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'ro_district',
                    'where' => [
                        'district_id' => $this->put('districtId')
                    ]
                ])->row();

                if (empty($check['district'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'district not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }

                $check['sub_district'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'ro_sub_district',
                    'where' => [
                        'sub_district_id' => $this->put('subDistrictId')
                    ]
                ])->row();

                if (empty($check['sub_district'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'sub district not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($checking == TRUE) {
                $query = $this->api_model->send_data([
                    'where' => [
                        'id' => $id
                    ],
                    'data' => [
                        'address_as' => $this->put('addressAs'),
                        'receiver_name' => $this->put('receiverName'),
                        'phone_number' => $this->put('phoneNumber'),
                        'mobile_phone_number' => $this->put('mobilePhoneNumber'),
                        'province_id' => $this->put('provinceId'),
                        'city_id' => $this->put('cityId'),
                        'district_id' => $this->put('districtId'),
                        'sub_district_id' => $this->put('subDistrictId'),
                        'postal_code' => $this->put('postalCode'),
                        'complete_address' => $this->put('completeAddress'),
                        'is_primary' => ($this->api_model->count_all_data([
                            'where' => [
                                'customer_id' => $this->core['customer']->id
                            ],
                            'table' => 'tbl_address'
                        ]) < 1) ? true : $check['address']->is_primary
                    ],
                    'table' => 'tbl_address'
                ]);

                if ($query['error'] == TRUE) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'update_address_customer.failed',
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
                                'message' => 'update_address_customer.success'
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    $parsing['address'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'tbl_address',
                        'where' => [
                            'customer_id' => $this->core['customer']->id
                        ],
                        'order_by' => [
                            'is_primary' => 'desc'
                        ]
                    ])->result();

                    foreach ($parsing['address'] as $key_address) {
                        $data['id'] = $key_address->id;
                        $data['addressAs'] = $key_address->address_as;
                        $data['receiverName'] = $key_address->receiver_name;
                        $data['phoneNumber'] = $key_address->phone_number;
                        $data['mobilePhoneNumber'] = $key_address->mobile_phone_number;
                        $data['completeAddress'] = $key_address->complete_address;

                        $parsing['province'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_province',
                            'where' => [
                                'province_id' => $key_address->province_id
                            ]
                        ])->row();
                        if (!empty($parsing['province'])) {
                            $province['id'] = $parsing['province']->province_id;
                            $province['name'] = $parsing['province']->province;
                            $province['kd'] = $parsing['province']->province_kd;

                            $data['province'] = $province;
                        } else {
                            $data['province'] = null;
                        }

                        $parsing['city'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_city',
                            'where' => [
                                'city_id' => $key_address->city_id
                            ]
                        ])->row();
                        if (!empty($parsing['city'])) {
                            $city['id'] = $parsing['city']->city_id;
                            $city['name'] = $parsing['city']->city;
                            $city['kd'] = $parsing['city']->city_kd;

                            $data['city'] = $city;
                        } else {
                            $data['city'] = null;
                        }

                        $parsing['district'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_district',
                            'where' => [
                                'district_id' => $key_address->district_id
                            ]
                        ])->row();
                        if (!empty($parsing['district'])) {
                            $district['id'] = $parsing['district']->district_id;
                            $district['name'] = $parsing['district']->district;
                            $district['kd'] = $parsing['district']->district_kd;

                            $data['district'] = $district;
                        } else {
                            $data['district'] = null;
                        }

                        $parsing['sub_district'] = $this->api_model->select_data([
                            'field' => '*',
                            'table' => 'ro_sub_district',
                            'where' => [
                                'sub_district_id' => $key_address->sub_district_id
                            ]
                        ])->row();
                        if (!empty($parsing['sub_district'])) {
                            $sub_district['id'] = $parsing['sub_district']->sub_district_id;
                            $sub_district['name'] = $parsing['sub_district']->sub_district;
                            $sub_district['kd'] = $parsing['sub_district']->sub_district_kd;

                            $data['subDistrict'] = $sub_district;
                        } else {
                            $data['subDistrict'] = null;
                        }

                        $data['postalCode'] = $key_address->postal_code;
                        $data['isPrimary'] = boolval($key_address->is_primary);

                        $response['result']['data'][] = $data;
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function address_delete($id = NULL)
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if (empty($id)) {
                $checking = FALSE;
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
                $check['address'] = $this->api_model->select_data([
                    'field' => '*',
                    'table' => 'tbl_address',
                    'where' => [
                        'id' => $id
                    ]
                ])->row();

                if (empty($check['address'])) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_NOT_FOUND,
                                'message' => 'address not found'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                } else {
                    if ($check['address']->is_primary == true) {
                        $checking = FALSE;
                        $response = [
                            'result' => [
                                'status' => [
                                    'code' => SELF::HTTP_NOT_FOUND,
                                    'message' => 'cannot delete primary address'
                                ],
                                'data' => null
                            ],
                            'status' => SELF::HTTP_OK
                        ];
                    }
                }
            }

            if ($checking == TRUE) {
                $query = $this->api_model->delete_data([
                    'where' => [
                        'id' => $id
                    ],
                    'table' => 'tbl_address'
                ]);

                if ($query['error'] == TRUE) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'delete_address.failed',
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
                                'message' => 'delete_address.success'
                            ],
                            'data' => []
                        ],
                        'status' => SELF::HTTP_OK
                    ];

                    $parsing['address'] = $this->api_model->select_data([
                        'field' => '*',
                        'table' => 'tbl_address',
                        'where' => [
                            'customer_id' => $this->core['customer']->id
                        ],
                        'order_by' => [
                            'is_primary' => 'desc'
                        ]
                    ])->result();

                    if (!empty($parsing['address'])) {
                        foreach ($parsing['address'] as $key_address) {
                            $data['id'] = $key_address->id;
                            $data['addressAs'] = $key_address->address_as;
                            $data['receiverName'] = $key_address->receiver_name;
                            $data['phoneNumber'] = $key_address->phone_number;
                            $data['mobilePhoneNumber'] = $key_address->mobile_phone_number;
                            $data['completeAddress'] = $key_address->complete_address;

                            $parsing['province'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_province',
                                'where' => [
                                    'province_id' => $key_address->province_id
                                ]
                            ])->row();
                            if (!empty($parsing['province'])) {
                                $province['id'] = $parsing['province']->province_id;
                                $province['name'] = $parsing['province']->province;
                                $province['kd'] = $parsing['province']->province_kd;

                                $data['province'] = $province;
                            } else {
                                $data['province'] = null;
                            }

                            $parsing['city'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_city',
                                'where' => [
                                    'city_id' => $key_address->city_id
                                ]
                            ])->row();
                            if (!empty($parsing['city'])) {
                                $city['id'] = $parsing['city']->city_id;
                                $city['name'] = $parsing['city']->city;
                                $city['kd'] = $parsing['city']->city_kd;

                                $data['city'] = $city;
                            } else {
                                $data['city'] = null;
                            }

                            $parsing['district'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_district',
                                'where' => [
                                    'district_id' => $key_address->district_id
                                ]
                            ])->row();
                            if (!empty($parsing['district'])) {
                                $district['id'] = $parsing['district']->district_id;
                                $district['name'] = $parsing['district']->district;
                                $district['kd'] = $parsing['district']->district_kd;

                                $data['district'] = $district;
                            } else {
                                $data['district'] = null;
                            }

                            $parsing['sub_district'] = $this->api_model->select_data([
                                'field' => '*',
                                'table' => 'ro_sub_district',
                                'where' => [
                                    'sub_district_id' => $key_address->sub_district_id
                                ]
                            ])->row();
                            if (!empty($parsing['sub_district'])) {
                                $sub_district['id'] = $parsing['sub_district']->sub_district_id;
                                $sub_district['name'] = $parsing['sub_district']->sub_district;
                                $sub_district['kd'] = $parsing['sub_district']->sub_district_kd;

                                $data['subDistrict'] = $sub_district;
                            } else {
                                $data['subDistrict'] = null;
                            }

                            $data['postalCode'] = $key_address->postal_code;
                            $data['isPrimary'] = boolval($key_address->is_primary);

                            $response['result']['data'][] = $data;
                        }
                    } else {
                        $response['result']['data'] = [];
                    }
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }

    public function password_put()
    {
        if (!empty($this->auth())) {
            $response = $this->auth();
        } else {
            $checking = TRUE;

            if (empty($this->core['customer'])) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_UNAUTHORIZED,
                            'message' => 'unauthorized'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            } else {
                if (!password_verify($this->put('oldPassword'), $this->core['customer']->password)) {
                    $checking = FALSE;
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'incorrect old password'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }

            if ($this->put('newPassword') != $this->put('confirmPassword')) {
                $checking = FALSE;
                $response = [
                    'result' => [
                        'status' => [
                            'code' => SELF::HTTP_BAD_REQUEST,
                            'message' => 'incorrect new and confirm password'
                        ],
                        'data' => null
                    ],
                    'status' => SELF::HTTP_OK
                ];
            }

            if ($checking == TRUE) {
                $query = $this->api_model->send_data([
                    'where' => [
                        'id' => $this->core['customer']->id
                    ],
                    'data' => [
                        'password' => password_hash($this->put('newPassword'), PASSWORD_DEFAULT)
                    ],
                    'table' => 'tbl_customer'
                ]);

                if ($query['error'] == TRUE) {
                    $response = [
                        'result' => [
                            'status' => [
                                'code' => SELF::HTTP_BAD_REQUEST,
                                'message' => 'change_password.failed',
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
                                'message' => 'change_password.success'
                            ],
                            'data' => null
                        ],
                        'status' => SELF::HTTP_OK
                    ];
                }
            }
        }

        $this->response($response['result'], $response['status']);
    }
}
