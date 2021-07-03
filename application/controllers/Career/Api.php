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

	//method to obtain careers previously registed
	public function career_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific careers, or empty to get all stations"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('careers',array('idCareer'=>$id),TRUE);
			}else{
				$response = $this->DAO->entitySelection('careers');
			}
		}
		$this->response($response,200);
	}

	//method to save careers 
	function career_post(){
		if(count($this->post())==0){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"No data was sent",
				"validations"=>array(
					"name"=>"Required, between 3 and 30 characters in length",
					"duration"=>"Required, between 3 and 20 characters in length",
					"inscription"=>"Required, between 3 and 20 characters in length",
					"tiution"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else if(count($this->post())>5){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many data was sent",
				"validations"=>array(
					"name"=>"Required, between 3 and 30 characters in length",
					"duration"=>"Required, between 3 and 20 characters in length",
					"inscription"=>"Required, between 3 and 20 characters in length",
					"tiution"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else{
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('name','name','required|callback_career_exists');
			$this->form_validation->set_rules('duration','duration','required|max_length[40]|min_length[5]');
			$this->form_validation->set_rules('inscription','inscription','required');
			$this->form_validation->set_rules('tiution','tiution','required');


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
					"nameCareer"=>$this->post('name'),
					"duration"=>$this->post('duration'),
					"inscription"=>$this->post('inscription'),
					"tuition"=>$this->post('tiution')
				);
				$response = $this->DAO->insertData("careers",$data);
			}
		}
		$this->response($response,200);
	}


	function career_put(){
		$id = $this->get('id');
		if($id){
			$stationsExists = $this->DAO->entitySelection('careers',array('idCareer'=>$id),TRUE);
			if($stationsExists['data']){
				if(count($this->put())==0){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"No data was sent",
						"validations"=>array(
							"name"=>"Required, between 3 and 30 characters in length",
							"latitude"=>"Required, between 3 and 20 characters in length",
							"inscription"=>"Required, between 3 and 20 characters in length",
							"tiution"=>"Required, previously registered"
						),
						"data"=>null
					);
				}else if(count($this->put())>6){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"Too many data was sent",
						"validations"=>array(
							"name"=>"Required, between 3 and 30 characters in length",
							"latitude"=>"Required, between 3 and 20 characters in length",
							"inscription"=>"Required, between 3 and 20 characters in length",
							"tiution"=>"Required, previously registered"
						),
						"data"=>null
					);
				}else{
					$this->form_validation->set_data($this->put());
					$this->form_validation->set_rules('name','name','required|max_length[40]|min_length[5]');
					$this->form_validation->set_rules('duration','duration','required|max_length[40]|min_length[5]');
					$this->form_validation->set_rules('inscription','inscription','required');
					$this->form_validation->set_rules('tiution','tiution','required');


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
							"nameCareer"=>$this->put('name'),
							"duration"=>$this->put('duration'),
							"inscription"=>$this->put('inscription'),
							"tuition"=>$this->put('tiution')
						);
						$response = $this->DAO->updateData("careers",$data,array('idCareer'=>$id));
					}
				}
			}else{
				$response = array(
					"status"=>"error",
					"status_code"=>409,
					"message"=>"Id doesn't exists",
					"validations"=>array(
						"id" => "Required, valid id",
						"name"=>"Required, between 3 and 30 characters in length",
						"latitude"=>"Required, between 3 and 20 characters in length",
						"longitude"=>"Required, between 3 and 20 characters in length",
						"system"=>"Required, previously registered"
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
					"name"=>"Required, between 3 and 30 characters in length",
					"latitude"=>"Required, between 3 and 20 characters in length",
					"inscription"=>"Required, between 3 and 20 characters in length",
					"tiution"=>"Required, previously registered"
				),
				"data"=>null
			);
		}
		$this->response($response,200);
	}

	function career_delete(){
		$id = $this->get('id');
		if($id){
			$stationsExists = $this->DAO->selectEntity('careers',array('idCareer' => $id),TRUE);
			if($stationsExists['data']){
				$response = $this->DAO->deleteData('careers',array('idCareer' => $id));
			}else{
				$response = array(
					"status" => "error",
					"status_code" => 409,
					"message" => "Station id, doesn't exists",
					"validations" => NULL,
					"data" => NULL
				);
			}
		}else{
			$response = array(
					"status" => "error",
					"status_code" => 409,
					"message" => "Station id, wasn't sent",
					"validations" => NULL,
					"data" => NULL
			);
		}
		$this->response($response,$response['status_code']);
	}



	//extra validations


	//verifica si el id del systema es valido o no existe
	function valid_idsystem($str){
		$systemExists = $this->DAO->entitySelection('system',array('idSystem' => $str),TRUE);
		if($systemExists['data']){
				return TRUE;
	    }else{
			$this->form_validation->set_message('valid_idsystem','The field {field} doesnt exists');
			return FALSE;
		}
	}

	//method to validate the name of careers
	function career_exists($str){

		if(strlen($str)<5 || strlen($str)>40){
			$this->form_validation->set_message('career_exists','The field {field} must be between 5 and 40 characters in length');
			return false;
		}
		$nameExists =  $this->DAO->entitySelection('careers',array('nameCareer'=>$str),TRUE);
		if($nameExists['data']){
			$this->form_validation->set_message('career_exists','The field {field} already exists');
			return false;
		}else{
			return true;
		}
	}

	function name_valid($str){
		if(strlen($str)==0){
			return TRUE;
		}
		if($str == $this->get('id')){
			return TRUE;
		}
		if(strlen($str)<3 || strlen($str)>50){
			$this->form_validation->set_message('name_valid','The field {field} must be between 3 and 50 characters in length');
			return FALSE;	
		}

		$stationsExists =  $this->DAO->selectEntity('stations',array('nameStation'=>$str),TRUE);
		if($stationsExists['data']){
			$this->form_validation->set_message('name_valid','The field {field} already exists');
			return false;
		}else{
			return TRUE;
		}
	}
}
