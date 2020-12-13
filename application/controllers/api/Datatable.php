<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Datatable extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function mechanic_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'name', 'birth_date', 'phone_number', 'username', 'gender', 'created_at', 'updated_at'
        ];
        $param['column_order'] = [
            null, 'name', 'phone_number', 'gender', 'created_at', 'updated_at', null
        ];
        $param['field'] = '*';
        $param['table'] = 'mechanic';

        $param['order_by'] = [
            'name' => 'asc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];

                $column[] = $no;
                $column[] = $key->name;
                $column[] = $key->username;
                $column[] = $key->phone_number;
                $column[] = ($key->gender == 'male') ? 'Laki-Laki' : 'Perempuan';
                $column[] = date_indo(date('d-m-Y', strtotime($key->birth_date)));
                $column[] = '
                <a href="' . base_url() . 'admin/mechanic/detail/' . encrypt_text($key->id) . '" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data"><i class="fas fa-info"></i></a>
                <a href="' . base_url() . 'admin/mechanic/form/' . encrypt_text($key->id) . '" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" title="Edit Data"><i class="fas fa-edit"></i></a>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Data" onclick="show_modal({ modal: ' . "'delete'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-trash-alt"></i></button>
				';

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function cashier_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'name', 'birth_date', 'phone_number', 'username', 'gender', 'created_at', 'updated_at'
        ];
        $param['column_order'] = [
            null, 'name', 'phone_number', 'gender', 'created_at', 'updated_at', null
        ];
        $param['field'] = '*';
        $param['table'] = 'cashier';

        $param['order_by'] = [
            'name' => 'asc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];

                $column[] = $no;
                $column[] = $key->name;
                $column[] = $key->username;
                $column[] = $key->phone_number;
                $column[] = ($key->gender == 'male') ? 'Laki-Laki' : 'Perempuan';
                $column[] = date_indo(date('d-m-Y', strtotime($key->birth_date)));
                $column[] = '
                <a href="' . base_url() . 'admin/cashier/detail/' . encrypt_text($key->id) . '" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data"><i class="fas fa-info"></i></a>
                <a href="' . base_url() . 'admin/cashier/form/' . encrypt_text($key->id) . '" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" title="Edit Data"><i class="fas fa-edit"></i></a>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Data" onclick="show_modal({ modal: ' . "'delete'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-trash-alt"></i></button>
				';

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function vehicle_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'name', 'created_at', 'updated_at', 'in_active'
        ];
        $param['column_order'] = [
            null, 'name', 'created_at', 'updated_at', 'in_active', null
        ];
        $param['field'] = '*';
        $param['table'] = 'vehicle';

        if ($this->input->post('params')['in_active'] != 'all') {
            $param['where'] = [
                'in_active' => $this->input->post('params')['in_active']
            ];
        }

        $param['order_by'] = [
            'name' => 'asc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];

                $created_at = explode(' ', $key->created_at);
                $updated_at = (!empty($key->updated_at)) ? explode(' ', $key->updated_at) : null;

                if ($key->in_active != 0) {
                    $in_active = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="vehicle_' . encrypt_text($key->id) . '" onclick="show_modal({ modal: ' . "'not_active'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })" checked><label class="custom-control-label" for="vehicle_' . encrypt_text($key->id) . '"></label></div>';
                } else {
                    $in_active = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="vehicle_' . encrypt_text($key->id) . '" onclick="show_modal({ modal: ' . "'active'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><label class="custom-control-label" for="vehicle_' . encrypt_text($key->id) . '"></label></div>';
                }

                $column[] = $no;
                $column[] = $key->name;
                $column[] = date_indo(date('d-m-Y', strtotime($created_at[0]))) . '<br>' . $created_at[1];
                $column[] = (!empty($updated_at)) ? date_indo(date('d-m-Y', strtotime($updated_at[0]))) . '<br>' . $updated_at[1] : 'Belum Update';
                $column[] = $in_active;
                $column[] = '
                <a href="' . base_url() . 'admin/vehicle/detail/' . encrypt_text($key->id) . '" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data"><i class="fas fa-info"></i></a>
                <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" title="Edit Data" onclick="show_modal({ modal: ' . "'edit'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Data" onclick="show_modal({ modal: ' . "'delete'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-trash-alt"></i></button>
				';

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }

    public function vehicle_children_post()
    {
        if (!empty($_REQUEST['draw'])) {
            $draw = $_REQUEST['draw'];
        } else {
            $draw = 0;
        }

        $param['column_search'] = [
            'name', 'created_at', 'updated_at', 'in_active'
        ];
        $param['column_order'] = [
            null, 'name', 'created_at', 'updated_at', 'in_active', null
        ];
        $param['field'] = '*';
        $param['table'] = 'vehicle_children';

        $arr_default = [
            'vehicle_id' => decrypt_text($this->input->post('params')['vehicle_id'])
        ];

        if ($this->input->post('params')['in_active'] != 'all') {
            $arr_in_active = [
                'in_active' => $this->input->post('params')['in_active']
            ];
        } else {
            $arr_in_active = [];
        }

        $param['where'] = array_merge($arr_default, $arr_in_active);

        $param['order_by'] = [
            'name' => 'asc'
        ];

        $data_parsing = $this->api_model->get_datatable($param);
        $total_filtered = $this->api_model->get_total_filtered($param);
        $total_data = $this->api_model->get_total_data($param);

        $data = [];
        if (!empty($data_parsing)) {
            $no = $_REQUEST['start'];
            foreach ($data_parsing as $key) {
                $no++;
                $column = [];

                $created_at = explode(' ', $key->created_at);
                $updated_at = (!empty($key->updated_at)) ? explode(' ', $key->updated_at) : null;

                if ($key->in_active != 0) {
                    $in_active = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="vehicle_children_' . encrypt_text($key->id) . '" onclick="show_modal_child({ modal: ' . "'not_active'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })" checked><label class="custom-control-label" for="vehicle_children_' . encrypt_text($key->id) . '"></label></div>';
                } else {
                    $in_active = '<div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="vehicle_children_' . encrypt_text($key->id) . '" onclick="show_modal_child({ modal: ' . "'active'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><label class="custom-control-label" for="vehicle_children_' . encrypt_text($key->id) . '"></label></div>';
                }

                $column[] = $no;
                $column[] = $key->name;
                $column[] = date_indo(date('d-m-Y', strtotime($created_at[0]))) . '<br>' . $created_at[1];
                $column[] = (!empty($updated_at)) ? date_indo(date('d-m-Y', strtotime($updated_at[0]))) . '<br>' . $updated_at[1] : 'Belum Update';
                $column[] = $in_active;
                $column[] = '
                <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" title="Edit Data" onclick="show_modal_child({ modal: ' . "'edit'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Hapus Data" onclick="show_modal_child({ modal: ' . "'delete'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-trash-alt"></i></button>
				';

                $data[] = $column;
            }
        }

        $response = [
            'result' => [
                'draw' => intval($draw),
                'recordsTotal' => intval($total_data),
                'recordsFiltered' => intval($total_filtered),
                'data' => $data
            ],
            'status' => SELF::HTTP_OK
        ];

        $this->response($response['result'], $response['status']);
    }
}
