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

                $created_at = explode(' ', $key->created_at);
                $updated_at = (!empty($key->updated_at)) ? explode(' ', $key->updated_at) : null;

                $column[] = $no;
                $column[] = $key->name;
                $column[] = $key->username;
                $column[] = $key->phone_number;
                $column[] = ($key->gender == 'male') ? 'Laki-Laki' : 'Perempuan';
                $column[] = date_indo(date('d-m-Y', strtotime($created_at[0])));
                $column[] = (!empty($updated_at)) ? date_indo(date('d-m-Y', strtotime($updated_at[0]))) : 'Belum Diupdate';
                $column[] = '
                <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" title="Detail Data" onclick="show_modal({ modal: ' . "'detail'" . ', id: ' . "'" . encrypt_text($key->id) . "'" . ' })"><i class="fas fa-info"></i></button>
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
}
