<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{

	public $core = [];

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');

		$this->core['enum'] = [
			'gender' => [
				'male', 'female'
			]
		];
		$this->core['app_name'] = 'Fixit';
		$this->core['mini_logo'] = '';

		// $this->load->module('master');
		// $this->load->model('master/master_model');

		// $this->core['full_logo'] = base_url() . 'asset/images/logo-full.png';

		// if ($this->core['server_online'] == FALSE) {
		// 	$this->core['link_seller'] = 'http://localhost/seller.jaja.id/';
		// 	$this->core['link_jaja'] = 'http://localhost/jaja.id/';
		// } else {
		// 	$this->core['link_seller'] = 'https://seller.jaja.id/';
		// 	$this->core['link_jaja'] = 'https://jaja.id/';
		// }
		// $this->core['folder_upload'] = $this->core['link_seller'] . 'asset/images/';
		// $this->core['folder_upload_other'] = $this->core['link_seller'] . 'asset/front/images/';
		// $this->core['put_upload'] = 'asset/images/';
		// $this->core['put_upload_other'] = 'asset/front/images/';

		// if (!empty($this->input->get('credentials'))) {
		// 	$credentials = explode('.', $this->input->get('credentials'));

		// 	$this->session->set_userdata('id_customer', decrypt_text($credentials[0]));

		// 	$this->core['seller'] = $this->master_model->select_data(
		// 		[
		// 			'field' => 'toko.*, banner_toko.banner, banner_toko.banner_1, banner_toko.banner_2, banner_toko.banner_3, banner_toko.banner_4, banner_toko.banner_5, ro_province.province AS nama_provinsi, ro_city.city_name AS nama_kota, ro_kecamatan.kecamatan AS nama_kecamatan, ro_wilayah.kelurahan_desa AS nama_kelurahan, ro_kecamatan.kecamatan_kd',
		// 			'table' => 'toko',
		// 			'join' => [
		// 				[
		// 					'table' => 'ro_province',
		// 					'on' => 'ro_province.province_id = toko.provinsi',
		// 					'type' => 'inner'
		// 				],
		// 				[
		// 					'table' => 'ro_city',
		// 					'on' => 'ro_city.city_id = toko.kota_kabupaten',
		// 					'type' => 'inner'
		// 				],
		// 				[
		// 					'table' => 'ro_kecamatan',
		// 					'on' => 'ro_kecamatan.kecamatan_id = toko.kecamatan',
		// 					'type' => 'inner'
		// 				],
		// 				[
		// 					'table' => 'ro_wilayah',
		// 					'on' => 'ro_wilayah.kelurahan_id = toko.kelurahan',
		// 					'type' => 'inner'
		// 				],
		// 				[
		// 					'table' => 'banner_toko',
		// 					'on' => 'banner_toko.id_toko = toko.id_toko',
		// 					'type' => 'left'
		// 				]
		// 			],
		// 			'where' => [
		// 				'toko.id_user' => decrypt_text($credentials[0])
		// 			]
		// 		]
		// 	)->row();

		// 	$this->core['customer'] = $this->master_model->select_data(
		// 		[
		// 			'field' => '*',
		// 			'table' => 'customer',
		// 			'where' => [
		// 				'id_customer' => decrypt_text($credentials[0])
		// 			]
		// 		]
		// 	)->row();
		// } else {
		// 	if ($this->session->has_userdata('id_customer')) {
		// 		$this->core['seller'] = $this->master_model->select_data(
		// 			[
		// 				'field' => 'toko.*, banner_toko.banner, banner_toko.banner_1, banner_toko.banner_2, banner_toko.banner_3, banner_toko.banner_4, banner_toko.banner_5, ro_province.province AS nama_provinsi, ro_city.city_name AS nama_kota, ro_kecamatan.kecamatan AS nama_kecamatan, ro_wilayah.kelurahan_desa AS nama_kelurahan, ro_kecamatan.kecamatan_kd',
		// 				'table' => 'toko',
		// 				'join' => [
		// 					[
		// 						'table' => 'ro_province',
		// 						'on' => 'ro_province.province_id = toko.provinsi',
		// 						'type' => 'inner'
		// 					],
		// 					[
		// 						'table' => 'ro_city',
		// 						'on' => 'ro_city.city_id = toko.kota_kabupaten',
		// 						'type' => 'inner'
		// 					],
		// 					[
		// 						'table' => 'ro_kecamatan',
		// 						'on' => 'ro_kecamatan.kecamatan_id = toko.kecamatan',
		// 						'type' => 'inner'
		// 					],
		// 					[
		// 						'table' => 'ro_wilayah',
		// 						'on' => 'ro_wilayah.kelurahan_id = toko.kelurahan',
		// 						'type' => 'inner'
		// 					],
		// 					[
		// 						'table' => 'banner_toko',
		// 						'on' => 'banner_toko.id_toko = toko.id_toko',
		// 						'type' => 'left'
		// 					]
		// 				],
		// 				'where' => [
		// 					'toko.id_user' => $this->session->userdata('id_customer')
		// 				]
		// 			]
		// 		)->row();

		// 		$this->core['customer'] = $this->master_model->select_data(
		// 			[
		// 				'field' => '*',
		// 				'table' => 'customer',
		// 				'where' => [
		// 					'id_customer' => $this->session->userdata('id_customer')
		// 				]
		// 			]
		// 		)->row();
		// 	}
		// }
	}

	public function core($title)
	{
		$this->core['title_page'] = $title . ' | ' . $this->core['app_name'];

		return $this->core;
	}

	public function alert_popup($message)
	{
		$sweet_alert = '
		Swal.mixin({
			toast: true,
			position: "top",
			showCloseButton: !0,
			showConfirmButton: false,
			timer: 4000,
			onOpen: (toast) => {
				toast.addEventListener("mouseenter", Swal.stopTimer)
				toast.addEventListener("mouseleave", Swal.resumeTimer)
			}
		}).fire({
			icon: "' . $message['swal']['type'] . '",
			title: "' . $message['swal']['title'] . '"
		});
		';

		$this->session->set_flashdata($message['name'], $sweet_alert);
	}

	// public function auth_seller()
	// {
	// 	if ($this->session->has_userdata('id_customer')) {
	// 		if ($this->core['customer']->status_login == 'T') {
	// 			$this->session->sess_destroy();
	// 			redirect($this->core['link_jaja'], 'refresh');
	// 		} else {
	// 			if (empty($this->core['seller'])) {
	// 				if ($this->uri->segment(2) != 'buka_toko') {
	// 					redirect($this->core['link_seller'] . 'home/buka_toko', 'refresh');
	// 				}
	// 			}
	// 		}
	// 	} else {
	// 		redirect($this->core['link_jaja'], 'refresh');
	// 	}
	// }

	// public function auth()
	// {
	//     $headers = $this->input->request_headers();
	//     $response = [];

	//     if (!empty($headers['Authorization'])) {
	//         $get_token = $this->authorization->validate_token();
	//         if ($get_token['error'] == FALSE) {
	//             $this->core['customer'] = $this->api_model->select_data([
	//                 'field' => '*',
	//                 'table' => 'tbl_customer',
	//                 'where' => [
	//                     'id' => $get_token['data']->id
	//                 ]
	//             ])->row();

	//             if (empty($this->core['customer'])) {
	//                 $response = [
	//                     'result' => [
	//                         'status' => [
	//                             'code' => SELF::HTTP_NOT_FOUND,
	//                             'message' => 'data not found'
	//                         ],
	//                         'data' => null
	//                     ],
	//                     'status' => SELF::HTTP_NOT_FOUND
	//                 ];
	//             }
	//         } else {
	//             $response = [
	//                 'result' => [
	//                     'status' => [
	//                         'code' => SELF::HTTP_UNAUTHORIZED,
	//                         'message' => $get_token['data']
	//                     ],
	//                     'data' => null
	//                 ],
	//                 'status' => SELF::HTTP_UNAUTHORIZED
	//             ];
	//         }
	//     }

	//     return $response;
	// }
}
