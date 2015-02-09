<?php 
require_once(APPPATH.'libraries/REST_Controller.php');


class Course_map extends REST_Controller
{
	public function index_get()
	{
		try
		{
			$name = $this->get('name');
			$partner = $this->get('partner');

			if(!empty($name) && !empty($partner))
			{
				$this->load->model('Partner_field_model');
				$partner_name = $this->Partner_field_model->get_name($partner, $name);

				if($partner_name)
				{
					$this->response(array(
							'name' => $partner_name
						), 200);
				}
				else
				{
					$this->response(array('error' => $name.' at '.$partner.' not found'), 404);
				}
			}
			else
			{
				$this->response(array('error' => 'invalid parameters'), 400);
			}
		}
		catch (Exception $e)
		{
			$this->response(array('error' => 'internal server error'), 500);
		}
	}

	public function index_post()
	{
		try
		{
			$partner = $this->post('partner');
			$partner_field_name = $this->post('partner_field_name');
			$field_name = $this->post('field_name');

			if(!empty($partner) && !empty($partner_field_name) && !empty($field_name))
			{
				$this->load->model('Partner_field_model');
				$result = $this->Partner_field_model->insert($partner, $field_name, $partner_field_name);

				if($result === True)
				{
					$this->response(array(
							'partner' => $partner, 
							'field_name' => $field_name, 
							'partner_field_name' => $partner_field_name
						), 201);
				}
				else if ($result === 1062)
				{
					$this->response(array('error' => 'duplicate entry'), 409);
				}
				else
				{
					$this->response(array('error' => 'partner or field not found'), 404);
				}
			}
			else
			{
				$this->response(array('error' => 'invalid parameters'), 400);
			}
		}
		catch (Exception $e)
		{
			$this->response(array('error' => 'server error'), 500);
		}
	}

	public function index_put()
	{
		try
		{
			$partner = $this->put('partner');
			$partner_field_name = $this->put('partner_field_name');
			$field_name = $this->put('field_name');

			if(!empty($partner) && !empty($partner_field_name) && !empty($field_name))
			{
				$this->load->model('Partner_field_model');
				$result = $this->Partner_field_model->update($partner, $field_name, $partner_field_name);

				//###############################
				$this->response($result);
				//###############################

				if($result)
				{
					$this->response(array(
							'partner' => $partner, 
							'field_name' => $field_name, 
							'partner_field_name' => $partner_field_name
						), 200);
				}
				else
				{
					$this->response(array('error' => $field_name.' at '.$partner.' not found'), 404);
				}
			}
			else
			{
				$this->response(array('error' => 'invalid parameters'), 400);
			}
		}
		catch (Exception $e)
		{
			$this->response(array('error' => 'server error'), 500);
		}
	}
}