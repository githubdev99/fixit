<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth([
            'session' => 'cashier',
            'login' => false
        ]);
    }

    public function index()
    {
        $title = 'Barang';
        $data = [
            'core' => $this->core($title),
            'get_view' => 'cashier/item/v_item',
            'get_script' => 'cashier/item/script_item'
        ];

        $this->master->template($data);
    }

    public function detail($id = null)
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'item/' . $id,
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $title = 'Detail Barang';
            $data = [
                'core' => $this->core($title),
                'get_view' => 'cashier/item/v_detail_item',
                'get_script' => 'cashier/item/script_detail_item',
                'get_data' => $response['data']
            ];

            $this->master->template($data);
        } else {
            $this->alert_popup([
                'name' => 'show_alert',
                'swal' => [
                    'title' => 'Ada kesalahan teknis',
                    'type' => 'error'
                ]
            ]);

            redirect(base_url() . 'cashier/item', 'refresh');
        }
    }

    public function option()
    {
        $response = json_decode(shoot_api([
            'url' => $this->core['url_api'] . 'item',
            'method' => 'get'
        ]), true);

        if ($response['status']['code'] == 200) {
            $output = [
                'error' => false,
                'html' => '<option></option>'
            ];

            foreach ($response['data'] as $key) {
                if ($key['in_active'] == true) {
                    $selected = (decrypt_text($this->input->post('id')) == decrypt_text($key['id'])) ? 'selected' : '';

                    $output['html'] .= '
                    <option value="' . $key['id'] . '" ' . $selected . '>' . $key['name'] . '</option>
                    ';
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
