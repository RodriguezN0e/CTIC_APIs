<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('DAO');
    }//segundo ejemplo de update
    //TERCER EJEMPLO BAJAR CAMBIOS PULL


    function login_get(){
        $id = $this->get('id');
        if($id){
             $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->entitySelection('users',array('idUser'=>$id)),
            );
        }else{
            $response = array(
                "status"=>"success",
                "message"=> '',
                "data"=>$this->DAO->entitySelection('users'),
            );
        }
        $this->response($response,200);
    }

    /**exta validations bower install ngstorage**/

    public function login_post(){
        if(count($this->post())>2 || count($this->post())==0){
            $response = array(
                "status"=>"error",
                "message"=>"Demasiados parametros o ninguno parametro fue enviado",
                "validations"=>array(
                    "email"=>"parametro obligatorio",
                    "password"=>"parametro obligatorio"
                )
            );
        }else{
            $this->form_validation->set_data($this->post());
            $this->form_validation->set_rules('email','email','required');
            $this->form_validation->set_rules('password','password','required');


            if($this->form_validation->run()==false){
                $response = array(
                    "status"=>"error",
                    "status_code"=>409,
                    "message"=>"validaciones incorrectas, ver validaciones para mas detalles",
                    "validations"=>$this->form_validation->error_array(),
                    "data"=>null
                );
            }else{
                $userExists = $this->DAO->selectEntity('user',array('emailUser'=>$this->post('email'),'passwordUser'=>$this->post('password')));
                if($userExists['data']){
                    $response = array(
                        "status"=>"success",
                        "message"=>"informacion cargada correcatmente",
                        "data"=>array(
                            "idUser"=>$userExists['data']->idUser,
                            "nameUser"=>$userExists['data']->nameUser,
                            "emailUser"=>$userExists['data']->emailUser,
                            "passwordUser"=>$userExists['data']->passwordUser,
                            "typeUser"=>$userExists['data']->typeUser
                        )
                    );

                }else{
                    $response = array(
                        "status"=>"error",
                        "message"=>"Email y/o contraseña incorrectos"
                    );
                }
            }
        }
        $this->response($response,200);
    }//este es un ejemplo de update

}
