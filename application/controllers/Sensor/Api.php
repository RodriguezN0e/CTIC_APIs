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

	public function sensor_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific sensor, or empty to get all sensors"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('sensor',array('idSensor'=>$id),TRUE);
			}else{
				$response = $this->DAO->entitySelection('sensor');
			}
		}
		$this->response($response,200);
	}

	public function sensorview_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific sensor, or empty to get all sensors"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('stationSensor',array('idSensor'=>$id),TRUE);
			}else{
				$response = $this->DAO->entitySelection('stationSensor');
			}
		}
		$this->response($response,200);
	}

	public function sensorbystation_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific sensors belonging to a station"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('sensor',array('fkStation'=>$id),FALSE);
			}else{
				$response = $this->DAO->entitySelection('sensor');
			}
		}
		$this->response($response,200);
	}

	function sensor_post(){
		if(count($this->post())==0){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"No data was sent",
				"validations"=>array(
					"name"=>"Required, between 3 and 30 characters in length",
					"station"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else if(count($this->post())>3){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many data was sent",
				"validations"=>array(
					"name"=>"Required, between 3 and 30 characters in length",
					"station"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else{
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('name','name','required|max_length[30]|min_length[3]');
			$this->form_validation->set_rules('station','station','callback_valid_idstation');


			if($this->form_validation->run()==FALSE){
				$response = array(
					"status"=>"error",
					"status_code"=>409,
					"message"=>"Validations failed, see validations object for more details",
					"validations"=>$this->form_validation->error_array(),
					"data"=>null
				);
			}else{
				$data = array(
					"nameSensor"=>$this->post('name'),
					"fkStation"=>$this->post('station')
				);
				$response = $this->DAO->insertData("sensor",$data);
			}
		}
		$this->response($response,200);
	}


	function sensor_put(){
		$id = $this->get('id');
		if($id){
			$sensorExists = $this->DAO->entitySelection('sensor',array('idSensor'=>$id),TRUE);
			if($sensorExists['data']){
				if(count($this->put())==0){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"No data was sent",
						"validations"=>array(
							"name"=>"Required, between 3 and 30 characters in length",
							"station"=>"Required, previously registered"
						),
						"data"=>null
					);
				}else if(count($this->put())>13){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"Too many data was sent",
						"validations"=>array(
							"name"=>"Required, between 3 and 30 characters in length",
							"station"=>"Required, previously registered"
						),
						"data"=>null
					);
				}else{
					$this->form_validation->set_data($this->put());
					$this->form_validation->set_rules('name','name','required|max_length[30]|min_length[3]');
					$this->form_validation->set_rules('station','station','callback_valid_idstation');


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
							"nameSensor"=>$this->put('name'),
							"fkStation"=>$this->put('station')
						);
						$response = $this->DAO->updateData("sensor",$data,array('idSensor'=>$id));
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
						"station"=>"Required, previously registered"
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
					"station"=>"Required, previously registered"
				),
				"data"=>null
			);
		}
		$this->response($response,200);
	}

	function sensor_delete(){
		$id = $this->get('id');
		if($id){
			$sensorExists = $this->DAO->entitySelection('sensor',array('idSensor'=>$id),TRUE);
			
			if($sensorExists['data']){
				$response = $this->DAO->deleteData('sensor',array('idSensor'=>$id));
			}else{
				$response = array(
					"status"=>"error",
					"status_code"=>409,
					"message"=>"Id doesn't exists",
					"validations"=>null,
					"data"=>null
				);
			}
		}else{
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Id wasn't sent",
				"validations"=>array(
					"id" => "Required, id not was sent"
				),
				"data"=>null
			);
		}
		$this->response($response,200);
	}

	//extra validation

	function valid_idstation($str){
		$stationExists = $this->DAO->entitySelection('station',array('idStation' => $str),TRUE);
		if($stationExists['data']){
				return TRUE;
	    }else{
			$this->form_validation->set_message('valid_idstation','The field {field} doesnt exists');
			return FALSE;
		}
	}

	function name_exists($str){

		if(strlen($str)<3 || strlen($str)>30){
			$this->form_validation->set_message('name_exists','The field {field} must be between 3 and 50 characters in length');
			return false;
		}
		$clasificationExists =  $this->DAO->entitySelection('station',array('nameArea'=>$str),TRUE);
		if($clasificationExists['data']){
			$this->form_validation->set_message('name_exists','The field {field} already exists');
			return false;
		}else{
			return true;
		}
	}
}





/*public function sensorfecha_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific clasification, or empty to get all clasifications"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->examplefecha('testingsensor',array('fecha'=>$id),TRUE);
			}else{
				$response = $this->DAO->examplefecha('testingsensor');
			}
		}
		$this->response($response,200);
	}
	select fecha from testingsensor where fecha like "%2020-07-13%";*/