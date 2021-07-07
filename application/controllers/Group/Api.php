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


	
	//function to save the data of groups
	function group_post(){
		if(count($this->post())==0){
			$response = array(
				"status"=>"error",
				"status_code"=>409,
				"message"=>"No data was sent",
				"validations"=>array(
					"name"=>"Required ",
					"day"=>"Required, must be Lunes, Martes, Miercoles, Jueves, Viernes, Sabado o Domingo",
					"schedule"=>"Required, must be '9:00 A.M. - 1:00 P.M.','1:00 P.M. - 5:00 P.M.'"
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
					"day"=>"Required, must be Lunes, Martes, Miercoles, Jueves, Viernes, Sabado o Domingo",
					"schedule"=>"Required, must be '9:00 A.M. - 1:00 P.M.','1:00 P.M. - 5:00 P.M.'"
				),
				"data"=>null
			);
		}else{
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('name','name','required|callback_group_exist');
			$this->form_validation->set_rules('day','day','required|callback_day_valid');
			$this->form_validation->set_rules('schedule','schedule','required|callback_schedule_valid');

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
					"dayScheduleGroup"=>$this->post('day'),
					"schedule"=>$this->post('schedule')
				);
				$response = $this->DAO->insertData("groups",$data);
			}
		}
		$this->response($response,200);
	}

	//function to update infromation of a group
	function group_put(){
		$id = $this->get('id');
		if($id){
			$measureExists = $this->DAO->entitySelection('groups',array('idGroup'=>$id),TRUE);
			if($measureExists['data']){
				if(count($this->put())==0){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"No data was sent",
						"validations"=>array(
							"name"=>"Required ",
							"day"=>"Required, must be Lunes, Martes, Miercoles, Jueves, Viernes, Sabado o Domingo",
							"schedule"=>"Required, must be '9:00 A.M. - 1:00 P.M.','1:00 P.M. - 5:00 P.M.'"
						),
						"data"=>null
					);
				}else if(count($this->put())>6){
					$response = array(
						"status"=>"error",
						"status_code"=>409,
						"message"=>"Too many data was sent",
						"validations"=>array(
							"name"=>"Required ",
							"day"=>"Required, must be Lunes, Martes, Miercoles, Jueves, Viernes, Sabado o Domingo",
							"schedule"=>"Required, must be '9:00 A.M. - 1:00 P.M.','1:00 P.M. - 5:00 P.M.'"
						),
						"data"=>null
					);
				}else{
					$this->form_validation->set_data($this->put());
					$this->form_validation->set_rules('name','name','required');
					$this->form_validation->set_rules('day','day','required|callback_day_valid');
					$this->form_validation->set_rules('schedule','schedule','required|callback_schedule_valid');


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
							"nameGroup"=>$this->put('name'),
							"dayScheduleGroup"=>$this->put('day'),
							"schedule"=>$this->put('schedule')
						);
						$response = $this->DAO->updateData("groups",$data,array('idGroup'=>$id));
					}
				}
			}else{
				$response = array(
					"status"=>"error",
					"status_code"=>409,
					"message"=>"Id doesn't exists",
					"validations"=>array(
						"id" => "Required, valid id",
						"name"=>"Required ",
						"day"=>"Required, must be Lunes, Martes, Miercoles, Jueves, Viernes, Sabado o Domingo",
						"schedule"=>"Required, must be '9:00 A.M. - 1:00 P.M.','1:00 P.M. - 5:00 P.M.'"
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
					"name"=>"Required ",
					"day"=>"Required, must be Lunes, Martes, Miercoles, Jueves, Viernes, Sabado o Domingo",
					"schedule"=>"Required, must be '9:00 A.M. - 1:00 P.M.','1:00 P.M. - 5:00 P.M.'"
				),
				"data"=>null
			);
		}
		$this->response($response,200);
	}


	function group_delete(){
		$id = $this->get('id');
		if($id){
			$measureExists = $this->DAO->selectEntity('groups',array('idGroup' => $id),TRUE);
			if($measureExists['data']){
				$response = $this->DAO->deleteData('groups',array('idGroup' => $id));
			}else{
				$response = array(
					"status" => "error",
					"status_code" => 409,
					"message" => "Group id, doesn't exists",
					"validations" => NULL,
					"data" => NULL
				);
			}
		}else{
			$response = array(
					"status" => "error",
					"status_code" => 409,
					"message" => "Group id, wasn't sent",
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

	function schedule_valid($str){
        if($str){
            if($str=="9:00 A.M. - 1:00 P.M." || $str == "1:00 P.M. - 5:00 P.M."){
                return true;
            }else{
                $this->form_validation->set_message('schedule_valid','The {field} must be 9:00 A.M. - 1:00 P.M. or 1:00 P.M. - 5:00 P.M.');
                return false;
            }
        }else{
            $this->form_validation->set_message('schedule_valid','The {field} must be 9:00 A.M. - 1:00 P.M. or 1:00 P.M. - 5:00 P.M.');
            return false;
        }
    }

    function day_valid($str){
        if($str){
            if($str=="Lunes" || $str == "Martes" || $str == "Miercoles" || $str == "Jueves" || $str == "Viernes" || $str == "Sabado" || $str == "Domingo"){
                return true;
            }else{
                $this->form_validation->set_message('day_valid','The {field} must be Lunes, Martes, Miercoles, Jueves, Viernes, Sabado o Domingo');
                return false;
            }
        }else{
            $this->form_validation->set_message('day_valid','The {field} must be Lunes, Martes, Miercoles, Jueves, Viernes, Sabado o Domingo');
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

	
    //method to validate the name of careers
	function group_exist($str){

		if(strlen($str)<4 || strlen($str)>20){
			$this->form_validation->set_message('group_exist','The field {field} must be between 4 and 20 characters in length');
			return false;
		}
		$nameExists =  $this->DAO->entitySelection('groups',array('nameGroup'=>$str),TRUE);
		if($nameExists['data']){
			$this->form_validation->set_message('group_exist','The field {field} already exists');
			return false;
		}else{
			return true;
		}
	}


}
