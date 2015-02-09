<?php
require_once 'cacheable_model.php';

class Partner_field_model extends Cacheable_model 
{
	function get_name($partner, $field_name)
	{
		$cached_value = $this->get_from_cache($partner.$field_name);
		if($cached_value)
			return $cached_value;

		$sql = 'SELECT partner_field_of_study.name FROM field_of_study '.
			'JOIN partner_field_of_study ON field_of_study.id = partner_field_of_study.field_id '.
			'JOIN partner ON partner_field_of_study.partner_id = partner.id '.
			'WHERE field_of_study.name = ? and partner.name = ?';

		$result = $this->db->query($sql, array($field_name, $partner));
		$error_num = $this->db->_error_number();

		if(!isset($result) || !isset($error_num) || $error_num !==0){
			throw new Exception("database error");
		}
		else if ($result->num_rows() > 0)
		{
			$partner_field_name = $result->row()->name;
			$this->add_to_cache($partner.$field_name, $partner_field_name);
			return $partner_field_name;
		}
	}

	function insert($partner, $field_name, $partner_field_name)
	{
		$this->load->model('Partner_model');
		$partner_id = $this->Partner_model->get_id($partner);

		$this->load->model('Field_model');
		$name_id = $this->Field_model->get_id($field_name);

		if (isset($name_id) && isset($partner_id))
		{
			$data = array(
				'partner_id' => $partner_id,
				'field_id' => $name_id,
				'name' => $partner_field_name
			);

			$result = $this->db->insert('partner_field_of_study', $data);
			$error_num = $this->db->_error_number();

			if(!isset($result) || !isset($error_num) || ($error_num !== 1062 && $error_num !==0))
			{
				throw new Exception('database error');
			}
			if($result === TRUE)
			{
				return $result;
			}
			else if ($result === FALSE)
			{
				$error_num = $this->db->_error_number();
				return $error_num;
			}
		}
		else
		{
			return FALSE;
		}
	}

	function update($partner, $field_name, $partner_field_name)
	{
		$table = 'field_of_study '.
			'JOIN partner_field_of_study ON field_of_study.id = partner_field_of_study.field_id '.
			'JOIN partner ON partner_field_of_study.partner_id = partner.id';

		$data = array('partner_field_of_study.name' => $partner_field_name);

		$where = array(
				'partner.name' => $partner,
				'field_of_study.name' => $field_name
			);

		$result = $this->db->update($table, $data, $where);
		$error_num = $this->db->_error_number();

		if(!isset($result) || !isset($error_num) || $error_num !==0)
		{
			throw new Exception('database error');
		}
		else
		{
			return $result //$this->db->affected_rows();
		}
	}
}  