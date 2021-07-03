<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api extends REST_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('DAO');
	}

	public function user_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific user, or empty to get all users"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('user',array('idUser'=>$id),TRUE);
			}else{
				$response = $this->DAO->entitySelection('user');
			}
		}
		$this->response($response,200);
	}

	function user_post(){
		if(count($this->post())==0){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"No data was sent",
				"validations"=>array(
					"name"=>"Required, between 3 and 50 characters in length",
					"emailuser"=>"Required, between 6 and 120 characters in length",
					"passworduser"=>"Required, between 3 and 160 characters in length",
					"confirmpass"=>"Required, between 3 and 160 characters in length",
					"type"=>"Required, Cliente o Administrador"
				),
				"data"=>null
			);
		}else if(count($this->post())>6){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many data was sent",
				"validations"=>array(
					"name"=>"Required, between 3 and 50 characters in length",
					"emailuser"=>"Required, between 6 and 120 characters in length",
					"passworduser"=>"Required, between 5 and 160 characters in length",
					"confirmpass"=>"Required, between 5 and 160 characters in length",
					"type"=>"Required, Cliente o Administrador"
				),
				"data"=>null
			);
		}else{
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('name','name','required|min_length[3]|max_length[50]');
			$this->form_validation->set_rules('emailuser','email user','required|valid_email|callback_email_exists');
			$this->form_validation->set_rules('passworduser','password user','required|min_length[5]|max_length[160]');
			$this->form_validation->set_rules('confirmpass', 'Password Confirmation','required|matches[passworduser]');
			$this->form_validation->set_rules('type', 'type','required');

			if($this->form_validation->run()==FALSE){
				$response = array(
					"status"=>"error",
					"status_code"=>409,
					"message"=>"Validations failed, see validations for more details",
					"validations"=>$this->form_validation->error_array(),
					"data"=>null
				);
			}else{
				$data = array(
					//name in database => alias
					"nameUser"=>$this->post('name'),
					"emailUser"=>$this->post('emailuser'),
					"passwordUser"=>$this->post('passworduser'),
					"typeUser"=>$this->post('type')
				);
				$response = $this->DAO->insertData("user",$data);
			}
		}
		$this->response($response,200);
	}

	function user_put(){
		$id = $this->get('id');
		if($id){
			$systemExists = $this->DAO->entitySelection('system',array('idSystem'=>$id),TRUE);
			if($systemExists['data']){
				if(count($this->put())==0){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"No data was sent",
						"validations"=>array(
							"name"=>"Required, between 3 and 30 characters in length"
						),
						"data"=>null
					);
				}else if(count($this->put())>3){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"Too many data was sent",
						"validations"=>array(
							"name"=>"Required, between 3 and 30 characters in length"
						),
						"data"=>null
					);
				}else{
					$this->form_validation->set_data($this->put());
					$this->form_validation->set_rules('name','name','required|max_length[30]|min_length[3]');


					if($this->form_validation->run()==FALSE){
						$response = array(
							"status"=>"error",
							"status_code"=>409,
							"message"=>"Validations failed, see validations objecto for more details",
							"validations"=>$this->form_validation->error_array(),
							"data"=>null
						);
					}else{
						$data = array(
							//name in database => alias
							"nameSystem"=>$this->put('name')
						);
						$response = $this->DAO->updateData("system",$data,array('idSystem'=>$id));
					}
				}
			}else{
				$response = array(
					"status"=>"error",
					"status_code"=>409,
					"message"=>"Id doesn't exists",
					"validations"=>array(
						"id" => "Required, valid id",
						"name"=>"Required, between 3 and 30 characters in length"
					),
					"data"=>null
				);
			}

		}else{
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Id wasn't sent",
				"validations"=>array(
					"id" => "Required, valid id",
					"name"=>"Required, between 3 and 30 characters in length"
				),
				"data"=>null
			);
		}
		$this->response($response,200);
	}

	function system_delete(){
		$id = $this->get('id');
		if($id){
			$systemExists = $this->DAO->selectEntity('system',array('idSystem' => $id),TRUE);
			if($systemExists['data']){
				$response = $this->DAO->deleteData('system',array('idSystem' => $id));
			}else{
				$response = array(
					"status" => "error",
					"status_code" => 409,
					"message" => "System id, doesn't exists",
					"validations" => NULL,
					"data" => NULL
				);
			}
		}else{
			$response = array(
					"status" => "error",
					"status_code" => 409,
					"message" => "System id, wasn't sent",
					"validations" => NULL,
					"data" => NULL
			);
		}
		$this->response($response,$response['status_code']);
	}


	//extra validations

	function email_exists($str){

		if(strlen($str)<6 || strlen($str)>120){
			$this->form_validation->set_message('email_exists','The field {field} must be between 6 and 120 characters in length');
			return false;
		}
		$nameExists =  $this->DAO->entitySelection('user',array('emailUser'=>$str),TRUE);
		if($nameExists['data']){
			$this->form_validation->set_message('email_exists','The field {field} already exists');
			return false;
		}else{
			return true;
		}
	}
}