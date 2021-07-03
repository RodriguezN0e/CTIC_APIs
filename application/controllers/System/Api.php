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

	public function system_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific system, or empty to get all system"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('system',array('idSystem'=>$id),TRUE);
			}else{
				$response = $this->DAO->entitySelection('system');
			}
		}
		$this->response($response,200);
	}

	function system_post(){
		if(count($this->post())==0){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"No data was sent",
				"validations"=>array(
					"name"=>"Required, between 3 and 30 characters in length"
				),
				"data"=>null
			);
		}else if(count($this->post())>2){
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
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('name','name','required|callback_name_exists');

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
					"nameSystem"=>$this->post('name')
				);
				$response = $this->DAO->insertData("system",$data);
			}
		}
		$this->response($response,200);
	}

	function system_put(){
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

	function name_exists($str){

		if(strlen($str)<3 || strlen($str)>30){
			$this->form_validation->set_message('name_exists','The field {field} must be between 3 and 30 characters in length');
			return false;
		}
		$nameExists =  $this->DAO->entitySelection('system',array('nameSystem'=>$str),TRUE);
		if($nameExists['data']){
			$this->form_validation->set_message('name_exists','The field {field} already exists');
			return false;
		}else{
			return true;
		}
	}
}