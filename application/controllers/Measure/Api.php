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
	//function to get the groups related to its career
	public function group_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific measure, or empty to get all groups"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('groups',array('idGroup'=>$id),TRUE);
			}else{
				$response = $this->DAO->entitySelection('groups');
			}
		}
		$this->response($response,200);
	}

	//measure by date
	public function measurebydate_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific measure, or empty to get all measure"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('measure',array('dateMeasure'=>$id),TRUE);
			}else{
				$response = $this->DAO->entitySelection('measure');
			}
		}
		$this->response($response,200);
	}



	//obtener medidas por medio de un sensor en especifico
	public function measurebysensor_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific measures belonging to a sensor"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('measure',array('fkSensor'=>$id),FALSE);
			}else{
				$response = $this->DAO->entitySelection('measure');
			}
		}
		$this->response($response,200);
	}

	//obtener medidas de los sensores de una estacion en especifico
	public function measurebystation_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific measures belonging to a sensor"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('measuresByStation',array('idStation'=>$id),FALSE);
			}else{
				$response = $this->DAO->entitySelection('measuresByStation');
			}
		}
		$this->response($response,200);
	}

	public function measurebysensorwithsta_get(){
		$id = $this->get('id');
		if(count($this->get())>1){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many params was sent",
				"validations"=>array(
					"id"=>"Send Id (Get) to get specific measures belonging to a sensor"
				),
				"data"=>null
			);
		}else{
			if($id){
				$response = $this->DAO->entitySelection('measuresByStation',array('idSensor'=>$id),FALSE);
			}else{
				$response = $this->DAO->entitySelection('measuresByStation');
			}
		}
		$this->response($response,200);
	}

	
	//function to save the data of groups
	function group_post(){
		if(count($this->post())==0){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"No data was sent",
				"validations"=>array(
					"name"=>"Required ",
					"schedule"=>"Required, must be '9:00 A.M. - 1:00 P.M.','1:00 P.M. - 5:00 P.M.'",
					"fkCareer"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else if(count($this->post())>4){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many data was sent",
				"validations"=>array(
					"name"=>"Required ",
					"schedule"=>"Required, must be '9:00 A.M. - 1:00 P.M.','1:00 P.M. - 5:00 P.M.'",
					"fkCareer"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else{
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('name','name','required|callback_group_exist');
			$this->form_validation->set_rules('schedule','schedule','required');
			$this->form_validation->set_rules('career','career','required|callback_valid_career');


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
					"nameGroup"=>$this->post('name'),
					"schedule"=>$this->post('schedule'),
					"fkCareer"=>$this->post('career')
				);
				$response = $this->DAO->insertData("groups",$data);
			}
		}
		$this->response($response,200);
	}



	//function post measure of humidity
	function measurehumidity_post(){
		if(count($this->post())==0){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"No data was sent",
				"validations"=>array(
					"date"=>"Required, correct format",
					"value"=>"Required, between 3 and 10 characters in length",
					"sensor"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else if(count($this->post())>4){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many data was sent",
				"validations"=>array(
					"date"=>"Required, correct format",
					"value"=>"Required, between 3 and 10 characters in length",
					"sensor"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else{
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('value','value','required|callback_humidity_valids');
			$this->form_validation->set_rules('sensor','sensor','callback_valid_idsensor');


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
					"dateMeasure"=>$this->post('date'),
					"valueMeasure"=>$this->post('value'),
					"fkSensor"=>$this->post('sensor')
				);
				$response = $this->DAO->insertData("measure",$data);
			}
		}
		$this->response($response,200);
	}


	//function post measure of particulate matter 2.5
	function measureparticulatemattertwofive_post(){
		if(count($this->post())==0){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"No data was sent",
				"validations"=>array(
					"date"=>"Required, correct format",
					"value"=>"Required, between 3 and 10 characters in length",
					"sensor"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else if(count($this->post())>4){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many data was sent",
				"validations"=>array(
					"date"=>"Required, correct format",
					"value"=>"Required, between 3 and 10 characters in length",
					"sensor"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else{
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('value','value','required|callback_particulatemattertwofive_valids');
			$this->form_validation->set_rules('sensor','sensor','callback_valid_idsensor');


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
					"dateMeasure"=>$this->post('date'),
					"valueMeasure"=>$this->post('value'),
					"fkSensor"=>$this->post('sensor')
				);
				$response = $this->DAO->insertData("measure",$data);
			}
		}
		$this->response($response,200);
	}


	//function post measure of particulate matter 10
	function measureparticulatematterten_post(){
		if(count($this->post())==0){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"No data was sent",
				"validations"=>array(
					"date"=>"Required, correct format",
					"value"=>"Required, between 3 and 10 characters in length",
					"sensor"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else if(count($this->post())>4){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"Too many data was sent",
				"validations"=>array(
					"date"=>"Required, correct format",
					"value"=>"Required, between 3 and 10 characters in length",
					"sensor"=>"Required, previously registered"
				),
				"data"=>null
			);
		}else{
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('value','value','required|callback_particulatematterten_valids');
			$this->form_validation->set_rules('sensor','sensor','callback_valid_idsensor');


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
					"dateMeasure"=>$this->post('date'),
					"valueMeasure"=>$this->post('value'),
					"fkSensor"=>$this->post('sensor')
				);
				$response = $this->DAO->insertData("measure",$data);
			}
		}
		$this->response($response,200);
	}

	function measure_put(){
		$id = $this->get('id');
		if($id){
			$measureExists = $this->DAO->entitySelection('measure',array('idMeasure'=>$id),TRUE);
			if($measureExists['data']){
				if(count($this->put())==0){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"No data was sent",
						"validations"=>array(
							"date"=>"Required, correct format",
							"value"=>"Required, between 3 and 10 characters in length",
							"sensor"=>"Required, previously registered"
						),
						"data"=>null
					);
				}else if(count($this->put())>6){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"Too many data was sent",
						"validations"=>array(
							"date"=>"Required, correct format",
							"value"=>"Required, between 3 and 10 characters in length",
							"sensor"=>"Required, previously registered"
						),
						"data"=>null
					);
				}else{
					$this->form_validation->set_data($this->put());
					$this->form_validation->set_rules('value','value','required|max_length[10]|min_length[3]');
					$this->form_validation->set_rules('sensor','sensor','callback_valid_idsensor');


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
							"dateMeasure"=>$this->put('date'),
							"valueMeasure"=>$this->put('value'),
							"fkSensor"=>$this->put('sensor')
						);
						$response = $this->DAO->updateData("measure",$data,array('idMeasure'=>$id));
					}
				}
			}else{
				$response = array(
					"status"=>"error",
					"status_code"=>409,
					"message"=>"Id doesn't exists",
					"validations"=>array(
						"id" => "Required, valid id",
						"date"=>"Required, correct format",
						"value"=>"Required, between 3 and 10 characters in length",
						"sensor"=>"Required, previously registered"
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
					"date"=>"Required, correct format",
					"value"=>"Required, between 3 and 10 characters in length",
					"sensor"=>"Required, previously registered"
				),
				"data"=>null
			);
		}
		$this->response($response,200);
	}


	function measure_delete(){
		$id = $this->get('id');
		if($id){
			$measureExists = $this->DAO->selectEntity('measure',array('idMeasure' => $id),TRUE);
			if($measureExists['data']){
				$response = $this->DAO->deleteData('measure',array('idMeasure' => $id));
			}else{
				$response = array(
					"status" => "error",
					"status_code" => 409,
					"message" => "Measure id, doesn't exists",
					"validations" => NULL,
					"data" => NULL
				);
			}
		}else{
			$response = array(
					"status" => "error",
					"status_code" => 409,
					"message" => "Measure id, wasn't sent",
					"validations" => NULL,
					"data" => NULL
			);
		}
		$this->response($response,$response['status_code']);
	}


	//extra validations

	function valid_career($str){
		$systemExists = $this->DAO->entitySelection('careers',array('idCareer' => $str),TRUE);
		if($systemExists['data']){
				return TRUE;
	    }else{
			$this->form_validation->set_message('valid_career','The field {field} doesnt exists');
			return FALSE;
		}
	}

	function gender_valid($str){
        if($str){
            if($str=="F" || $str == "M"){
                return true;
            }else{
                $this->form_validation->set_message('gender_valid','The {field} must be F or M');
                return false;
            }
        }else{
            $this->form_validation->set_message('gender_valid','The {field} must be F or M');
            return false;
        }
    }

    //functions of a specific values between a range
    function temperature_valids($value){

        if($value<-10 || $value>60){
            $this->form_validation->set_message('temperature_valids','The field {field} must be between -10 and 60 degrees Celsius');
            return false;
        }else{
        	return true;
        }
        
    }


    //functions of a specific values between a range for the humedy
    function humidity_valids($value){

        if($value<0 || $value>99){
            $this->form_validation->set_message('humidity_valids','The field {field} must be between 0% and 99%');
            return false;
        }else{
        	return true;
        } 
    }



    //funcion of a specific values between a range for the pm2.5
    function particulatemattertwofive_valids($value){

        if($value<0 || $value>500){
            $this->form_validation->set_message('particulatemattertwofive_valids','The field {field} must be between 0 and 500');
            return false;
        }else{
        	return true;
        } 
    }


    //funcion of a specific values between a range for the pm10
    function particulatematterten_valids($value){

        if($value<0 || $value>604){
            $this->form_validation->set_message('particulatematterten_valids','The field {field} must be between 0 and 604');
            return false;
        }else{
        	return true;
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


}
