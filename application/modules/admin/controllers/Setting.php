<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth([
            'session' => 'admin',
            'login' => false
        ]);
    }

    public function index()
    {
        $title = 'Pengaturan Admin';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'admin/v_setting',
            'get_script' => 'admin/script_setting'
        ];

        if (!$this->input->post()) {
            $this->master->template($data);
        } else {
            if (!empty($this->input->post('submit'))) {
                $response = json_decode(shoot_api([
                    'url' => $this->core['url_api'] . 'admin/setting/' . $this->input->post('submit'),
                    'method' => 'POST',
                    'header' => [
                        "Content-Type: application/json"
                    ],
                    'data' => json_encode([
                        'id' => $this->input->post('id'),
                        'password_default' => $this->input->post('password_default')
                    ])
                ]), true);

                if ($response['status']['code'] == 200) {
                    $output = [
                        'error' => false,
                        'type' => 'success',
                        'message' => 'Pengaturan berhasil disimpan.'
                    ];
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

    public function layouts()
    {
        $parsing['setting'] = $this->api_model->select_data([
            'field' => '*',
            'table' => 'setting'
        ])->row();

        if (!empty($parsing['setting'])) {
            $form_input = '
            <input type="hidden" name="id" value="' . encrypt_text($parsing['setting']->id) . '">
            <input type="text" name="password_default" class="form-control" placeholder="Masukkan password default" required value="' . $parsing['setting']->password_default . '">';
            $button = '
            <button type="submit" name="update" value="update" class="btn btn-info">Simpan</button>';
        } else {
            $form_input = '
            <input type="hidden" name="id" value="">
            <input type="text" name="password_default" class="form-control" placeholder="Masukkan password default" required>';
            $button = '
            <button type="submit" name="create" value="create" class="btn btn-info">Simpan</button>';
        }

        $output = '
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="' . base_url() . 'admin/setting" method="post" enctype="multipart/form-data" name="setting">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mt-3 mt-sm-0">
                                        <label>Password Default <span class="text-danger">*</span></label>
                                        ' . $form_input . '
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-right">
                                <button type="submit" name="reset" value="reset" class="btn btn-danger mr-2">Reset</button>
                                    ' . $button . '
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>';

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }
}
