<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends MX_Controller
{

	public $core = [];

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');

		$this->load->module('master');

		$this->core['enum'] = [
			'gender' => [
				'male', 'female'
			]
		];
		$this->core['app_name'] = 'Fixit';
		$this->core['full_logo'] = base_url() . 'asset/images/logo-full.png';
		$this->core['mini_logo'] = base_url() . 'asset/images/logo-mini.png';
		$this->core['url_api'] = base_url() . 'api/';
		$this->core['total_data'] = [
			'cashier' => $this->api_model->count_all_data([
				'table' => 'cashier'
			]),
			'vehicle' => $this->api_model->count_all_data([
				'table' => 'vehicle_children'
			]),
			'service' => $this->api_model->count_all_data([
				'table' => 'service'
			]),
			'item' => $this->api_model->count_all_data([
				'table' => 'item'
			]),
			'purchase' => $this->api_model->select_data([
				'field' => 'SUM(total_price) AS data',
				'table' => 'purchase'
			])->row()->data,
			'transaction' => $this->api_model->select_data([
				'field' => 'SUM(total_price) AS data',
				'table' => 'transaction'
			])->row()->data
		];

		$this->core['setting'] = $this->api_model->select_data([
			'field' => '*',
			'table' => 'setting'
		])->result();

		if ($this->session->has_userdata('admin')) {
			$this->core['admin'] = $this->api_model->select_data([
				'field' => '*',
				'table' => 'admin',
				'where' => [
					'id' => decrypt_text($this->session->userdata('admin'))
				]
			])->row();
		}

		if ($this->session->has_userdata('cashier')) {
			$this->core['cashier'] = $this->api_model->select_data([
				'field' => '*',
				'table' => 'cashier',
				'where' => [
					'id' => decrypt_text($this->session->userdata('cashier'))
				]
			])->row();
		}
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
			timer: 1700,
			timerProgressBar: true,
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

	public function auth($param)
	{
		if ($param['session'] == 'admin') {
			if ($param['login'] == true) {
				if (!empty($this->core['admin'])) {
					redirect(base_url() . 'admin/dashboard', 'refresh');
				}
			} else {
				if (empty($this->core['admin'])) {
					redirect(base_url() . 'auth/login', 'refresh');
				}
			}
		}

		if ($param['session'] == 'cashier') {
			if ($param['login'] == true) {
				if (!empty($this->core['cashier'])) {
					redirect(base_url() . 'cashier/dashboard', 'refresh');
				}
			} else {
				if (empty($this->core['cashier'])) {
					redirect(base_url() . 'auth/login', 'refresh');
				}
			}
		}
	}
}
