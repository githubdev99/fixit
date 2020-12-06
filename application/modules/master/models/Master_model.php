<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->db->db_debug = FALSE;
	}

	// Server Side Datatable
	public function query_datatable($param)
	{
		$column_order = $param['column_order'];

		$this->db->select($param['field']);
		$this->db->from($param['table']);

		$i = 0;
		foreach ($param['column_search'] as $item) {
			if (!empty($_REQUEST['search']['value'])) {
				if ($i === 0) {
					$this->db->group_start();
					$this->db->like($item, $_REQUEST['search']['value']);
				} else {
					$this->db->or_like($item, $_REQUEST['search']['value']);
				}

				if (count($param['column_search']) - 1 == $i) {
					$this->db->group_end();
				}
			}
			$i++;
		}

		if (!empty($param['join'])) {
			foreach ($param['join'] as $key) {
				$this->db->join($key['table'], $key['on'], $key['type']);
			}
		}
		if (!empty($param['where'])) {
			$this->db->where($param['where']);
		}
		if (!empty($_REQUEST['order'])) {
			$this->db->order_by($column_order[$_REQUEST['order']['0']['column']], $_REQUEST['order']['0']['dir']);
		}
		if (!empty($param['order_by'])) {
			$this->db->order_by(key($param['order_by']), $param['order_by'][key($param['order_by'])]);
        }
        if (!empty($param['distinct'])) {
			$this->db->distinct($param['distinct']);
        }
        if (!empty($param['group_by'])) {
			$this->db->group_by($param['group_by']);
		}
	}

	public function get_datatable($param)
	{
		$this->query_datatable($param);
		if ($_REQUEST['length'] != -1) {
			$this->db->limit($_REQUEST['length'], $_REQUEST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function get_total_filtered($param)
	{
		$this->query_datatable($param);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_total_data($param)
	{
		$this->db->from($param['table']);
		return $this->db->count_all_results();
	}
	// End Server Side Datatable

	public function get_data($param)
	{
		if (!empty($param['where'])) {
			$this->db->where($param['where']);
		}
		if (!empty($param['order_by'])) {
			$this->db->order_by(key($param['order_by']), $param['order_by'][key($param['order_by'])]);
		}
		
		return $this->db->get($param['table']);
	}

	public function select_data($param)
	{
		if (!empty($param['field'])) {
			$this->db->select($param['field']);
		}
		if (!empty($param['distinct'])) {
			$this->db->distinct($param['distinct']);
		}
		$this->db->from($param['table']);
		if (!empty($param['join'])) {
			foreach ($param['join'] as $key) {
				$this->db->join($key['table'], $key['on'], $key['type']);
			}
		}
		if (!empty($param['like'])) {
			$this->db->like($param['like']);
		}
		if (!empty($param['or_like'])) {
			foreach ($param['or_like'] as $key) {
				$this->db->or_like($key);
			}
		}
		if (!empty($param['where'])) {
			$this->db->where($param['where']);
		}
		if (!empty($param['where_not_in'])) {
			$this->db->where_not_in(key($param['where_not_in']), $param['where_not_in'][key($param['where_not_in'])]);
		}
		if (!empty($param['order_by'])) {
			$this->db->order_by(key($param['order_by']), $param['order_by'][key($param['order_by'])]);
		}
		if (!empty($param['group_by'])) {
			$this->db->group_by($param['group_by']);
		}

		if (!empty($param['limit'])) {
			$this->db->limit($param['limit']);
		}

		return $this->db->get();
	}

	public function send_data($param)
	{
		if (!empty($param['where'])) {
			try {
				$this->db->where($param['where']);
				return $this->db->update($param['table'], $param['data']);

				$db_error = $this->db->error();
				if (!empty($db_error)) {
					return FALSE;
				}
			} catch (Exception $e) {
				return FALSE;
			}
		} else {
			try {
				return $this->db->insert($param['table'], $param['data']);
				
				$db_error = $this->db->error();
				if (!empty($db_error)) {
					return FALSE;
				}
			} catch (Exception $e) {
				return FALSE;
			}
		}
	}

	public function delete_data($param)
	{
		try {
			$this->db->where($param['where']);
			return $this->db->delete($param['table']);

			$db_error = $this->db->error();
			if (!empty($db_error)) {
				return FALSE;
			}
		} catch (Exception $e) {
			return FALSE;
		}
	}

	public function count_all_data($param)
	{
		if (!empty($param['join'])) {
			foreach ($param['join'] as $key) {
				$this->db->join($key['table'], $key['on'], $key['type']);
			}
		}
		
		if (!empty($param['where'])) {
			$this->db->where($param['where']);
		}

		return $this->db->count_all_results($param['table']);
	}

	public function GetJumlahDataFilter($table, $where){
		return $this->db->where($where)->get($table)->num_rows();
	}

}