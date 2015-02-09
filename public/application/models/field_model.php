<?php
require_once 'cacheable_model.php';

class Field_model extends Cacheable_model 
{
	function get_id($name)
	{
		$cached_value = $this->get_from_cache($name);
		if($cached_value)
			return $cached_value;

		$result = $this->db->query('SELECT id FROM field_of_study WHERE name = ?', array($name));
		$error_num = $this->db->_error_number();

		if(!isset($result) || !isset($error_num) || $error_num !== 0)
		{
			throw new Exception('database error');
		}
		else if ($result->num_rows() > 0)
		{
			$id = $result->row()->id;
			$this->add_to_cache($name, $id);
			return $id;
		}
	}

	function get_name($id)
	{
		$cached_value = $this->get_from_cache('field'.$id);
		if($cached_value)
			return $cached_value;

		$result = $this->db->query('SELECT name FROM field_of_study WHERE id = ?', array($id));
		$error_num = $this->db->_error_number();

		if(!isset($result) || !isset($error_num) || $error_num !== 0)
		{
			throw new Exception('database error');
		}
		else if ($result->num_rows() > 0)
		{
			$name = $result->row()->name;
			$this->add_to_cache('field'.$id, $name);
			return $name;
		}
	}
}  