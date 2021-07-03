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

	//metodo para obtener las estaciones por medio de su id y vacio para obtener todas las estaciones
	//method to obtain stations by id or empty to get all stations
	public function station_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific station, or empty to get all stations"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('station',array('idStation'=>$id),TRUE);
			}else{
				$response = $this->DAO->entitySelection('station');
			}
		}
		$this->response($response,200);
	}

	//function get station view
	public function stationview_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific station, or empty to get all stations"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('systemStation',array('idStation'=>$id),TRUE);
			}else{
				$response = $this->DAO->entitySelection('systemStation');
			}
		}
		$this->response($response,200);
	}

	//busca todas las estaciones por medio del sistema (estaciones que pertenecen a un sistema)
	//search all stations through the system
	public function stationbysystem_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get all stations belonging to a system"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('station',array('fkSystem'=>$id),FALSE);
			}else{
				$response = $this->DAO->entitySelection('station');
			}
		}
		$this->response($response,200);
	}


	//metodo para guardar la informacion de estaciones
	//method to save station information
	function station_post(){
		if(count($this->post())==0){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"No data was sent",
				"validations"=>array(
					"name"=>"Required, between 3 and 30 characters in length",
					"latitude"=>"Required, between 3 and 20 characters in length",
					"longitude"=>"Required, between 3 and 20 characters in length",
					"system"=>"Required, previously registered"
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
					"latitude"=>"Required, between 3 and 20 characters in length",
					"longitude"=>"Required, between 3 and 20 characters in length",
					"system"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else{
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('name','name','required|callback_name_exists');
			$this->form_validation->set_rules('latitude','latitude','required|max_length[20]|min_length[3]');
			$this->form_validation->set_rules('longitude','longitude','required|max_length[20]|min_length[3]');
			$this->form_validation->set_rules('system','system','callback_valid_idsystem');


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
					"nameStation"=>$this->post('name'),
					"latitudeStation"=>$this->post('latitude'),
					"longitudeStation"=>$this->post('longitude'),
					"fkSystem"=>$this->post('system')
				);
				$response = $this->DAO->insertData("station",$data);
			}
		}
		$this->response($response,200);
	}


	function station_put(){
		$id = $this->get('id');
		if($id){
			$stationsExists = $this->DAO->entitySelection('station',array('idStation'=>$id),TRUE);
			if($stationsExists['data']){
				if(count($this->put())==0){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"No data was sent",
						"validations"=>array(
							"name"=>"Required, between 3 and 30 characters in length",
							"latitude"=>"Required, between 3 and 20 characters in length",
							"longitude"=>"Required, between 3 and 20 characters in length",
							"system"=>"Required, previously registered"
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
							"longitude"=>"Required, between 3 and 20 characters in length",
							"system"=>"Required, previously registered"
						),
						"data"=>null
					);
				}else{
					$this->form_validation->set_data($this->put());
					$this->form_validation->set_rules('name','name','required|max_length[30]|min_length[3]');
					$this->form_validation->set_rules('latitude','latitude','required|max_length[20]|min_length[3]');
					$this->form_validation->set_rules('longitude','longitude','required|max_length[20]|min_length[3]');
					$this->form_validation->set_rules('system','system','callback_valid_idsystem');


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
							"nameStation"=>$this->put('name'),
							"latitudeStation"=>$this->put('latitude'),
							"longitudeStation"=>$this->put('longitude'),
							"fkSystem"=>$this->put('system')
						);
						$response = $this->DAO->updateData("station",$data,array('idStation'=>$id));
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
					"longitude"=>"Required, between 3 and 20 characters in length",
					"system"=>"Required, previously registered"
				),
				"data"=>null
			);
		}
		$this->response($response,200);
	}

	function station_delete(){
		$id = $this->get('id');
		if($id){
			$stationsExists = $this->DAO->selectEntity('station',array('idStation' => $id),TRUE);
			if($stationsExists['data']){
				$response = $this->DAO->deleteData('station',array('idStation' => $id));
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

	//metodo para saber si no existe otra estacion llamada con el mismo nombre.
	function name_exists($str){

		if(strlen($str)<3 || strlen($str)>30){
			$this->form_validation->set_message('name_exists','The field {field} must be between 3 and 30 characters in length');
			return false;
		}
		$nameExists =  $this->DAO->entitySelection('station',array('nameStation'=>$str),TRUE);
		if($nameExists['data']){
			$this->form_validation->set_message('name_exists','The field {field} already exists');
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