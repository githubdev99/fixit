<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth([
            'session' => 'admin',
            'login' => true
        ]);

        $this->auth([
            'session' => 'cashier',
            'login' => true
        ]);
    }

    public function index()
    {
        $title = 'Login';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'auth/v_login',
            'get_script' => 'auth/script_login'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            if ($this->input->post('submit') == 'login') {
                $response = json_decode(shoot_api([
                    'url' => $this->core['url_api'] . 'login',
                    'method' => 'POST',
                    'header' => [
                        "Content-Type: application/json"
                    ],
                    'data' => json_encode([
                        'username' => $this->input->post('username'),
                        'password' => $this->input->post('password')
                    ])
                ]), true);

                if ($response['status']['code'] == 200) {
                    if ($response['data']['session'] == 'admin') {
                        $this->session->set_userdata('admin', $response['data']['id']);
                        $output = [
                            'error' => false,
                            'type' => 'success',
                            'message' => 'Anda berhasil login, mohon tunggu...',
                            'callback' => base_url() . 'admin/dashboard'
                        ];
                    } elseif ($response['data']['session'] == 'cashier') {
                        $this->session->set_userdata('cashier', $response['data']['id']);
                        $output = [
                            'error' => false,
                            'type' => 'success',
                            'message' => 'Anda berhasil login, mohon tunggu...',
                            'callback' => base_url() . 'cashier/dashboard'
                        ];
                    }
                } else {
                    if ($response['status']['code'] == 401) {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Username atau password salah.'
                        ];
                    } elseif ($response['status']['code'] == 404) {
                        $output = [
                            'error' => true,
                            'type' => 'warning',
                            'message' => 'Akun tidak ditemukan.'
                        ];
                    } else {
                        $output = [
                            'error' => true,
                            'type' => 'error',
                            'message' => 'Anda dilarang mengakses.'
                        ];
                    }
                }
            } else {
                $output = [
                    'error' => true,
                    'type' => 'error',
                    'message' => 'Ada kesalahan teknis.'
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }
}
